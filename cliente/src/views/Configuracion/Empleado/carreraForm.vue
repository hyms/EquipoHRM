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
                            <b-button variant="primary" v-if="!disabled" @click="submitFrom()"
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
  import Helpers from "@/store/funcions";

  export default {
      data() {
          return {
              path: "/api/personal/empresa",
              pathData: "/api/personal/carrera",
              validator: [],
              model: {
                  empleado: "",
                  cargo: "",
                  unidad_negocio: "",
                  area_trabajo: "",
                  regional: "",
                  gerencia: "",
                  tipo_empleado: ""
              },
              schema: {
                  fields: [
                      {
                          type: "select",
                          label: "Cargo",
                          model: "cargo",
                          disabled: this.disabled,
                          selectOptions: {noneSelectedText: "Selecciona un Cargo"},
                          values: []
                      },
                      {
                          type: "select",
                          label: "Unidad de Negocio",
                          model: "unidad_negocio",
                          disabled: this.disabled,
                          selectOptions: {
                              noneSelectedText: "Selecciona una Unidad de Negocio"
                          },
                          values: []
                      },
                      {
                          type: "select",
                          label: "Area de Trabajo",
                          model: "area_trabajo",
                          disabled: this.disabled,
                          selectOptions: {
                              noneSelectedText: "Selecciona un Area de Trabajo"
                          },
                          values: []
                      },
                      {
                          type: "select",
                          label: "Regional",
                          model: "regional",
                          disabled: this.disabled,
                          selectOptions: {noneSelectedText: "Selecciona una Regional"},
                          values: []
                      },
                      {
                          type: "select",
                          label: "Gerencia",
                          model: "gerencia",
                          disabled: this.disabled,
                          selectOptions: {noneSelectedText: "Selecciona una Gerencia"},
                          values: []
                      },
                      {
                          type: "select",
                          label: "Tipo de Empleado",
                          model: "tipo_empleado",
                          disabled: this.disabled,
                          selectOptions: {
                              noneSelectedText: "Selecciona el tipo de Empleado"
                          },
                          values: this.tipoEmpleado()
                      }
                  ]
              },
              datos: [],
              message_error: false,
              disabled: false,
              id: null
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
              await this.loadData();
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
                          Object.entries(data["data"]).forEach(([key, value]) => {
                              if (key === "id") this.model[key] = "";
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
              axios
                  .post(this.path, this.model)
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
          loadData() {
              axios
                  .get(this.pathData)
                  .then(({data}) => {
                      if (data["status"] === 0) {
                          Object.entries(this.schema.fields).forEach(([key, value]) => {
                              if (data["data"][value.model] !== undefined) {
                                  this.schema.fields[key].values = data["data"][value.model];
                              }
                          });
                          this.datos = data["data"];
                      } else {
                          this.message_error = data["data"];
                      }
                  })
                  .catch();
          },
          tipoEmpleado() {
              return Helpers.tipoEmpleado();
          }
      },
      async created() {
          await this.resetForm();
      }
  };
</script>
