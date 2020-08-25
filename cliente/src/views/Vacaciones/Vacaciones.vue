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
                      <b-form-select
                              v-if="visibleSelect([1, 2],data.value )"
                              v-model="modelSelect"
                              :options="optionSelect"
                      ></b-form-select>
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
        pageTitle: "Vacaciones/Permisos",
        formTitle: "",
        path: "/api/vacaciones/",
        isBusy: false,
        columns: [
          "empleado",
          "numero_dias",
          "fecha_inicio",
          "fecha_fin",
          "estado",
          "Acciones"
        ],
        tables: [],
        validator: [],
        modelSelect: "",
        optionSelect: [],
        model: {
          nombre: "",
          ci: "",
          cargo: "",
          fecha_ingreso: "",
          disponible: "",
          ano_cumplido: "",
          fecha_inicio: "",
          fecha_fin: "",
          tipo: "",
          numero_dias: "",
          observaciones: "",
          estado: "",
          sabado: "",
          empleado_id: "",
          tipo_vacaciones_id: ""
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
                        this.message_error = "";
                        await axios
                                .get("api/vacaciones/empleado", {
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
                  model: "fecha_inicio",
                  attributes: {
                    placeholder: "Fecha desde"
                  },
                  styleClasses: "col-sm-6 col-lg-3"
                },
                {
                  type: "input",
                  inputType: "date",
                  label: "Fecha hasta",
                  model: "fecha_fin",
                  attributes: {
                    placeholder: "Fecha hasta"
                  },
                  styleClasses: "col-sm-6 col-lg-3"
                },
                {
                  type: "radios",
                  label: "Tiempo a solicitar",
                  model: "tipo_vacaciones_id",
                  listBox: true,
                  values: Helpers.tipoDiaVacacion(),
                  visible: false,
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
                  styleClasses: "col-sm-6 col-lg-3",
                  buttons: [
                    {
                      classes: "btn btn-primary",
                      label: "Cargar",
                      onclick: async function (model) {
                        await axios
                                .get("api/vacaciones/days", {
                                  params: {
                                    fecha_inicio: this.$options.filters.formatPostDateOnly(
                                            model["fecha_inicio"]
                                    ),
                                    fecha_fin: this.$options.filters.formatPostDateOnly(
                                            model["fecha_fin"]
                                    ),
                                    sabado: model.sabado
                                  }
                                })
                                .then(({data}) => {
                                  if (data["status"] === 0) {
                                    this.model.numero_dias = data["data"];
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
    computed: {
      numero_dias: function () {
        return this.model.numero_dias;
      },
      tipo_vacaciones_id: function () {
        return this.model.tipo_vacaciones_id;
      }
    },
    watch: {
      numero_dias: function () {
        Object.entries(this.schema.groups[1].fields).forEach(([key, value]) => {
          if (value.model === "tipo_vacaciones_id") {
            this.schema.groups[1].fields[key].visible =
                    this.model.numero_dias <= 1 && this.model.numero_dias >= 0;
          }
        });
      },
      tipo_vacaciones_id: function () {
        if (this.model.tipo_vacaciones_id === 1) {
          this.model.numero_dias = 1;
        } else if (this.model.tipo_vacaciones_id === 0) {
          this.model.numero_dias = 0.5;
        }
      }
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
          await axios
                  .get("api/vacaciones/empleado", {
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
      },
      handleOk(bvModalEvt) {
        // Prevent modal from closing
        bvModalEvt.preventDefault();
        this.submitForm();
      },
      submitForm() {
        let formdata = {
          empleado_id: "",
          numero_dias: "",
          fecha_inicio: "",
          fecha_fin: "",
          observaciones: "",
          tipo_vacaciones_id: ""
        };
        this.model["fecha_inicio"] = this.$options.filters.formatPostDateOnly(
                this.model["fecha_inicio"]
        );
        this.model["fecha_fin"] = this.$options.filters.formatPostDateOnly(
                this.model["fecha_fin"]
        );
        if (this.model["id"] !== undefined) {
          formdata["id"] = "";
        }
        if (formdata["tipo_vacaciones_id"] === "")
          delete formdata["tipo_vacaciones_id"];
        Object.entries(this.model).forEach(([key, value]) => {
          if (formdata[key] !== undefined) {
            formdata[key] = value;
          }
        });
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
      visibleSelect(values, id = null) {
        this.optionSelect = Helpers.stateVacaciones();
        if (id) {
          this.modelSelect = Helpers.stateVacaciones(id);
        }
        return Helpers.visible(values);
      }
    }
  };
</script>
<style>
  legend:before {
    top: 100% !important;
  }
</style>
