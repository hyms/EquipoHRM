<template>
    <ul class="main-menu">
        <li class="sub-header">
            <span>Personal</span>
        </li>
        <router-link to="/" tag="li" exact exact-active-class="selected">
            <a>
                <div class="icon-w">
                    <div class="os-icon os-icon-layout"></div>
                </div>
                <span>Vacaciones</span>
            </a>
        </router-link>


        <li class="sub-header">
            <span>Reportes</span>
        </li>
        <router-link to="/" tag="li" exact exact-active-class="selected">
            <a>
                <div class="icon-w">
                    <div class="os-icon os-icon-layers"></div>
                </div>
                <span>Perimisos</span>
            </a>
        </router-link>

        <li class="sub-header">
            <span>Configuración</span>
        </li>
        <router-link to="/organizacion" tag="li" exact exact-active-class="selected">
            <a>
                <div class="icon-w">
                    <div class="os-icon os-icon-package"></div>
                </div>
                <span>Organizacion</span>
            </a>
        </router-link>
        <router-link to="/diasfestivos" tag="li" exact exact-active-class="selected">
            <a>
                <div class="icon-w">
                    <div class="os-icon os-icon-package"></div>
                </div>
                <span>Dias Festivos</span>
            </a>
        </router-link>

        <li class="sub-header">
            <span>Cuenta</span>
        </li>
        <router-link to="/configuracion" tag="li" exact exact-active-class="selected">
            <a>
                <div class="icon-w">
                    <div class="os-icon os-icon-layers"></div>
                </div>
                <span>Configuración</span>
            </a>
        </router-link>
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