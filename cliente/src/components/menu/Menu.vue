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
                :to="'/' + key + '/' + ksub"
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
                    vacaciones: {
                        name: "Vacaciones/Permisos",
                        estado: {
                            name: "Estados",
                            icon: "os-icon os-icon-package"
                        },
                        vacaciones: {
                            name: "Lista",
                            icon: "os-icon os-icon-package"
                        },
                        tipovacacion: {
                            name: "Tipos",
                            icon: "os-icon os-icon-package"
                        },
                        diasfestivos: {
                            name: "Dias Festivos",
                            icon: "os-icon os-icon-package"
                        }
                    },
                    configuracion: {
                        name: "Configuración",
                        personal: {
                            name: "Personal",
                            icon: "os-icon os-icon-package"
                        },
                        organizacion: {
                            name: "Organizacion",
                            icon: "os-icon os-icon-package"
                        },
                        usuarios: {
                            name: "Usuarios",
                            icon: "os-icon os-icon-package"
                        }
                    },
                    cuenta: {
                        name: "Cuenta",
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
