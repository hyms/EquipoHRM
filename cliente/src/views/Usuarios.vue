<template>
    <div class="content-i">
        <div class="content-box">
            <div class="row pt-4">
                <div class="col-sm-12">
                    <!--START - Recent Ticket Comments-->
                    <div class="element-wrapper">
                        <div class="element-actions">
                            <b-button variant="primary" v-b-modal.modalUsuario>Nuevo</b-button>
                            <Form/>
                        </div>
                        <h6 class="element-header">
                            Usuarios
                        </h6>
                        <div class="element-box-tp">
                            <div class="table-responsive">
                                <table class="table table-padded">
                                    <thead>
                                    <tr>
                                        <th>
                                            Alias
                                        </th>
                                        <th>
                                            Fecha Registro
                                        </th>
                                        <th>
                                            Ultimo acceso
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
                                    <tr colspan="5" v-if="tables.length===0">
                                        No existen registros
                                    </tr>
                                    <tr v-else v-for="(value) in tables" :key="value.id">
                                        <td>
                                            <span>{{value.alias}}</span>
                                        </td>
                                        <td>
                                            <span>{{value.created_at}}</span>
                                        </td>
                                        <td>
                                            <span>{{value.updated_at}}</span>
                                        </td>
                                        <td>
                                            <span>{{value.estado}}</span>
                                        </td>
                                        <td class="row-actions">
                                            <a href="#" v-on:click="edit(value.id)" v-b-modal.modalUsuario><i class="os-icon os-icon-ui-44"></i></a>
                                            <a class="danger" href="#"><i class="os-icon os-icon-ui-15"></i></a>
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
    import Form from '@/components/forms/usuarioForm'
    import axios from "axios";

    export default {
        data(){
            return {
                tables: [],
                item: [],
            }
        },
        components: {
            Form
        },
        created() {
            this.getAll();
        },
        updated() {
            //this.getAll();
        },
        methods:{
            async getAll() {
                 await axios.get('/api/usuarios/get')
                    .then(({data}) => {
                        //console.log(data);
                        if (data['status'] === 0) {
                            this.tables = data['data']['all'];
                        }
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            },
            edit(id) {
               const res = this.tables.find(elem=>elem.id==id);
               console.log(res);
            }

        }
    };
</script>