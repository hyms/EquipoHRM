<template>
    <div class="col-12">
        <div class="element-wrapper">
            <div class="element-box">
                <b-alert show dismissible variant="danger" v-if="message_error">
                    {{ message_error }}
                    <p v-for="(item, key) in validator" :key="key">
                        {{ key }}:{{ item }}
                    </p>
                </b-alert>
                <form ref="form" @submit.stop.prevent="submitFrom">
                    <vue-form-generator
                            :schema="schema"
                            :model="model"
                    ></vue-form-generator>
                    <div class="row">
                        <div class="col text-center">
                            <b-button variant="primary" v-if="disabled" @click="changeDisabled(false)">Editar</b-button>
                            <b-button variant="danger" @click="resetForm()" v-if="!disabled">Cancelar</b-button>
                            <b-button variant="primary" v-if="!disabled" @click="submitFrom()">Guardar</b-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import Helpers from "@/store/funcions";
    import moment from "moment";

    export default {
        data() {
            return {
                path: "/api/personal",
                validator: [],
                model: {
                    apellidos: "",
                    nombres: "",
                    ci: "",
                    telefono_propio: "",
                    telefono_referencia: "",
                    fecha_nacimiento: "",
                    profesion: "",
                    direccion: "",
                    estado_civil: "",
                    fecha_ingreso: ""
                },
                schema: {
                    fields: [
                        {
                            type: "input",
                            inputType: "text",
                            label: "Apellidos",
                            model: "apellidos",
                            required: true,
                            disabled: this.disabled,
                            attributes: {placeholder: "Apellidos"}
                        },
                        {
                            type: "input",
                            inputType: "text",
                            label: "Nombres",
                            model: "nombres",
                            required: true,
                            disabled: this.disabled,
                            attributes: {placeholder: "Nombres"}
                        },
                        {
                            type: "input",
                            inputType: "text",
                            label: "Carnet de Identidad",
                            model: "ci",
                            required: true,
                            disabled: this.disabled,
                            attributes: {placeholder: "Carnet de Identidad"}
                        },
                        {
                            type: "input",
                            inputType: "text",
                            label: "Telefono Propio",
                            model: "telefono_propio",
                            required: true,
                            disabled: this.disabled,
                            attributes: {placeholder: "Telefono Propio"}
                        },
                        {
                            type: "input",
                            inputType: "text",
                            label: "Telefono Referencia",
                            model: "telefono_referencia",
                            disabled: this.disabled,
                            attributes: {placeholder: "Telefono Referencia"}
                        },
                        {
                            type: "input",
                            inputType: "date",
                            label: "Fecha de Nacimiento",
                            model: "fecha_nacimiento",
                            required: true,
                            disabled: this.disabled,
                            attributes: {placeholder: "Fecha de Nacimiento"}
                        },
                        {
                            type: "input",
                            inputType: "text",
                            label: "Profesion",
                            model: "profesion",
                            disabled: this.disabled,
                            attributes: {placeholder: "Profesion"}
                        },
                        {
                            type: "textArea",
                            label: "Direccion",
                            model: "direccion",
                            disabled: this.disabled,
                            placeholder: "Direccion"
                        },
                        {
                            type: "select",
                            label: "Estado Civil",
                            model: "estado_civil",
                            disabled: this.disabled,
                            selectOptions: {noneSelectedText: "Selecciona tu estado civil"},
                            values: this.estadoCivil()
                        },
                        {
                            type: "input",
                            inputType: "date",
                            label: "Fecha de Ingreso",
                            model: "fecha_ingreso",
                            required: true,
                            disabled: this.disabled,
                            attributes: {placeholder: "Fecha de Ingreso"}
                        }
                    ]
                },
                message_error: false,
                disabled: false,
                id: null,
            };
        },
        methods: {
            changeDisabled(disabled) {
                this.disabled = disabled;
                Object.keys(this.schema.fields).forEach(key => {
                    this.schema.fields[key].disabled = disabled;
                });
            },
            async resetForm() {
                Object.keys(this.model).forEach(key => {
                    this.model[key] = "";
                });
                this.message_error = false;
                this.validator = [];
                if (this.$route.query.id) {
                    this.model['id'] = this.$route.query.id;
                    await this.loadForm(this.$route.query.id);
                    this.changeDisabled(true);
                } else {
                    this.changeDisabled(false);
                }
            },
            async loadForm(id) {
                // Push the name to submitted names
                await axios.get(this.path, {
                    params: {id: id}
                })
                    .then(({data}) => {
                        if (data["status"] === 0) {
                            Object.entries(data["data"]).forEach(([key, value]) => {
                                if (this.model[key] !== undefined) this.model[key] = value;
                            });
                            this.disabled = true;
                        } else {
                            this.message_error = data["message"];
                            this.validator = data["data"];
                            this.disabled = false;
                        }
                    })
                    .catch();
            },
            submitFrom() {
                this.model['fecha_nacimiento'] = moment(this.model['fecha_nacimiento']).format("YYYY-MM-DD");
                this.model['fecha_ingreso'] = moment(this.model['fecha_ingreso']).format("YYYY-MM-DD");

                axios.post(this.path, this.model)
                    .then(({data}) => {
                        if (data["status"] === 0) {
                            // Hide the modal manually
                            this.$route.query['id'] = data['data']['id'];
                            this.loadForm();
                        } else {
                            this.m_error = data["data"];
                        }
                    })
                    .catch();
            },
            estadoCivil() {
                return Helpers.estadoCivil();
            }
        },
        async created() {
            await this.resetForm();
        }
    };
</script>
