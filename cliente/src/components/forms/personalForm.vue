<template>
    <div>
        <b-modal
                :id="nameModal"
                :title="(idForm ? 'Modificar' : 'Nuevo') + ' Personal'"
                @show="loadModal"
                @hidden="resetModal"
                @ok="handleOk"
                okTitle="Guardar"
                cancelTitle="Cancelar"
        >
            <b-alert
                    show
                    dismissible
                    variant="danger"
                    v-for="(value, key) in m_error"
                    :key="key"
            >
                {{ value }}
            </b-alert>
            <form ref="form" @submit.stop.prevent="handleSubmit">
                <template v-for="(input, key) in form">
                    <div class="form-check" v-if="input.type === 'check'" :key="key">
                        <input
                                class="form-check-input"
                                type="checkbox"
                                v-model="form[key].value"
                                :id="key"
                        />
                        <label class="form-check-label" :for="key">
                            {{ input.label }}
                        </label>
                    </div>
                    <template v-else>
                        <b-form-group
                                :state="input.state"
                                :label="input.label"
                                :label-for="key"
                                :key="key"
                        >
                            <b-form-input
                                    v-if="input.type === 'text'"
                                    :id="key"
                                    v-model="form[key].value"
                                    :state="input.state"
                            ></b-form-input>
                            <b-form-input
                                    :id="key"
                                    v-model="form[key].value"
                                    :state="input.state"
                                    v-if="input.type === 'password'"
                                    type="password"
                            ></b-form-input>
                            <b-form-textarea
                                    :id="key"
                                    v-model="form[key].value"
                                    :state="input.state"
                                    v-if="input.type === 'textarea'"
                            ></b-form-textarea>
                            <b-form-select
                                    v-if="input.type === 'select'"
                                    :id="key"
                                    v-model="form[key].value"
                                    :state="input.state"
                                    :options="estadoCivil()"
                            ></b-form-select>
                            <b-form-datepicker
                                    v-if="input.type === 'datepicker'"
                                    :id="key"
                                    v-model="form[key].value"
                                    :state="input.state"
                                    locale="es"
                                    :show-decade-nav="true"
                                    :date-format-options="{
                  year: 'numeric',
                  month: '2-digit',
                  day: '2-digit'
                }"
                            ></b-form-datepicker>
                        </b-form-group>
                    </template>
                </template>
            </form>
        </b-modal>
    </div>
</template>

<script>
    import axios from "axios";
    import Helpers from "@/store/funcions";

    export default {
        data() {
            return {
                path: "/api/personal",
                form: {
                    nombres: {value: "", state: null, type: "text", label: "Nombres"},
                    apellidos: {value: "", state: null, type: "text", label: "Apellidos"},
                    ci: {value: "", state: null, type: "text", label: "CI"},
                    telefono_propio: {
                        value: "",
                        state: null,
                        type: "text",
                        label: "Telefono Propio"
                    },
                    telefono_referencia: {
                        value: "",
                        state: null,
                        type: "text",
                        label: "Telefono Referencia"
                    },
                    fecha_nacimiento: {
                        value: "",
                        state: null,
                        type: "datepicker",
                        label: "Fecha de Nacimiento"
                    },
                    profesion: {value: "", state: null, type: "text", label: "Profesion"},
                    direccion: {value: "", state: null, type: "text", label: "Direccion"},
                    estado_civil: {
                        value: "",
                        state: null,
                        type: "select",
                        label: "Estado Civil"
                    },
                    estado: {
                        value: false, state: null, type: "check",
                        label: "Estado"
                    }
                },
                m_error: false
            };
        },
        props: {
            idForm: Number,
            nameModal: String
        },
        methods: {
            checkFormValidity() {
                const valid = this.$refs.form.checkValidity();
                //this.form.nombres.state = valid;
                this.m_error = false;
                return valid;
            },
            resetModal() {
                Object.keys(this.form).forEach(key => {
                    this.form[key].value = "";
                    this.form[key].state = null;
                });
                this.m_error = false;
            },
            loadModal() {
                this.resetModal();
                if (this.idForm) {
                    // Push the name to submitted names
                    axios
                        .get(this.path, {
                            params: {id: this.idForm}
                        })
                        .then(({data}) => {
                            if (data["status"] === 0) {
                                Object.entries(data["data"][0]).forEach(([key, value]) => {
                                    if (this.form[key]) this.form[key].value = value;
                                });
                            } else {
                                this.m_error = data["data"];
                            }
                        })
                        .catch();
                }
            },
            handleOk(bvModalEvt) {
                // Prevent modal from closing
                bvModalEvt.preventDefault();
                // Trigger submit handler
                this.handleSubmit();
            },
            handleSubmit() {
                // Exit when the form isn't valid
                /* if (!this.checkFormValidity()) {
                     return;
                 }*/
                // Push the name to submitted names
                let formData = {};
                Object.entries(this.form).forEach(([key, value]) => {
                    formData[key] = value.value;
                });

                if (this.idForm) formData["id"] = this.idForm;

                axios
                    .post(this.path, formData)
                    .then(({data}) => {
                        if (data["status"] === 0) {
                            // Hide the modal manually
                            this.$nextTick(() => {
                                this.$emit("finish", true);
                                this.$bvModal.hide(this.nameModal);
                            });
                        } else {
                            this.m_error = data["data"];
                        }
                    })
                    .catch();
            },
            estadoCivil() {
                return Helpers.estadoCivil();
            }
        }
    };
</script>
