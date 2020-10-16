<template>
  <TopMenu :links="links" :url="path"/>
</template>

<script>
  import TopMenu from "@/components/menu/TopMenu";

  export default {
    data() {
      return {
        path: "/configuracion/organizacion/",
        links: {
          regional: "Regional",
          gerencia: "Gerencia",
          areastrabajo: "Areas de Trabajo",
          unidadesnegocio: "Unidades de negocio",
          cargos: "Cargos",
          empresa: "Empresa"
        }
      };
    },
    components: {
      TopMenu
    }
  };
</script>
