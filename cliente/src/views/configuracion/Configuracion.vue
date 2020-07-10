<template>
    <div>
        <div class="top-bar color-scheme-light overflow-auto">
            <ul class="col-12">
                <router-link
                        v-for="(link, key) in links"
                        :to="link.to"
                        :key="key"
                        tag="li"
                        exact
                        exact-active-class="active"
                >
                    <a>{{ link.name }}</a>
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

<script>
    export default {
        data() {
            return {
                links: {
                    usuarios: {name: "Usuarios", to: "/configuracion/usuarios"},
                    roles: {name: "Roles", to: "/configuracion/roles"}
                }
            };
        }
    };
</script>
