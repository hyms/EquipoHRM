<template>
    <!--------------------
              START - Main Menu
              -------------------->
    <div
            class="menu-w color-scheme-dark color-style-bright menu-activated-on-hover menu-has-selected-link menu-position-side menu-side-left menu-layout-compact sub-menu-style-inside"
    >
        <div class="logo-w">
            <router-link to="/">
                <div class="logo-element"></div>
                <div class="logo-label">
                    EquipoHRM
                </div>
            </router-link>
        </div>
        <div class="logged-user-w avatar-inline">
            <div class="logged-user-i">
                <div class="avatar-w">
                    <img alt="" :src="images.avatar"/>
                </div>
                <div class="logged-user-info-w">
                    <div class="logged-user-name">
                       {{name}}
                    </div>
                    <div class="logged-user-role">
                        {{rol|roles}}
                    </div>
                </div>
            </div>
        </div>

        <h1 class="menu-page-header">
            EquipoHRM
        </h1>

        <Menu/>

        <!--------------------
            END - Main Menu
            -------------------->
    </div>
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
                botonMenu: false,
                name: "",
                rol: "",
            };
        },
        components: {
            Menu
        },
        methods: {
            logout() {
                this.$store.dispatch("logout");
            },
            getUser() {
                const userInfo = JSON.parse(localStorage.getItem("user"));
                console.log(userInfo.user);
                this.name = userInfo.user["alias"];
                this.rol = userInfo.user["rol"];
            }
        },
        created() {
            this.getUser();
        }
    };
</script>
