<template>
  <div>
    <b-modal
      :id="nameModal"
      :title="(idForm ? 'Modificar' : 'Nuevo') + ' Dias de Trabajo'"
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
          :state="form.nombre.state"
          label="Nombre"
          label-for="name-input"
          invalid-feedback="Nombre es requerido"
        >
          <b-form-input
            id="name-input"
            v-model="form.nombre.value"
            :state="form.nombre.state"
            required
          ></b-form-input>
        </b-form-group>
        <label for="fechaForm">Date picker with placeholder</label>
        <b-form-datepicker
          id="fechaForm"
          v-model="form.fecha.value"
          locale="es"
          :date-format-options="{
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
          }"
        ></b-form-datepicker>
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
      path: "/api/diasfestivos",
      form: {
        nombre: { value: "", state: null },
        estado: { value: false, state: null },
        fecha: { value: "", state: null }
      },
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
      this.form.nombre.state = valid;
      this.m_error = false;
      return valid;
    },
    resetModal() {
      this.form.nombre = { value: "", state: null };
      this.form.estado = { value: false, state: null };
      this.form.fecha = { value: "", state: null };
      this.m_error = false;
    },
    loadModal() {
      this.resetModal();
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
