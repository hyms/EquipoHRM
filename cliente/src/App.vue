<template>
    <div id="app">
        <div class="all-wrapper" v-bind:class="[isLogged ?  'solid-bg-all' : 'menu-side with-pattern']">
            <div v-bind:class="[isLogged ?  'layout-w' : 'auth-box-w']">
                <Menu v-if="isLogged"/>
                <router-view/>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import Menu from "@/components/MainMenu";

    let myBody = null;
    export default {
        data() {
            return {}
        },
        mounted() {
            this.bodyClass();
        },
        updated() {
            this.bodyClass();
        },
        components: {
            Menu,
        },
        computed: {
            ...mapGetters([
                'isLogged'
            ]),
        },
        methods: {
            bodyClass() {
                myBody = document.getElementsByTagName('body')[0];
                if (this.isLogged) {
                    myBody.classList.add('menu-position-side', 'menu-side-left', 'full-screen');
                } else {
                    myBody.classList.add('auth-wrapper');
                }

            },


        },


    }
</script>

<style>
</style>
