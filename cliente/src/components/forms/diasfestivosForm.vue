<template>
  <div>
    <b-modal
            :id="nameModal"
            :title="(idForm ? 'Modificar' : 'Nuevo') + ' Dia Festivo'"
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
          <b-form-group
                  v-else
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
            ></b-form-input>
          </b-form-group>
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
        path: "/api/diasfestivos",
        form: {
          nombre: {value: "", state: null, type: 'text', label: 'Nombre'},
          fecha: {value: "", state: null, type: 'date', label: 'Fecha'},
          estado: {value: false, state: null, type: 'check', label: 'Estado'}
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
      //this.form.nombre.state = valid;
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
      if (this.idForm) {
        // Push the name to submitted names
        axios
                .get(this.path, {
                  params: {id: this.idForm}
                })
          .then(({ data }) => {
            if (data["status"] === 0) {
              Object.entries(data["data"]).forEach(([key, value]) => {
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
