<template>
  <div>
    <b-modal
      :id="nameModal"
      :title="(idForm ? 'Modificar' : 'Nuevo') + ' Usuario'"
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
                              :options="roles"
                              value-field="id"
                              text-field="nombre"
                      ></b-form-select>
                  </b-form-group>
              </template>
          </template>
      </form>
    </b-modal>
  </div>
</template>

<script>
    import axios from "axios";

    export default {
        data() {
            return {
                path: "/api/usuarios",
                form: {
                    name: {value: "", state: null, type: "text", label: "Nombre"},
                    password: {value: "", state: null, type: "password", label: "Contraseña"},
                    alias: {value: "", state: null, type: "text", label: "Alias"},
                    detail: {value: "", state: null, type: "textarea", label: "Detalle"},
                    rol: {value: "", state: null, type: "select", label: "Tipo Rol"},
                    estado: {value: false, state: null, type: "check", label: "Estado"},
                },
      roles: {},
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
      //this.form.name.state = valid;
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
      this.loadRoles();
      if (this.idForm) {
        // Push the name to submitted names
        axios
                .get(this.path, {
                  params: {id: this.idForm}
                })
          .then(({ data }) => {
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
    async loadRoles() {
      await axios
          .get("api/roles")
        .then(({ data }) => {
          if (data["status"] === 0) {
            this.roles = data["data"]["all"];
          } else {
            this.m_error = data["data"];
          }
        })
        .catch();
    },
    handleOk(bvModalEvt) {
      // Prevent modal from closing
      bvModalEvt.preventDefault();
      // Trigger submit handler
      this.handleSubmit();
    },
    handleSubmit() {
      // Exit when the form isn't valid
        /*if (!this.checkFormValidity()) {
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
        .then(({ data }) => {
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
    close() {}
  }
};
</script>
