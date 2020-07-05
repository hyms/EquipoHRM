<template>
    <div>
        <div class="top-bar color-scheme-light">
            <ul>
                <router-link to="/organizacion/unidadesnegocio" tag="li" exact exact-active-class="active">
                    <a>Unidades de negocio</a>
                </router-link>
                <router-link to="/organizacion/areastrabajo" tag="li" exact exact-active-class="active">
                    <a>Areas de Trabajo</a>
                </router-link>
                <router-link to="/organizacion/cargos" tag="li" exact exact-active-class="active">
                    <a>Cargos</a>
                </router-link>
            </ul>
        </div>
        <div class="content-i">
            <div class="content-box">
                <div class="row">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </div>
</template>
