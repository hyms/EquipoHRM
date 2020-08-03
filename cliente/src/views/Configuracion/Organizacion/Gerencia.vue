<template>
    <div class="col">
        <div class="padded-lg">
            <!--START - Recent Ticket Comments-->
            <div class="element-wrapper">
                <div class="element-actions">
                    <b-button variant="primary" v-b-modal="'modal'" @click="loadForm()"
                    >Nuevo
                    </b-button
                    >
                    <b-modal
                            id="modal"
                            :title="formTitle + ' Gerencia'"
                            @hidden="resetForm"
                            @ok="handleOk"
                            okTitle="Guardar"
                            cancelTitle="Cancelar"
                    >
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
                        </form>
                    </b-modal>
                </div>
                <h6 class="element-header">
                    {{ pageTitle }}
                </h6>
                <div class="element-box-tp">
                    <div class="table-responsive">
                        <b-table
                                :items="tables"
                                :fields="columns"
                                striped
                                responsive="sm"
                                class="table table-padded"
                                show-empty
                                empty-text="Sin datos"
                                :busy="isBusy"
                        >
                            <template v-slot:table-busy>
                                <div class="text-center my-2">
                                    <b-spinner class="align-middle"></b-spinner>
                                    <strong>Cargando...</strong>
                                </div>
                            </template>

                            <template v-slot:cell(estado)="data">
                                <span>{{ data.value | formatState }}</span>
                            </template>
                            <template v-slot:cell(Acciones)="row">
                                <div class="row-actions">
                                    <a @click="loadForm(row.item)" v-b-modal="'modal'"
                                    ><i class="os-icon os-icon-ui-44"></i
                                    ></a>
                                    <a class="text-danger" @click="remove(row.item.id)"
                                    ><i class="os-icon os-icon-ui-15"></i
                                    ></a>
                                </div>
                            </template>
                        </b-table>
                    </div>
                </div>
            </div>
            <!--END - Recent Ticket Comments-->
        </div>
    </div>
</template>
<script>
    import axios from "axios";
    import "@/store/funcions";

    export default {
        data() {
            return {
                pageTitle: "Gerencias",
                formTitle: "",
                path: "/api/gerencia",
                isBusy: false,
                columns: [
                    "nombre", "detalle", "Acciones"],
                tables: [],
                validator: [],
                model: {
                    nombre: "",
                    detalle: ""
                },
                schema: {
                    fields: []
                },
                message_error: false
            };
        },
        created() {
            this.getAllData();
        },
        methods: {
            //asignar titulo
            async loadForm(data = null) {
                this.resetForm();
                if (!data) {
                    this.formTitle = "Nuevo";
                    delete this.model["id"];
                } else {
                    this.formTitle = "Modificar";
                    this.model["id"] = "";
                    Object.entries(data).forEach(([key, value]) => {
                        if (this.model[key] !== undefined) {
                            this.model[key] = value;
                        }
                    });
                }
            },
            //obtener todos
            async getAllData() {
                this.isBusy = true;
                await axios
                    .get(this.path)
                    .then(({data}) => {
                        if (data["status"] === 0) {
                            this.tables = data["data"]["all"];
                        }
                    })
                    .catch(err => {
                        console.log(err);
                    });
                this.isBusy = false;
            },
            async resetForm() {
                Object.keys(this.model).forEach(key => {
                    this.model[key] = "";
                });
                this.schema["fields"] = [
                    {
                        type: "input",
                        inputType: "text",
                        label: "Nombre",
                        model: "nombre",
                        required: true,
                        attributes: {placeholder: "Nombre"}
                    },
                    {
                        type: "textArea",
                        label: "Descripcion",
                        model: "detalle",
                        attributes: {placeholder: "Descripcion"}
                    }
                ];
                this.message_error = false;
                this.validator = [];
            },
            handleOk(bvModalEvt) {
                // Prevent modal from closing
                bvModalEvt.preventDefault();
                this.submitForm();
            },
            submitForm() {
                axios
                    .post(this.path, this.model)
                    .then(({data}) => {
                        if (data["status"] === 0) {
                            // Hide the modal manually
                            this.$bvModal.hide("modal");
                            this.getAllData();
                        } else {
                            this.message_error = data["message"];
                            this.validator = data["data"];
                        }
                    })
                    .catch();
            },

            //eliminar
            async remove(id) {
                if (await this.showMsgConfirm()) {
                    await axios
                        .delete(this.path, {
                            params: {id: id}
                        })
                        .then(({data}) => {
                            if (data["status"] === 0) {
                                this.getAll();
                            }
                        })
                        .catch(err => {
                            console.log(err);
                        });
                }
            },
            showMsgConfirm() {
                return this.$bvModal
                    .msgBoxConfirm("Esta seguro?.", {
                        title: "Eliminar",
                        size: "sm",
                        //okVariant: 'success',
                        okTitle: "SI",
                        cancelVariant: "danger",
                        cancelTitle: "NO",
                        footerClass: "p-2",
                        hideHeaderClose: false
                    })
                    .then(value => {
                        return value;
                    })
                    .catch(() => {
                    });
            }
        }
    };
</script>
