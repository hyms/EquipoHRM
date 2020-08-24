<template>
  <div class="content-i">
    <div class="content-box">
      <div class="row pt-4">
        <div class="col">
          <div class="padded-lg">
            <!--START - Recent Ticket Comments-->
            <div class="element-wrapper">
              <h6 class="element-header">
                {{ pageTitle }}
              </h6>
              <div class="element-box-tp">
                <b-alert
                        show
                        dismissible
                        variant="danger"
                        v-if="message_error"
                >
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
                      <span>{{ data.value | formatStateLeave }}</span>
                    </template>
                    <template v-slot:cell(empleado)="row">
                      <span>{{
                        row.item.nombres + " " + row.item.apellidos
                      }}</span>
                    </template>
                    <template v-slot:cell(Acciones)="row">
                      <div class="row-actions">
                        <b-button class="btn-sm btn-success" @click="aprobar(row.item.id)">Aprobar</b-button>
                        <b-button class="btn-sm btn-danger" @click="declinar(row.item.id)">Declinar</b-button>
                        <b-button class="btn-sm btn-info" @click="registro(row.item.id)">Registro</b-button>
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

  export default {
    data() {
      return {
        pageTitle: "Estado de Vacaciones/Permisos",
        formTitle: "",
        path: "/api/vacaciones/",
        isBusy: false,
        columns: [
          "empleado",
          {
            key: "numero_dias",
            label: "Dias"
          },
          "fecha_inicio",
          "estado",
          {
            key: "total_disponible",
            label: "Disponible"
          },
          //fecha registro
          "Acciones"
        ],
        tables: [],
        message_error: false
      };
    },
    created() {
      this.getAllData();
    },
    methods: {
      //obtener todos
      async getAllData() {
        this.isBusy = true;
        await axios
                .get(this.path + "estado")
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
      async aprobar(id) {
        this.message_error = "";
        if (await this.showMsgConfirm()) {
          await axios
                  .post(this.path + "aprobar", {id: id})
                  .then(({data}) => {
                    if (data["status"] === 0) {
                      this.getAllData();
                    } else {
                      this.message_error = data['message'];
                    }
                  })
                  .catch(err => {
                    console.log(err);
                  });
        }
      },
      async declinar(id) {
        this.message_error = "";
        if (await this.showMsgConfirm()) {
          await axios
                  .post(this.path + "declinar", {id: id})
                  .then(({data}) => {
                    if (data["status"] === 0) {
                      this.getAllData();
                    } else {
                      this.message_error = data['message'];
                    }
                  })
                  .catch(err => {
                    console.log(err);
                  });
        }
      },
      async registro(id) {
        this.message_error = "";
        if (await this.showMsgConfirm()) {
          await axios
                  .post(this.path + "registro", {id: id})
                  .then(({data}) => {
                    if (data["status"] === 0) {
                      this.getAllData();
                    } else {
                      this.message_error = data['message'];
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
                  title: "Informacion",
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
      }
    }
  };
</script>
<style>
  legend:before {
    top: 100% !important;
  }
</style>
