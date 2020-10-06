<template>
    <div class="table-responsive">
        <b-table
                :items="table"
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

            <template v-slot:cell(estado)="data">
                <span>{{ data.value | formatState }}</span>
            </template>
            <template v-slot:cell(Acciones)="row">
                <div class="row-actions">
                    <a @click="loadForm(row.item)"
                    ><i class="os-icon os-icon-ui-44"></i
                    ></a>
                    <a class="text-danger" @click="remove(row.item.id)"
                    ><i class="os-icon os-icon-ui-15"></i
                    ></a>
                </div>
            </template>
        </b-table>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        data() {
            return {
                isBusy: false,
                table: {}
            };
        },
        created() {
            this.getAllData();
        },
        props: {
            columns: {},
            path: null
        },
        methods: {
            //obtener todos
            async getAllData() {
                if (this.path !== null) {
                    this.isBusy = true;
                    await axios
                        .get(this.path)
                        .then(({data}) => {
                            if (data["status"] === 0) {
                                this.table = data["data"]["all"];
                            }
                        })
                        .catch(err => {
                            console.log(err);
                        });
                    this.isBusy = false;
                }
            },
            //eliminar
            async remove(id) {
                if (this.path !== null) {
                    if (await this.showMsgConfirm()) {
                        await axios
                            .delete(this.path, {
                                params: {id: id}
                            })
                            .then(({data}) => {
                                if (data["status"] === 0) {
                                    this.getAllData();
                                }
                            })
                            .catch(err => {
                                console.log(err);
                            });
                    }
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
