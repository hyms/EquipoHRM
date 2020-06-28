<template>
    <div>
        <b-modal
                id="modalRol"
                title="Nuevo Rol"
                @show="loadModal"
                @hidden="resetModal"
                @ok="handleOk"
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
                nombre:{
                    value: '',
                    state: null,
                },
                estado:{
                    value: false,
                    state: null,
                },
                m_error: false,
            }
        },
        props:{
            idForm:Number,
        },
        methods: {
            checkFormValidity() {
                const valid = this.$refs.form.checkValidity();
                this.nombre.state = valid;
                this.estado.state = valid;
                this.m_error = false;
                return valid
            },
            resetModal() {
                this.nombre.value = '';
                this.nombre.state = null;
                this.estado.value = false;
                this.estado.state = null;
                this.m_error= false;
            },
            loadModal() {
                this.resetModal();
                if (this.idForm) {
                    // Push the name to submitted names
                    axios.get(
                        'api/roles/get',
                        {
                            params: {'id': this.idForm}
                        })
                        .then(({data}) => {
                            if (data['status'] === 0) {
                                this.nombre.value = data['data'][0]['nombre'];
                                this.estado.value = data['data'][0]['estado'];
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
                    'estado':this.estado.value
                };
                if(this.idForm)
                    formData['id']=this.idForm;

                axios.post(
                    'api/roles/post',
                    formData
                    )
                    .then(({data}) => {
                        if (data['status'] === 0) {
                            // Hide the modal manually
                            this.$nextTick(() => {
                                this.$bvModal.hide('modalRol');
                                this.$emit('finish',true);
                            });

                        }
                        else {
                            this.m_error = data['data'];
                        }
                    })
                    .catch();
            }
        }
    }
</script>