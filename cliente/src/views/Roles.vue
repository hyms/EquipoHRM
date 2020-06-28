<template>
    <div class="content-i">
        <div class="content-box">
            <div class="row pt-4">
                <div class="col">
                    <!--START - Recent Ticket Comments-->
                    <div class="element-wrapper">
                        <div class="element-actions">
                            <b-button variant="primary" v-b-modal.modalRol v-on:click="idForm=null">Nuevo</b-button>
                            <Form v-bind:idForm="idForm" v-on:finish="getAll()"/>
                        </div>
                        <h6 class="element-header">
                            Roles
                        </h6>
                        <div class="element-box-tp">
                            <div class="table-responsive">
                                <table class="table table-padded">
                                    <thead>
                                    <tr>
                                        <th>
                                            nombre
                                        </th>
                                        <th>
                                            Fecha Registro
                                        </th>
                                        <th>
                                            Estado
                                        </th>
                                        <th>
                                            Acciones
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-if="tables.length===0">
                                        <td class="text-center" colspan="4">No existen registros</td>
                                    </tr>
                                    <tr v-else v-for="(value) in tables" :key="value.id">
                                        <td>
                                            <span>{{value.nombre}}</span>
                                        </td>
                                        <td>
                                            <span>{{value.created_at | formatDate}}</span>
                                        </td>
                                        <td>
                                            <span>{{value.estado | formatState}}</span>
                                        </td>
                                        <td class="row-actions">
                                            <a href="#" v-on:click="idForm=value.id" v-b-modal.modalRol><i class="os-icon os-icon-ui-44"></i></a>
                                            <a class="danger" href="#" v-on:click="del(value.id)"><i class="os-icon os-icon-ui-15"></i></a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--END - Recent Ticket Comments-->
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import Form from '@/components/forms/rolesForm'
    import axios from "axios";
    import '@/store/funcions';

    export default {
        data(){
            return {
                tables: [],
                item: [],
                idForm: null,
            }
        },
        components: {
            Form
        },
        created() {
            this.getAll();
        },
        updated(){
        },
        methods:{
            async getAll() {
                 await axios.get('/api/roles/get')
                    .then(({data}) => {
                        if (data['status'] === 0) {
                            this.tables = data['data']['all'];
                        }
                    })
                    .catch((err) => {
                        console.log(err);
                    });
                 return true;
            },

            async del(id) {
                await axios.delete(
                    '/api/roles/delete',
                    {params:{id:id}
                    })
                    .then(({data}) => {
                        if (data['status'] === 0) {
                            this.getAll();
                        }
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            }

        }
    };
</script>