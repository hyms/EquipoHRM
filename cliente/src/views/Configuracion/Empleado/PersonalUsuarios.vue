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
                <form ref="form" @submit.stop.prevent="submitForm()">
                    <vue-form-generator
                            :schema="schema"
                            :model="model"
                    ></vue-form-generator>
                    <div class="row">
                        <div class="col text-center">
                            <b-button
                                    variant="primary"
                                    v-if="disabled"
                                    @click="changeDisabled(false)"
                            >Editar
                            </b-button
                            >
                            <b-button variant="danger" @click="resetForm()" v-if="!disabled"
                            >Cancelar
                            </b-button
                            >
                            <b-button variant="primary" v-if="!disabled" @click="submitForm()"
                            >Guardar
                            </b-button
                            >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from "axios";
    import "@/store/funcions";

    export default {
        data() {
            return {
                pageTitle: "Usuarios",
                path: "/api/personal",
                pathget: "/api/usuarios",
                pathpost: "/api/personal/usuario",
                isBusy: false,
                disabled: false,
                validator: [],
                roles: [],
                model: {
                    username: "",
                    password: "",
                    alias: "",
                    email: "",
                    rol: "",
                    estado: "",
                    empleado: ""
                },
                schema: {
                    fields: [
                        {
                            type: "input",
                            inputType: "text",
                            label: "Usuario",
                            model: "username",
                            required: true,
                            disabled: this.disabled,
                            attributes: {placeholder: "Usuario"}
                        },
                        {
                            type: "input",
                            inputType: "password",
                            label: "Contraseña",
                            model: "password",
                            disabled: this.disabled,
                            attributes: {placeholder: "Contraseña"}
                        },
                        {
                            type: "input",
                            inputType: "text",
                            label: "Alias",
                            model: "alias",
                            disabled: this.disabled,
                            attributes: {placeholder: "Alias"}
                        },
                        {
                            type: "input",
                            inputType: "email",
                            label: "Email",
                            model: "email",
                            disabled: this.disabled,
                            attributes: {placeholder: "Email"}
                        },
                        {
                            type: "checkbox",
                            label: "Estado",
                            model: "estado",
                            disabled: this.disabled
                        },
                        {
                            type: "select",
                            label: "Rol",
                            model: "rol",
                            required: true,
                            values: [],
                            disabled: this.disabled,
                            selectOptions: {noneSelectedText: "Selecciona un Rol"}
                        }
                    ]
                },
                message_error: false
            };
        },
        created() {
            this.resetForm();
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
                await this.loadRoles();
                this.message_error = false;
                this.validator = [];
                if (this.$route.query.id) {
                    await this.loadForm(this.$route.query.id);
                    this.changeDisabled(true);
                } else {
                    this.changeDisabled(false);
                }
            },
            async loadForm(id) {
                // Push the name to submitted names
                await axios
                    .get(this.path, {
                        params: {id: id}
                    })
                    .then(({data}) => {
                        if (data["status"] === 0) {
                            this.model.empleado = id;
                            if (data["data"]["id_usuario"]) {
                                axios
                                    .get(this.pathget, {
                                        params: {id: data["data"]["id_usuario"]}
                                    })
                                    .then(({data}) => {
                                        if (data["status"] === 0) {
                                            Object.entries(data["data"]).forEach(([key, value]) => {
                                                if (key === "id") this.model[key] = "";
                                                if (this.model[key] !== undefined)
                                                    this.model[key] = value;
                                            });
                                        } else {
                                            this.message_error = data["data"];
                                        }
                                    })
                                    .catch();
                            }
                            this.disabled = true;
                        } else {
                            this.message_error = data["message"];
                            this.validator = data["data"];
                            this.disabled = false;
                        }
                    })
                    .catch();
            },
            submitForm() {
                axios
                    .post(this.pathpost, this.model)
                    .then(({data}) => {
                        if (data["status"] === 0) {
                            this.$route.query["id"] = data["data"]["empleado"];
                            this.resetForm();
                        } else {
                            this.m_error = data["data"];
                        }
                    })
                    .catch();
            },
            async loadRoles() {
                this.roles = [];
                await axios
                    .get("api/roles")
                    .then(({data}) => {
                        if (data["status"] === 0) {
                            data["data"]["all"].forEach(value => {
                                this.roles.push({id: value["id"], name: value["name"]});
                            });
                            Object.entries(this.schema.fields).forEach(([key, value]) => {
                                if (value.model === "rol") {
                                    this.schema.fields[key].values = this.roles;
                                }
                            });
                        }
                    })
                    .catch();
            }
        }
    };
</script>
