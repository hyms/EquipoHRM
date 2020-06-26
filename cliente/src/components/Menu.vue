<template>
    <ul class="main-menu">
        <li class="sub-header">
            <span>Personal</span>
        </li>
        <li class="">
            <a href="#">
                <div class="icon-w">
                    <div class="os-icon os-icon-layout"></div>
                </div>
                <span>Vacaciones</span>
            </a>
        </li>
        <li class="">
            <a href="#">
                <div class="icon-w">
                    <div class="os-icon os-icon-layers"></div>
                </div>
                <span>Perimisos</span></a>
        </li>
        <li class="">
            <a href="#">
                <div class="icon-w">
                    <div class="os-icon os-icon-layers"></div>
                </div>
                <span>Registro</span></a>
        </li>
        <li class="sub-header">
            <span>Configuración</span>
        </li>
        <li class=" ">
            <a href="#">
                <div class="icon-w">
                    <div class="os-icon os-icon-package"></div>
                </div>
                <span>Dias Festivos</span></a>
        </li>
        <li class=" ">
            <a href="#">
                <div class="icon-w">
                    <div class="os-icon os-icon-file-text"></div>
                </div>
                <span>Unidades de Negocio</span></a>
        </li>

        <li class="sub-header">
            <span>Reportes</span>
        </li>
        <li class=" ">
            <a href="#">
                <div class="icon-w">
                    <div class="os-icon os-icon-layers"></div>
                </div>
                <span>Perimisos</span></a>
        </li>
        <li class="sub-header">
            <span>Cuenta</span>
        </li>
        <li class=" ">
            <a href="#">
                <div class="icon-w">
                    <div class="os-icon os-icon-layers"></div>
                </div>
                <span>Configuración</span></a>
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