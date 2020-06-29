<template>
    <div>
        <b-modal
                id="modalRol"
                :title="this.idForm?'Modificar Rol':'Nuevo Rol'"
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
                        :state="name.state"
                        label="Nombre"
                        label-for="name-input"
                        invalid-feedback="Nombre es requerido"
                >
                    <b-form-input
                            id="name-input"
                            v-model="name.value"
                            :state="name.state"
                            required
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                        :state="password.state"
                        label="Contraseña"
                        label-for="pass-input"
                >
                    <b-form-input
                            id="pass-input"
                            v-model="password.value"
                            :state="password.state"
                            type="password"
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                        :state="alias.state"
                        label="Alias"
                        label-for="alias-input"
                >
                    <b-form-input
                            id="alias-input"
                            v-model="alias.value"
                            :state="alias.state"
                    ></b-form-input>
                </b-form-group>
                 <b-form-group
                        :state="detail.state"
                        label="Detalle"
                        label-for="detail-input"
                >
                    <b-form-input
                            id="detail-input"
                            v-model="detail.value"
                            :state="detail.state"
                    ></b-form-input>
                </b-form-group>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" v-model="estado.value" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck1">
                        Estado
                    </label>
                </div>
                <b-form-select
                        v-model="rol"
                        :options="roles"
                        value-field="id"
                        text-field="nombre"
                ></b-form-select>
            </form>
        </b-modal>
    </div>
</template>

<script>
    import axios from 'axios'


    export default {
        data() {
            return {
                path:'/api/usuarios',
                name: {
                    value: '',
                    state: null,
                },
                password: {
                    value: '',
                    state: null,
                },
                alias: {
                    value: '',
                    state: null,
                },
                detail: {
                    value: '',
                    state: null,
                },
                estado: {
                    value: false,
                    state: null,
                },
                rol: '',
                roles:{},
                m_error: false,
            }
        },
        props: {
            idForm: Number,
        },
        methods: {
            checkFormValidity() {
                const valid = this.$refs.form.checkValidity();
                this.name.state = valid;
                this.m_error = false;
                return valid
            },
            resetModal() {
                this.name={value: '',state:null};
                this.password={value: '',state:null};
                this.detail={value: '',state:null};
                this.alias={value: '',state:null};
                this.rol='';
                this.estado={value: false,state:null};
                this.m_error = false;
            },
            loadModal() {
                this.resetModal();
                this.loadRoles();
                if (this.idForm) {
                    // Push the name to submitted names
                    axios.get(
                        this.path+'/get',
                        {
                            params: {'id': this.idForm}
                        })
                        .then(({data}) => {
                            if (data['status'] === 0) {
                                this.name['value'] = data['data'][0]['name'];
                                this.estado['value'] = data['data'][0]['estado'];
                                this.password['value'] = (data['data'][0]['password']?'******':'');
                                this.alias['value'] = data['data'][0]['alias'];
                                this.detail['value'] = data['data'][0]['detail'];
                                this.rol = data['data'][0]['rol'];
                            } else {
                                this.m_error = data['data'];
                            }
                        })
                        .catch();
                }
            },
            async loadRoles(){
                await axios.get('api/roles/get')
                    .then(({data}) => {
                        if (data['status'] === 0) {
                            this.roles=data['data']['all'];
                        } else {
                            this.m_error = data['data'];
                        }
                    })
                    .catch();
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
                    'name': this.name.value,
                    'password': this.password.value,
                    'alias': this.alias.value,
                    'detail': this.detail.value,
                    'estado': this.estado.value,
                    'rol': this.rol
                };
                if (this.idForm)
                    formData['id'] = this.idForm;

                axios.post(
                    this.path+'/post',
                    formData
                )
                    .then(({data}) => {
                        if (data['status'] === 0) {
                            // Hide the modal manually
                            this.$nextTick(() => {
                                this.$emit('finish', true);
                                this.$bvModal.hide('modalRol');
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