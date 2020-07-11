<template>
  <ul class="main-menu">
    <template v-for="(link, key) in links">
      <template v-for="(sublink, ksub) in link">
        <li class="sub-header" v-if="ksub === 'name'" :key="key + ksub">
          <span>{{ sublink }}</span>
        </li>
        <li v-else-if="ksub === 'logout'" :key="key + ksub">
          <a v-on:click="logout">
            <div class="icon-w">
              <div class="os-icon os-icon-signs-11"></div>
            </div>
            <span>{{ sublink }}</span>
          </a>
        </li>
        <router-link
                v-else
                :to="'/' + ksub"
                tag="li"
                exact
                exact-active-class="selected"
                :key="key + ksub"
        >
          <a>
            <div class="icon-w">
              <div :class="sublink.icon"></div>
            </div>
            <span>{{ sublink.name }}</span>
          </a>
        </router-link>
      </template>
    </template>
  </ul>
</template>

<script>
  export default {
    data() {
      return {
        links: {
          personal: {
            name: "Personal",
            vacaciones: {
              name: "Vacaciones",
              icon: "os-icon os-icon-package"
            }
          },
          configuracion: {
            name: "Configuración",
            organizacion: {
              name: "Organizacion",
              icon: "os-icon os-icon-package"
            },
            personal: {
              name: "Personal",
              icon: "os-icon os-icon-package"
            },
            diasfestivos: {
              name: "Dias Festivos",
              icon: "os-icon os-icon-package"
            }
          },
          cuenta: {
            name: "Cuenta",
            configuracion: {
              name: "Configuración",
              icon: "os-icon os-icon-layers"
            },
            logout: "Salir"
          }
        }
      };
    },
    methods: {
      logout() {
        this.$store.dispatch("logout");
      }
    }
  };
</script>
