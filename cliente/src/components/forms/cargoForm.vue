<template>
    <div>
        <b-modal
                id="modalCargo"
                :title="(this.idForm?'Modificar':'Nuevo')+' Cargo'"
                @show="loadModal"
                @hidden="resetModal"
                @ok="handleOk"
            okTitle="Guardar"
            cancelTitle="Cancelar"
        >
            <b-alert show dismissible variant="danger" v-for="(value, key) in m_error" :key="key">
                {{ value }}
            </b-alert>
            <form ref="form" @submit.stop.prevent="handleSubmit">
                <b-form-group
                        :state="nombre.state"
                        label="Nombre"
                        label-for="name-input"
                        invalid-feedback="Nombre es requerido"
                >
                    <b-form-input
                            id="name-input"
                            v-model="nombre.value"
                            :state="nombre.state"
                            required
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                        :state="detalle.state"
                        label="Detalle"
                        label-for="detalle-input"
                        invalid-feedback="Detalle es requerido"
                >
                    <b-form-textarea
                            id="detalle-input"
                            v-model="detalle.value"
                            :state="detalle.state"
                    ></b-form-textarea>
                </b-form-group>
                <b-form-group
                        :state="cargo_padre.state"
                        label="Padre"
                        label-for="padre-input"
                >
                    <b-form-select
                            v-model="cargo_padre.value"
                            :options="padres"
                            value-field="id"
                            text-field="nombre"
                    ></b-form-select>
                </b-form-group>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" v-model="estado.value" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck1">
                        Estado
                    </label>
                </div>
            </form>
        </b-modal>
    </div>
</template>

<script>
    import axios from 'axios'


    export default {
        data() {
            return {
                path: '/api/cargos',
                nombre: {value: '', state: null},
                detalle: {value: '', state: null},
                estado: {value: false, state: null},
                cargo_padre: {value: false, state: null},
                m_error: false,
            }
        },
        props: {
            idForm: Number,
            padres: Array,
        },
        methods: {
            checkFormValidity() {
                const valid = this.$refs.form.checkValidity();
                this.nombre.state = valid;
                this.detalle.state = valid;
                this.cargo_padre.state = valid;
                this.m_error = false;
                return valid
            },
            resetModal() {
                this.nombre = {value: '', state: null};
                this.cargo_padre = {value: '', state: null};
                this.detalle = {value: '', state: null};
                this.estado = {value: false, state: null};
                this.m_error = false;
            },
            loadModal() {
                this.resetModal();
                this.loadPadre();
                if (this.idForm) {
                    // Push the name to submitted names
                    axios.get(
                        this.path + '/get',
                        {
                            params: {'id': this.idForm}
                        })
                        .then(({data}) => {
                            if (data['status'] === 0) {
                                this.nombre.value = data['data'][0]['nombre'];
                                this.detalle.value = data['data'][0]['detalle'];
                                this.estado.value = data['data'][0]['estado'];
                                this.cargo_padre.value = data['data'][0]['cargo_padre'];
                            } else {
                                this.m_error = data['data'];
                            }
                        })
                        .catch();
                }
            },
            loadPadre() {
                if (this.padres) {
                    const index = this.padres.findIndex((element) => element.id === this.idForm);
                    if (index > -1) {
                        this.padres.splice(index, 1);
                    }
                }
            },
            handleOk(bvModalEvt) {
                // Prevent modal from closing
                bvModalEvt.preventDefault();
                // Trigger submit handler
                this.handleSubmit()
            },
            handleSubmit() {
                // Exit when the form isn't valid
                if (!this.checkFormValidity()) {
                    return
                }
                // Push the name to submitted names
                var formData = {
                    'nombre': this.nombre.value,
                    'estado': this.estado.value,
                    'detalle': this.detalle.value,
                    'cargo_padre': this.cargo_padre.value
                };
                if (this.idForm)
                    formData['id'] = this.idForm;

                axios.post(
                    this.path + '/post',
                    formData
                )
                    .then(({data}) => {
                        if (data['status'] === 0) {
                            // Hide the modal manually
                            this.$nextTick(() => {
                                this.$emit('finish', true);
                                this.$bvModal.hide('modalCargo');
                            });
                        } else {
                            this.m_error = data['data'];
                        }
                    })
                    .catch();
            },
            close() {
            }
        }
    }
</script>