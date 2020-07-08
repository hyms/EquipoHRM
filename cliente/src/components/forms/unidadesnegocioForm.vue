<template>
  <div>
    <b-modal
      :id="nameModal"
      :title="(idForm ? 'Modificar ' : 'Nueva') + ' Unidad de Negocio'"
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
        <b-form-group
          :state="form.direccion.state"
          label="Direccion"
          label-for="direccion-input"
          invalid-feedback="Direccion es requerido"
        >
          <b-form-input
            id="direccion-input"
            v-model="form.direccion.value"
            :state="form.direccion.state"
            required
          ></b-form-input>
        </b-form-group>
        <b-form-group
          :state="form.telefono.state"
          label="telefono"
          label-for="telefono-input"
          invalid-feedback="telefono es requerido"
        >
          <b-form-input
            id="telefono-input"
            v-model="form.telefono.value"
            :state="form.telefono.state"
            required
          ></b-form-input>
        </b-form-group>
        <b-form-group
          :state="form.celular.state"
          label="celular"
          label-for="celular-input"
          invalid-feedback="celular es requerido"
        >
          <b-form-input
            id="celular-input"
            v-model="form.celular.value"
            :state="form.celular.state"
            required
          ></b-form-input>
        </b-form-group>
        <b-form-group
          :state="form.fax.state"
          label="fax"
          label-for="fax-input"
          invalid-feedback="fax es requerido"
        >
          <b-form-input
            id="fax-input"
            v-model="form.fax.value"
            :state="form.fax.state"
            required
          ></b-form-input>
        </b-form-group>
        <b-form-group
          :state="form.ciudad.state"
          label="ciudad"
          label-for="ciudad-input"
          invalid-feedback="ciudad es requerido"
        >
          <b-form-input
            id="ciudad-input"
            v-model="form.ciudad.value"
            :state="form.ciudad.state"
            required
          ></b-form-input>
        </b-form-group>
        <b-form-group
          :state="form.departamento.state"
          label="departamento"
          label-for="departamento-input"
          invalid-feedback="departamento es requerido"
        >
          <b-form-input
            id="departamento-input"
            v-model="form.departamento.value"
            :state="form.departamento.state"
            required
          ></b-form-input>
        </b-form-group>
        <b-form-group
          :state="form.encargado.state"
          label="encargado"
          label-for="encargado-input"
          invalid-feedback="encargado es requerido"
        >
          <b-form-input
            id="encargado-input"
            v-model="form.encargado.value"
            :state="form.encargado.state"
            required
          ></b-form-input>
        </b-form-group>
        <b-form-group
          :state="form.email.state"
          label="email"
          label-for="email-input"
          invalid-feedback="email es requerido"
        >
          <b-form-input
            id="email-input"
            v-model="form.email.value"
            :state="form.email.state"
            required
          ></b-form-input>
        </b-form-group>
        <b-form-group
          :state="form.web.state"
          label="web"
          label-for="web-input"
          invalid-feedback="web es requerido"
        >
          <b-form-input
            id="web-input"
            v-model="form.web.value"
            :state="form.web.state"
            required
          ></b-form-input>
        </b-form-group>
        <b-form-group
          :state="form.fecha_nacimiento.state"
          label="Fecha de Nacimiento"
          label-for="fecha_nacimiento-input"
          invalid-feedback="fecha_nacimiento es requerido"
        >
          <b-form-input
            id="fecha_nacimiento-input"
            v-model="form.fecha_nacimiento.value"
            :state="form.fecha_nacimiento.state"
            required
          ></b-form-input>
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
      path: "/api/unidadesnegocio",
      form: {
        nombre: { value: "", state: null },
        direccion: { value: "", state: null },
        telefono: { value: "", state: null },
        celular: { value: "", state: null },
        fax: { value: "", state: null },
        ciudad: { value: "", state: null },
        departamento: { value: "", state: null },
        encargado: { value: "", state: null },
        email: { value: "", state: null },
        web: { value: "", state: null },
        fecha_nacimiento: { value: "", state: null },
        estado: { value: false, state: null }
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
      this.nombre = { value: "", state: null };
      this.direccion = { value: "", state: null };
      this.telefono = { value: "", state: null };
      this.celular = { value: "", state: null };
      this.fax = { value: "", state: null };
      this.ciudad = { value: "", state: null };
      this.departamento = { value: "", state: null };
      this.encargado = { value: "", state: null };
      this.email = { value: "", state: null };
      this.web = { value: "", state: null };
      this.fecha_nacimiento = { value: "", state: null };
      this.estado = { value: false, state: null };
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
              data["data"][0].forEach((value, key) => {
                this[key].value = value;
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
