<template>
    <div class="content-i">
        <div class="content-box">
            <!--START - Control panel above projects-->
            <div class="os-tabs-w mx-4">
                <div class="os-tabs-controls">
                    <ul class="nav nav-tabs upper">
                        <li class="nav-item" v-for="(link,key) in links" :key="key">
                            <router-link
                                    :to="{path:key,query:$route.query}"
                                    exact
                                    exact-active-class="active"
                                    class="nav-link"
                                    data-toggle="tab"
                                    aria-expanded="false"
                            >
                                {{link}}
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>
            <!--END - Control panel above projects-->
            <div class="row">
                <router-view/>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                links: {
                    detalle: 'Detalle',
                    carrera: 'Funcion Laboral',
                    cuenta: 'Creacion de Cuenta',
                    historial: 'Cargos Anteriores',
                }
            }
        }
    }
</script>
