<template>
  <div class="col-12">
    <div class="element-wrapper">
      <div class="element-box">
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
          <div class="row">
            <div class="col text-center">
              <b-button variant="primary" v-if="!editable" @click="editable=true">Editar</b-button>
              <b-button variant="danger" @click="resetModal()" v-if="editable">Cancelar</b-button>
              <b-button variant="primary" v-if="editable" @click="handleSubmit()">Guardar</b-button>
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
        path: "/api/carrera",
        pathData: "/api/personal/carrera",
        form: {
          cargo: {value: "", state: null, type: 'select', datos: 'cargos', label: "Cargo"},
          unidad_negocio: {value: "", state: null, type: 'select', datos: 'negocios', label: "Unidad de Negocio"},
          area_trabajo: {value: "", state: null, type: 'select', datos: 'areas', label: "Area de Trabajo"},
          regional: {value: "", state: null, type: 'select', datos: 'regionales', label: "Regional"},
          gerencia: {value: "", state: null, type: 'select', datos: 'gerencias', label: "Gerencia"},
          fecha_ingreso: {value: "", state: null, type: 'date', label: 'Fecha de Ingreso'},
          personal: {value: ""}
        },
        datos: [],
        id: null,
        editable: true,
        m_error: false
      };
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

      },
      loadData() {
        axios.get(this.pathData)
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
        this.loadData();
        this.id = this.$route.query.id;
        if (this.id) {
          this.editable = false;
          // Push the name to submitted names
          axios.get(this.path, {
            params: {id: this.id}
          })
                  .then(({data}) => {
                    if (data["status"] === 0) {
                      Object.entries(data["data"]).forEach(([key, value]) => {
                        if (this.form[key]) this.form[key].value = value;
                      });
                    } else {
                      this.m_error = data["data"];
                      this.editable = true;
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

        if (this.id) formData["personal"] = this.id;

        axios.post(this.path, formData)
                .then(({data}) => {
                  if (data["status"] === 0) {
                    this.$route.query['id'] = data['data']['personal'];
                    this.loadModal();
                  } else {
                    this.m_error = data["data"];
                  }
                })
                .catch();
      },
      estadoCivil() {
        return Helpers.estadoCivil();
      }

    },
    created() {
      this.loadModal();
    }
  };
</script>
