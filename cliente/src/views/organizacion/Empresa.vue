<template>
  <div class="col">
    <div class="padded-lg">
      <!--START - Recent Ticket Comments-->
      <div class="element-wrapper">
        <div class="element-actions">
          <b-button variant="primary" @click="handleSubmit()">{{
            textoBoton[editable ? 1 : 0]
            }}
          </b-button>
        </div>
        <h6 class="element-header">
          {{ tituloPagina }}
        </h6>
        <div class="element-box">
          <b-alert
                  show
                  dismissible
                  variant="danger"
                  v-for="(value, key) in m_error"
                  :key="key"
          >
            {{ key }}:{{ value }}
          </b-alert>
          <form ref="form" @submit.stop.prevent="handleSubmit">
              <template v-for="(input, key) in form">
                  <b-form-group
                          :state="input.state"
                          :label="input.label"
                          :label-for="key"
                          :key="key"
                  >
                      <b-form-input
                              v-if=" ['text','number','email','password','url','tel','date'].includes(input.type)"
                              :id="key"
                              v-model="form[key].value"
                              :state="input.state"
                              :type="input.type"
                              :disabled="!editable"
                      ></b-form-input>

                      <b-form-textarea
                              :id="key"
                              v-model="form[key].value"
                              :state="input.state"
                              v-if="input.type === 'textarea'"
                              :disabled="!editable"
                      ></b-form-textarea>
                      <b-form-select
                              v-if="input.type === 'select'"
                              :id="key"
                              v-model="form[key].value"
                              :state="input.state"
                              :options="datos[input.datos]"
                              value-field="id"
                              text-field="nombre"
                              :disabled="!editable"
                      ></b-form-select>
                  </b-form-group>
              </template>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
    import "@/store/funcions";
    import axios from "axios";

    export default {
        data() {
            return {
                path: "/api/empresa",
                tituloPagina: "Empresa",
                idForm: 1,
                editable: false,
                textoBoton: ["Editar", "Guardar"],
                form: {
                    nombre: {value: "", state: null, type: 'text', label: 'Nombre'},
                    direccion: {value: "", state: null, type: 'textarea', label: 'Direccion'},
                    nit: {value: "", state: null, type: 'text', label: 'Nit'},
                    telefono: {value: "", state: null, type: 'tel', label: 'Telefono'},
                    celular: {value: "", state: null, type: 'tel', label: 'Celular'},
                    fax: {value: "", state: null, type: 'tel', label: 'Fax'},
                    ciudad: {value: "", state: null, type: 'text', label: 'Ciudad'},
                    departamento: {value: "", state: null, type: 'text', label: 'Departamento'},
                    gerente: {value: "", state: null, type: 'text', label: 'Gerente'},
                    email: {value: "", state: null, type: 'email', label: 'Email'},
                    web: {value: "", state: null, type: 'url', label: 'Web'},
                    fecha_nacimiento: {value: "", state: null, type: 'date', label: 'Fecha de Nacimiento'},
                    estado: {value: 1}
                },
        m_error: false
      };
    },
    methods: {
      checkFormValidity() {
        const valid = this.$refs.form.checkValidity();
        this.form.nombre.state = valid;
        this.m_error = false;
        return valid;
      },
      loadModal() {
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
                        this.idForm = null;
                    }
                  })
                  .catch();
        }
      },
      handleSubmit() {
        if (this.editable) {
          // Exit when the form isn't valid
          if (!this.checkFormValidity()) {
            return;
          }
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
                      this.editable = false;
                      this.loadModal();
                    } else {
                      this.m_error = data["data"];
                    }
                  })
                  .catch();
        } else {
          this.editable = true;
        }
      }
    },
    created() {
      this.loadModal();
    }
  };
</script>
