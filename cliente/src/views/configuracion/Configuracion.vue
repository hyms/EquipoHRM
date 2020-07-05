<template>
    <div>
        <div class="top-bar color-scheme-light">
            <ul>
                <router-link to="/configuracion/usuarios" tag="li" exact exact-active-class="active">
                    <a>Usuarios</a>
                </router-link>
                <router-link to="/configuracion/roles" tag="li" exact exact-active-class="active">
                    <a>Roles</a>
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
