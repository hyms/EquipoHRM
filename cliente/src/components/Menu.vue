<template>
    <ul class="main-menu">
        <li class="sub-header">
            <span>Personal</span>
        </li>
        <li class="">
            <router-link to="/" >
                <div class="icon-w">
                    <div class="os-icon os-icon-layout"></div>
                </div>
                <span>Vacaciones</span>
            </router-link>
        </li>
        <li class="">
            <router-link to="/" >
                <div class="icon-w">
                    <div class="os-icon os-icon-layers"></div>
                </div>
                <span>Perimisos</span></router-link>
        </li>
        <li class="">
            <router-link to="/" >
                <div class="icon-w">
                    <div class="os-icon os-icon-layers"></div>
                </div>
                <span>Registro</span></router-link>
        </li>
        <li class="sub-header">
            <span>Configuración</span>
        </li>
        <li class=" ">
            <router-link to="/" >
                <div class="icon-w">
                    <div class="os-icon os-icon-package"></div>
                </div>
                <span>Dias Festivos</span></router-link>
        </li>
        <li class=" ">
            <router-link to="/" >
                <div class="icon-w">
                    <div class="os-icon os-icon-file-text"></div>
                </div>
                <span>Unidades de Negocio</span></router-link>
        </li>
        <li class=" ">
            <router-link to="/usuarios" >
                <div class="icon-w">
                    <div class="os-icon os-icon-file-text"></div>
                </div>
                <span>Usuarios</span>
            </router-link>
        </li>
        <li class=" ">
            <router-link to="/roles" >
                <div class="icon-w">
                    <div class="os-icon os-icon-file-text"></div>
                </div>
                <span>Roles</span>
            </router-link>
        </li>

        <li class="sub-header">
            <span>Reportes</span>
        </li>
        <li class=" ">
            <router-link to="/" >
                <div class="icon-w">
                    <div class="os-icon os-icon-layers"></div>
                </div>
                <span>Perimisos</span></router-link>
        </li>
        <li class="sub-header">
            <span>Cuenta</span>
        </li>
        <li class=" ">
            <router-link to="/" >
                <div class="icon-w">
                    <div class="os-icon os-icon-layers"></div>
                </div>
                <span>Configuración</span></router-link>
        </li>
        <li class=" ">
            <a href="#" v-on:click="logout">
                <div class="icon-w">
                    <div class="os-icon os-icon-layers"></div>
                </div>
                <span>Salir</span></a>
        </li>
    </ul>
</template>

<script>
    export default {
        methods: {
            logout() {
                this.$store.dispatch('logout')
            },
        },
    }
</script>