<template>
  <TopMenu :links="links" :url="path"/>
</template>

<script>
    import TopMenu from "@/components/menu/TopMenu";

    export default {
        data() {
            return {
                path: "/configuracion/usuarios/",
                links: {
                    usuarios: "Usuarios", //roles: "Roles"
                    rules: "Privilegios"
                }
            };
        },
        components: {
            TopMenu
        }
    };
</script>
