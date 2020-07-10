import Vue from "vue";
import VueRouter from "vue-router";
//components
import Error from "../views/Error";
import Login from "../views/Login";
import Home from "../views/Home";
import Diasfestivos from "../views/Diasfestivos";
//organizacion
import Organizacion from "../views/organizacion/Organizacion";
import Cargos from "../views/organizacion/Cargos";
import Areastrabajo from "../views/organizacion/Areastrabajo";
import Unidadesnegocio from "../views/organizacion/Unidadesnegocio";
import Empresa from "../views/organizacion/Empresa";
import Regional from "../views/organizacion/Regional";
//configuracion
import Configuracion from "../views/configuracion/Configuracion";
import Usuarios from "../views/configuracion/Usuarios";
import Roles from "../views/configuracion/Roles";

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
  {
    path: "/configuracion",
    redirect: "/configuracion/usuarios",
    name: "Configuracion",
    meta: {
      auth: true
    },
    component: Configuracion,
    children: [
      {
        path: "/configuracion/usuarios",
        name: "Usuarios",
        meta: {
          auth: true
        },
        component: Usuarios
      },
      {
        path: "/configuracion/roles",
        name: "Roles",
        meta: {
          auth: true
        },
        component: Roles
      }
    ]
  },
  {
    path: "/organizacion",
    name: "Organizacion",
    meta: {
      auth: true
    },
    component: Organizacion,
    children: [
      {
        path: "/organizacion/areastrabajo",
        name: "Areastrabajo",
        meta: {
          auth: true
        },
        component: Areastrabajo
      },
      {
        path: "/organizacion/unidadesnegocio",
        name: "Unidadesnegocio",
        meta: {
          auth: true
        },
        component: Unidadesnegocio
      },
      {
        path: "/organizacion/cargos",
        name: "Cargos",
        meta: {
          auth: true
        },
        component: Cargos
      },
      {
        path: "/organizacion/empresa",
        name: "Empresa",
        meta: {
          auth: true
        },
        component: Empresa
      },
      {
        path: "/organizacion/regional",
        name: "Regional",
        meta: {
          auth: true
        },
        component: Regional
      }
    ]
  },
  {
    path: "/diasfestivos",
    name: "Diasfestivos",
    meta: {
      auth: true
    },
    component: Diasfestivos
  }
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
