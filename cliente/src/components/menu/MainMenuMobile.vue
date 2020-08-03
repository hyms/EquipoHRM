<template>
    <!--------------------
              START - Mobile Menu
              -------------------->
    <div class="menu-mobile menu-activated-on-click color-scheme-dark">
        <div class="mm-logo-buttons-w">
            <router-link to="/" class="mm-logo">
                <img :src="images.logo"/><span>Clean Admin</span>
            </router-link>
            <div class="mm-buttons">
                <div class="mobile-menu-trigger" v-on:click="botonMenu = !botonMenu">
                    <div class="os-icon os-icon-hamburger-menu-1"></div>
                </div>
            </div>
        </div>
        <VueSlideToggle :open="botonMenu" tag="section" :duration="200">
            <div
                    class="menu-and-user"
                    v-bind:style="botonMenu ? 'display:block' : 'display:none'"
            >
                <div class="logged-user-w">
                    <div class="avatar-w">
                        <img alt="" :src="images.avatar"/>
                    </div>
                    <div class="logged-user-info-w">
                        <div class="logged-user-name">
                            Maria Gomez
                        </div>
                        <div class="logged-user-role">
                            Administrator
                        </div>
                    </div>
                </div>
                <Menu/>
            </div>
        </VueSlideToggle>
    </div>
    <!--------------------
          END - Mobile Menu
          -------------------->

</template>

<script>
    import Menu from "./Menu";

    export default {
        props: {},

        data() {
            return {
                images: {
                    logo: require("@/assets/img/logo.png"),
                    avatar: require("@/assets/img/avatar1.jpg")
                },
                botonMenu: false
            };
        },
        components: {
            Menu
        },
        methods: {
            logout() {
                this.$store.dispatch("logout");
            }
        }
    };
</script>
