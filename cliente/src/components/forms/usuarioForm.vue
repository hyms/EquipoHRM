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
        <b-form-group
          :state="form.name.state"
          label="Nombre"
          label-for="name-input"
          invalid-feedback="Nombre es requerido"
        >
          <b-form-input
            id="name-input"
            v-model="form.name.value"
            :state="form.name.state"
            required
          ></b-form-input>
        </b-form-group>
        <b-form-group
          :state="form.password.state"
          label="Contraseña"
          label-for="pass-input"
        >
          <b-form-input
            id="pass-input"
            v-model="form.password.value"
            :state="form.password.state"
            type="password"
          ></b-form-input>
        </b-form-group>
        <b-form-group
          :state="form.alias.state"
          label="Alias"
          label-for="alias-input"
        >
          <b-form-input
            id="alias-input"
            v-model="form.alias.value"
            :state="form.alias.state"
          ></b-form-input>
        </b-form-group>
        <b-form-group
          :state="form.detail.state"
          label="Detalle"
          label-for="detail-input"
        >
          <b-form-input
            id="detail-input"
            v-model="form.detail.value"
            :state="form.detail.state"
          ></b-form-input>
        </b-form-group>
        <b-form-group
          :state="form.rol.state"
          label="Tipo de rol"
          label-for="rol-input"
        >
          <b-form-select
            id="rol-input"
            v-model="form.rol.value"
            :options="roles"
            value-field="id"
            text-field="nombre"
          ></b-form-select>
        </b-form-group>
        <div class="form-check">
          <input
            class="form-check-input"
            type="checkbox"
            v-model="form.estado.value"
            id="defaultCheck1"
          />
          <label class="form-check-label" for="defaultCheck1">
            Estado
          </label>
        </div>
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
        name: { value: "", state: null },
        password: { value: "", state: null },
        alias: { value: "", state: null },
        detail: { value: "", state: null },
        estado: { value: false, state: null },
        rol: { value: "", state: null }
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
      this.form.name.state = valid;
      this.m_error = false;
      return valid;
    },
    resetModal() {
      this.form.name = { value: "", state: null };
      this.form.password = { value: "", state: null };
      this.form.detail = { value: "", state: null };
      this.form.alias = { value: "", state: null };
      this.form.rol = { value: "", state: null };
      this.form.estado = { value: false, state: null };
      this.m_error = false;
    },
    loadModal() {
      this.resetModal();
      this.loadRoles();
      if (this.idForm) {
        // Push the name to submitted names
        axios
          .get(this.path + "/get", {
            params: { id: this.idForm }
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
        .get("api/roles/get")
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
        .post(this.path + "/post", formData)
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
