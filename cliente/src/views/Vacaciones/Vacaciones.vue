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
                        :title="formTitle + ' Vacacion/Permiso'"
                        @hidden="resetForm"
                        @ok="handleOk"
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
                      <span>{{ data.value | formatStateLeave }}</span>
                    </template>
                    <template v-slot:cell(empleado)="row">
                      <span>{{row.item.nombres + " " + row.item.apellidos }}</span>
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
  import moment from "moment";

  export default {
    data() {
      return {
        pageTitle: "Vacaciones/Permisos",
        formTitle: "",
        path: "/api/vacaciones/",
        isBusy: false,
        columns: [
          "empleado",
          "tipo",
          "numero_dias",
          "fecha_inicio",
          "fecha_fin",
          "estado",
          "Acciones"
        ],
        tables: [],
        validator: [],
        model: {
          nombre: "",
          ci: "",
          unidad: "",
          fecha_ingreso: "",
          disponible: "",
          ano_cumplido: "",
          fecha_desde: "",
          fecha_hasta: "",
          tipo: "",
          numero_dias: "",
          observaciones: "",
          estado: ""
        },
        schema: {
          groups: [
            {
              legend: "Datos del Empleado",
              fields: [
                {
                  type: "input",
                  inputType: "Number",
                  label: "DNI",
                  model: "ci",
                  required: true,
                  styleClasses: "col-md-10",
                  attributes: {placeholder: "Carnet de Identidad"},
                  buttons: [
                    {
                      classes: "btn btn-primary",
                      label: "Buscar",
                      onclick: async function (model) {
                        await axios
                                .get("api/vacaciones/empleado", {
                                  params: {ci: model.ci}
                                })
                                .then(({data}) => {
                                  if (data["status"] === 0) {
                                    Object.entries(data["data"]).forEach(
                                            ([key, value]) => {
                                              this.model["empleado"] = "";
                                              if (key === "fecha_ingreso") {
                                                value = moment(value).format("DD/MM/YYYY");
                                              }
                                              if (this.model[key] !== undefined)
                                                this.model[key] = value;
                                            }
                                    );
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
                  label: "Unidad de Negocio",
                  model: "unidad",
                  disabled: true,
                  attributes: {placeholder: "Unidad de Negicio"},
                  styleClasses: "col-md-6"
                },
                {
                  type: "input",
                  inputType: "text",
                  label: "Fecha de Ingreso",
                  model: "fecha_ingreso",
                  disabled: true,
                  attributes: {
                    placeholder: "Fecha de Ingreso"
                  },
                  styleClasses: "col-sm-4"
                },
                {
                  type: "input",
                  inputType: "text",
                  label: "Disponible",
                  model: "disponible",
                  disabled: true,
                  attributes: {
                    placeholder: "Disponible"
                  },
                  styleClasses: "col-sm-4"
                },
                {
                  type: "input",
                  inputType: "text",
                  label: "Año Cumplido",
                  model: "ano_cumplido",
                  disabled: true,
                  attributes: {
                    placeholder: "Año Cumplido"
                  },
                  styleClasses: "col-sm-4"
                }
              ]
            },
            {
              legend: "Periodo de disfrute de vacaciones",
              fields: [
                {
                  type: "input",
                  inputType: "date",
                  label: "Fecha desde",
                  model: "fecha_desde",
                  attributes: {
                    placeholder: "Fecha desde"
                  },
                  styleClasses: "col-sm-6 col-lg-3"
                },
                {
                  type: "input",
                  inputType: "date",
                  label: "Fecha hasta",
                  model: "fecha_hasta",
                  attributes: {
                    placeholder: "Fecha hasta"
                  },
                  styleClasses: "col-sm-6 col-lg-3"
                },
                {
                  type: "input",
                  inputType: "text",
                  label: "Tipo Salida",
                  model: "tipo",
                  disabled: true,
                  attributes: {
                    placeholder: "Tipo Salida"
                  },
                  styleClasses: "col-sm-6 col-lg-3"
                },
                {
                  type: "input",
                  inputType: "text",
                  label: "Numero de Dias",
                  model: "numero_dias",
                  disabled: true,
                  attributes: {
                    placeholder: "Numero de Dias"
                  },
                  styleClasses: "col-sm-6 col-lg-3"
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
      loadPersonal(id) {
        axios
                .get("api/vacaciones/empleado", {params: {ci: id}})
                .then(({data}) => {
                  if (data["status"] === 0) {
                    Object.entries(data["data"]).forEach(([key, value]) => {
                      this.model["empleado"] = "";
                      if (this.model[key] !== undefined) this.model[key] = value;
                    });
                  }
                })
                .catch(err => {
                  console.log(err);
                });
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
                      this.getAllData();
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
<style>
  legend:before {
    top: 100% !important;
  }
</style>
