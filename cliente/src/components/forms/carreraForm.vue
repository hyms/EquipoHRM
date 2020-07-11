<template>
  <div>
    <b-modal
            :id="nameModal"
            :title="(idForm ? 'Modificar' : 'Nuevo') + ' Carrera'"
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
                    :options="estadoCivil()"
            ></b-form-select>
          </b-form-group>
        </template>
      </form>
    </b-modal>
  </div>
</template>

<script>
  import axios from "axios";
  import Helpers from "@/store/funcions";

  export default {
    data() {
      return {
        path: "/api/carrera",
        form: {
          cargo: {value: "", state: null, type: 'select'},
          unidad_negocio: {value: "", state: null, type: 'select'},
          area_trabajo: {value: "", state: null, type: 'select'},
          regional: {value: "", state: null, type: 'select'},
          gerencia: {value: "", state: null, type: 'select'},
          fecha_ingreso: {value: "", state: null},
        },
        datos: [],
        m_error: false
      };
    },
    props: {
      idForm: Number,
      nameModal: String,
      pathData: String
    },
    methods: {
      checkFormValidity() {
        const valid = this.$refs.form.checkValidity();
        //this.form.nombres.state = valid;
        this.m_error = false;
        return valid;
      },
      resetModal() {
        Object.keys(this.form).forEach(key => {
          this.form[key].value = "";
          this.form[key].state = null;
        });
        this.m_error = false;
        axios
                .get(this.pathData, {
                  params: {id: this.idForm}
                })
                .then(({data}) => {
                  if (data["status"] === 0) {
                    this.datos = data['data'];
                  } else {
                    this.m_error = data["data"];
                  }
                })
                .catch();
      },
      loadModal() {
        this.resetModal();
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
                .post(this.path, formData)
                .then(({data}) => {
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
      estadoCivil() {
        return Helpers.estadoCivil();
      }
    }
  };
</script>
