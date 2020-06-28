import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../views/Home";
import Login from "../views/Login";
import Usuarios from "../views/Usuarios";
import Roles from "../views/Roles";

Vue.use(VueRouter);

const routes = [
    //auth
    {
        path: "/",
        name: "Home",
        meta: {
            auth: true
        },
        component: Home
    },
    {
        path: '/usuarios',
        name: 'Usuarios',
        meta: {
            auth: true
        },
        component: Usuarios
    },
    {
        path: '/roles',
        name: 'Roles',
        meta: {
            auth: true
        },
        component: Roles
    },
    //no auth
    {
        path: '/login',
        name: 'Login',
        component: Login

    }
];

const router = new VueRouter({
    mode: "history",
    base: process.env.BASE_URL,
    routes
});

router.beforeEach((to, from, next) => {
    const loggedIn = localStorage.getItem('user');

    if (to.matched.some(record => record.meta.auth) && !loggedIn) {
        next('/login');
        return
    }
    next()
});


export default router;
