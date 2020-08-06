<template>
    <div class="content-i">
        <div class="content-box">
            <div class="row pt-4">
                <div class="col">
                    <div class="padded-lg">
                        <!--START - Recent Ticket Comments-->
                        <div class="element-wrapper">
                            <div class="element-actions">
                                <router-link :to="{ name: 'detalle' }" class="btn btn-primary">
                                    Nuevo
                                </router-link>
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
                                            :busy="isBusy"
                                    >
                                        <template v-slot:table-busy>
                                            <div class="text-center my-2">
                                                <b-spinner class="align-middle"></b-spinner>
                                                <strong>Cargando...</strong>
                                            </div>
                                        </template>
                                        <template v-slot:cell(created_at)="data">
                                            <span>{{ data.value | formatDate }}</span>
                                        </template>
                                        <template v-slot:cell(estado)="data">
                                            <span>{{ data.value | formatState }}</span>
                                        </template>
                                        <template v-slot:cell(Acciones)="row">
                                            <div class="row-actions">
                                                <router-link
                                                        :to="{ name: 'detalle', query: { id: row.item.id } }"
                                                ><i class="os-icon os-icon-ui-44"></i
                                                ></router-link>
                                                <a
                                                        href="#"
                                                        class="text-danger"
                                                        @click="del(row.item.id)"
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
                tituloPagina: "Personal",
                path: "/api/personal",
                isBusy: false,
                columnas: [
                    {
                        key: "nombres",
                        sortable: true
                    },
                    {
                        key: "apellidos",
                        sortable: true
                    },
                    {
                        key: "ci",
                        sortable: true
                    },
                    "telefono_propio",
                    "telefono_referencia",
                    "Acciones"
                ],
                tables: []
            };
        },
        created() {
            this.getAll();
        },
        methods: {
            async getAll() {
                this.isBusy = true;
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
                this.isBusy = false;
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
