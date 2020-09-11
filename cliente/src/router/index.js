import Vue from "vue";
import VueRouter from "vue-router";
//components
import Error from "../views/Error";
import Login from "../views/Login";
import Home from "../views/Home";

//configuracion
import ConfiguracionUsuarios from "./configuracionUsuarios";
import ConfiguracionOrganizacion from "./configuracionOrganizacion";
import configuracionPersonal from "./configuracionPersonal";
import Vacaciones from "./Vacaciones";
import Permisos from "./Permisos";

Vue.use(VueRouter);

const routes = [
  //no auth
  {
    path: "/login",
    name: "Login",
    component: Login
  },
  {
    path: "*",
    component: Error
  },
  //auth
    {
        path: "/",
        name: "Home",
        meta: {
            auth: true
        },
        component: Home
    },
    ...ConfiguracionUsuarios,
    ...ConfiguracionOrganizacion,
    ...configuracionPersonal,
    ...Vacaciones,
    ...Permisos
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes
});

router.beforeEach((to, from, next) => {
  const loggedIn = localStorage.getItem("user");

  if (to.matched.some(record => record.meta.auth) && !loggedIn) {
    next("/login");
    return;
  }
  next();
});

export default router;
