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
          regional: {name: "Regional", to: "/organizacion/regional"},
          unidadesnegocio: {
            name: "Unidades de negocio",
            to: "/organizacion/unidadesnegocio"
          },
          areastrabajo: {
            name: "Areas de Trabajo",
            to: "/organizacion/areastrabajo"
          },
          cargos: {name: "Cargos", to: "/organizacion/cargos"},
          empresa: {name: "Empresa", to: "/organizacion/empresa"}
        }
      };
    }
  };
</script>
