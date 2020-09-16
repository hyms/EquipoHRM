<template>
  <div class="col">
    <div class="padded-lg">
      <!--START - Recent Ticket Comments-->
      <div class="element-wrapper">
        <h6 class="element-header">
          {{ pageTitle }}
        </h6>
        <div class="element-box-tp">
          <b-alert show dismissible variant="danger" v-if="message_error">
            {{ message_error }}
          </b-alert>
          <div class="table-responsive">
            <!--<b-table
              :items="tables"
              :fields="columns"
              responsive="sm"
              class="table table-padded"
              :busy="isBusy"
            >
              <template v-slot:table-busy>
                <div class="text-center my-2">
                  <b-spinner class="align-middle"></b-spinner>
                  <strong>Cargando...</strong>
                </div>
              </template>
            </b-table>-->
            <table class="table table-padded text-center">
              <thead>
              <tr>
                <th v-for="column in columns" :key="column">
                  {{ column }}
                </th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="row in tables" :key="row.id">
                <template v-for="(cel, index) in columns">
                  <td v-if="index === 0" :key="index">
                    {{ row[cel] }}
                  </td>
                  <td v-else :key="index">
                    <div class="form-check">
                      <input
                              type="checkbox"
                              :name="String(row['id']) + String(row[cel]['id'])"
                              class="form-check-input position-static"
                              @change="
                            checkPrivilege(
                              row['id'],
                              row[cel]['id'],
                              !row[cel]['value']
                            )
                          "
                              :checked="row[cel]['value']"
                      />
                    </div>
                  </td>
                </template>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from "axios";

  export default {
    name: "rules",
    data() {
      return {
        pageTitle: "Privilegios",
        formTitle: "",
        path: "/api/rules",
        isBusy: false,
        columns: [],
        tables: [],
        checks: [],
        message_error: false
      };
    },
    created() {
      this.getAllData();
    },
    methods: {
      async getAllData() {
        this.isBusy = true;
        await axios
                .get(this.path)
                .then(({data}) => {
                  if (data["status"] === 0) {
                    this.columns = data["data"]["columnas"];
                    this.tables = data["data"]["tabla"];
                  }
                })
                .catch(err => {
                  console.log(err);
                });
        this.isBusy = false;
      },
      async checkPrivilege(funcion, rol, check) {
        await axios
                .post(this.path, {funcion, rol, check})
                .then(({data}) => {
                  if (data["status"] !== 0) {
                    this.message_error = data["message"];
                  }
                })
                .catch(err => {
                  console.log(err);
                });
        this.getAllData();
      }
    }
  };
</script>
