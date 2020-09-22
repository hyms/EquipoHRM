<template>
  <div class="content-i">
    <div class="content-box">
      <div class="row pt-4">
        <div class="col">
          <div class="padded-lg">
            <!--START - Recent Ticket Comments-->
            <div class="element-wrapper">
              <div class="element-actions">
                <b-button
                        variant="primary"
                        v-b-modal="'modal'"
                        @click="loadForm()"
                >Nuevo
                </b-button>
                <b-modal
                        id="modal"
                        :title="formTitle + ' Permiso'"
                        @hidden="resetForm"
                        @ok="submitForm"
                        okTitle="Guardar"
                        cancelTitle="Cancelar"
                        size="lg"
                >
                  <b-alert
                          show
                          dismissible
                          variant="danger"
                          v-if="message_error"
                  >
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
                <b-alert show dismissible variant="danger" v-if="message_error">
                  {{ message_error }}
                </b-alert>
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

                    <template v-slot:cell(fecha_inicio)="data">
                      <span>{{ data.value | formatDateOnly }}</span>
                    </template>
                    <template v-slot:cell(fecha_fin)="data">
                      <span>{{ data.value | formatDateOnly }}</span>
                    </template>
                    <template v-slot:cell(estado)="data">
                      <selectState
                              v-if="visibleSelect()"
                              :value="row.item.estado"
                              :id="row.item.id"
                              ref="childComponent"
                      ></selectState>
                      <span v-else>{{ data.value | formatStateLeave }}</span>
                    </template>
                    <template v-slot:cell(empleado)="row">
                      <span>{{
                        row.item.nombres + " " + row.item.apellidos
                      }}</span>
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
      </div>
    </div>
  </div>
</template>
<script>
  import axios from "axios";
  import "@/store/funcions";
  import Helpers from "../../store/funcions";

  export default {
    data() {
      return {
        pageTitle: "Permisos",
        formTitle: "",
        path: "/api/permisos/",
        isBusy: false,
        columns: [
          "empleado",
          "fecha",
          {
            label: "tiempo (hrs)",
            key: "tiempo"
          },
          "estado",
          "Acciones"
        ],
        tables: [],
        validator: [],
        tipo_permisos: [],
        model: {
          nombre: "",
          ci: "",
          cargo: "",
          fecha: "",
          tiempo: "",
          tipo: "",
          observaciones: "",
          estado: "",
          sabado: "",
          empleado_id: "",
          tipo_permisos_id: ""
        },
        schema: {},
        message_error: false
      };
    },
    created() {
      this.getAllData();
    },
    computed: {
      numero_dias: function () {
        return this.model.numero_dias;
      },
      tipo_permisos_id: function () {
        return this.model.tipo_permisos_id;
      }
    },
    watch: {
      numero_dias: function () {
        Object.entries(this.schema.groups[1].fields).forEach(([key, value]) => {
          if (value.model === "tipo_permisos_id") {
            this.schema.groups[1].fields[key].visible =
                    this.model.numero_dias <= 1 && this.model.numero_dias >= 0;
          }
        });
      },
      tipo_permisos_id: function () {
        if (this.model.tipo_permisos_id === 1) {
          this.model.numero_dias = 1;
        } else if (this.model.tipo_permisos_id === 0) {
          this.model.numero_dias = 0.5;
        }
      }
    },
    methods: {
      //asignar titulo
      async loadForm(data = null) {
        this.loadTipoPermiso();
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
          await axios
                  .get("api/permisos/empleado", {
                    params: {id: this.model.empleado_id}
                  })
                  .then(({data}) => {
                    if (data["status"] === 0) {
                      if (Object.keys(data["data"]).length > 0) {
                        Object.entries(data["data"]).forEach(([key, value]) => {
                          if (key === "fecha_ingreso") {
                            value = this.$options.filters.formatDateOnly(value);
                          }
                          if (this.model[key] !== undefined) this.model[key] = value;
                        });
                      } else {
                        this.message_error = "no se encontraron resultados";
                      }
                    }
                  })
                  .catch(err => {
                    console.log(err);
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
        this.message_error = false;
        this.validator = [];
        this.schema = {
          groups: [
            {
              legend: "Datos del Empleado",
              fields: [
                {
                  type: "input",
                  inputType: "Number",
                  label: "Documento de Identidad",
                  model: "ci",
                  required: true,
                  styleClasses: "col-md-10",
                  attributes: {placeholder: "Documento de Identidad"},
                  buttons: [
                    {
                      classes: "btn btn-primary",
                      label: "Buscar",
                      onclick: async function (model) {
                        this.message_error = "";
                        await axios
                                .get("api/permisos/empleado", {
                                  params: {ci: model.ci}
                                })
                                .then(({data}) => {
                                  if (data["status"] === 0) {
                                    if (Object.keys(data["data"]).length > 0) {
                                      Object.entries(data["data"]).forEach(
                                              ([key, value]) => {
                                                if (key === "fecha_ingreso") {
                                                  value = this.$options.filters.formatDateOnly(
                                                          value
                                                  );
                                                }
                                                if (this.model[key] !== undefined)
                                                  this.model[key] = value;
                                              }
                                      );
                                    } else {
                                      this.message_error =
                                              "no se encontraron resultados";
                                    }
                                  }
                                })
                                .catch(err => {
                                  console.log(err);
                                });
                      }
                    }
                  ]
                },
                {
                  type: "input",
                  inputType: "text",
                  label: "Nombre",
                  model: "nombre",
                  disabled: true,
                  attributes: {placeholder: "Nombre"},
                  styleClasses: "col-md-6"
                },
                {
                  type: "input",
                  inputType: "text",
                  label: "Cargo",
                  model: "cargo",
                  disabled: true,
                  attributes: {placeholder: "Cargo"},
                  styleClasses: "col-md-6"
                }
              ]
            },
            {
              legend: "Periodo de Permiso",
              fields: [
                {
                  type: "input",
                  inputType: "date",
                  label: "Fecha",
                  model: "fecha",
                  attributes: {
                    placeholder: "Fecha"
                  },
                  styleClasses: "col-sm-6 col-lg-3"
                },
                {
                  type: "input",
                  inputType: "numeric",
                  label: "Tiempo en hrs",
                  model: "tiempo",
                  attributes: {
                    placeholder: "Horas"
                  },
                  styleClasses: "col-sm-6 col-lg-3"
                },
                {
                  type: "select",
                  label: "Tiempo a solicitar",
                  model: "tipo_permisos_id",
                  values: this.tipo_permisos,
                  styleClasses: "col-sm-6 col-lg-3",
                  selectOptions: {noneSelectedText: "Selecciona un Tipo"}
                },
                {
                  type: "textArea",
                  label: "Observaciones",
                  model: "observaciones",
                  placeholder: "Observaciones"
                }
              ]
            }
          ]
        };
      },
      handleOk(bvModalEvt) {
        // Prevent modal from closing
        bvModalEvt.preventDefault();
        this.submitForm();
      },
      submitForm() {
        let formdata = {
          empleado_id: "",
          tiempo: "",
          fecha: "",
          observaciones: "",
          tipo_permisos_id: ""
        };
        this.model["fecha"] = this.$options.filters.formatPostDateOnly(
                this.model["fecha"]
        );
        if (this.model["id"] !== undefined) {
          formdata["id"] = "";
        }
        Object.entries(this.model).forEach(([key, value]) => {
          if (formdata[key] !== undefined) {
            formdata[key] = value;
          }
        });
        if (formdata["tipo_permisos_id"] === "")
          delete formdata["tipo_permisos_id"];
        axios
                .post(this.path, formdata)
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
        this.message_error = "";
        if (await this.showMsgConfirm()) {
          await axios
                  .delete(this.path, {
                    params: {id: id}
                  })
                  .then(({data}) => {
                    if (data["status"] === 0) {
                      this.getAllData();
                    } else {
                      this.message_error = data["mensaje"];
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
      },
      visibleSelect() {
        return Helpers.visible("vacaciones", 6);
      },
      async loadTipoPermiso() {
        this.tipo_permisos = [];
        await axios
                .get("/api/permisos/tipo")
                .then(({data}) => {
                  if (data["status"] === 0) {
                    data["data"]["all"].forEach(value => {
                      this.tipo_permisos.push({id: value.id, name: value.tipo});
                    });
                  }
                })
                .catch(err => {
                  console.log(err);
                });
      }
    }
  };
</script>
<style>
  legend:before {
    top: 100% !important;
  }
</style>
