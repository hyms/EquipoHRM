<template>
    <div>
        <b-modal
                id="modalUnidadesNegocio"
                :title="(this.idForm?'Modificar ':'Nueva')+' Unidad de Negocio'"
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
                            required
                    ></b-form-textarea>
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
                path: '/api/unidadesnegocio',
                nombre: {value: '', state: null},
                direccion: {value: '', state: null},
                telefono: {value: '', state: null},
                celular: {value: '', state: null},
                fax: {value: '', state: null},
                ciudad: {value: '', state: null},
                departamento: {value: '', state: null},
                encargado: {value: '', state: null},
                email: {value: '', state: null},
                web: {value: '', state: null},
                fecha_nacimiento: {value: '', state: null},
                estado: {value: false, state: null},
                m_error: false,
            }
        },
        props: {
            idForm: Number,
        },
        methods: {
            checkFormValidity() {
                const valid = this.$refs.form.checkValidity();
                this.nombre.state = valid;
                this.detalle.state = valid;
                this.m_error = false;
                return valid
            },
            resetModal() {
                this.nombre= {value: '', state: null};
                this.direccion= {value: '', state: null};
                this.telefono= {value: '', state: null};
                this.celular= {value: '', state: null};
                this.fax= {value: '', state: null};
                this.ciudad= {value: '', state: null};
                this.departamento= {value: '', state: null};
                this.encargado= {value: '', state: null};
                this.email= {value: '', state: null};
                this.web= {value: '', state: null};
                this.fecha_nacimiento= {value: '', state: null};
                this.estado= {value: false, state: null};
                this.m_error = false;
            },
            loadModal() {
                this.resetModal();
                if (this.idForm) {
                    // Push the name to submitted names
                    axios.get(
                        this.path + '/get',
                        {
                            params: {'id': this.idForm}
                        })
                        .then(({data}) => {
                            if (data['status'] === 0) {
                                data['data'][0].forEach((value,key)=>{
                                    this[key].value = value;
                                });
                            } else {
                                this.m_error = data['data'];
                            }
                        })
                        .catch();
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
                    'detalle': this.detalle.value,
                    'estado': this.estado.value
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
                                this.$bvModal.hide('modalUnidadesNegocio');
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