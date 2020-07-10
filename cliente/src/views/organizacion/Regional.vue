<template>
  <div class="col">
    <div class="padded-lg">
      <!--START - Recent Ticket Comments-->
      <div class="element-wrapper">
        <div class="element-actions">
          <b-button variant="primary" v-b-modal="nameModal" @click="setIdForm()"
          >Nuevo
          </b-button
          >
          <Form :idForm="idForm" @finish="getAll()" :nameModal="nameModal"/>
        </div>
        <h6 class="element-header">
          {{ tituloPagina }}
        </h6>
        <div class="element-box-tp">
          <div class="table-responsive">
            <b-table
                    :items="tables"
                    :fields="columnas"
                    striped
                    responsive="sm"
                    class="table table-padded"
                    show-empty
                    empty-text="Sin datos"
            >
              <template v-slot:cell(created_at)="data">
                <span>{{ data.value | formatDate }}</span>
              </template>
              <template v-slot:cell(estado)="data">
                <span>{{ data.value | formatState }}</span>
              </template>
              <template v-slot:cell(Acciones)="row">
                <div class="row-actions">
                  <a @click="setIdForm(row.item.id)" v-b-modal="nameModal"
                  ><i class="os-icon os-icon-ui-44"></i
                  ></a>
                  <a class="text-danger" @click="del(row.item.id)"
                  ><i class="os-icon os-icon-ui-15"></i
                  ></a>
                </div>
              </template>
            </b-table>
          </div>
        </div>
      </div>
      <!--END - Recent Ticket Comments-->
    </div>
  </div>
</template>
<script>
  import axios from "axios";
  import Form from "@/components/forms/regionalForm";
  import "@/store/funcions";

  export default {
    data() {
      return {
        tituloPagina: "Regionales",
        path: "/api/regionales",
        nameModal: "modalRegional",
        columnas: [
          {
            key: "nombre",
            sortable: true
          },
          {
            key: "created_at",
            label: "Fecha Registro",
            sortable: true
          },
          {
            key: "estado",
            sortable: true
          },
          "Acciones"
        ],
        tables: [],
        idForm: null
      };
    },
    components: {
      Form
    },
    created() {
      this.getAll();
    },
    methods: {
      setIdForm(id = null) {
        this.idForm = id;
      },
      async getAll() {
        await axios
                .get(this.path)
                .then(({data}) => {
                  if (data["status"] === 0) {
                    this.tables = data["data"]["all"];
                  }
                })
                .catch(err => {
                  console.log(err);
                });
        return true;
      },

      async del(id) {
        if (await this.showMsgConfirm()) {
          await axios
                  .delete(this.path, {
                    params: {id: id}
                  })
                  .then(({data}) => {
                    if (data["status"] === 0) {
                      this.getAll();
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
                  title: "Eliminar",
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
