import Vue from "vue";
import VueRouter from "vue-router";
//components
import Error from "../views/Error";
import Login from "../views/Login";
import Home from "../views/Home";
import Diasfestivos from "../views/Diasfestivos";
//personal
import Personal from "../views/personal/Personal";
import PersonalDetalle from "../views/personal/PersonalDetalle";
//organizacion
import Organizacion from "../views/organizacion/Organizacion";
import Cargos from "../views/organizacion/Cargos";
import Areastrabajo from "../views/organizacion/Areastrabajo";
import Unidadesnegocio from "../views/organizacion/Unidadesnegocio";
import Empresa from "../views/organizacion/Empresa";
import Regional from "../views/organizacion/Regional";
import Gerencia from "../views/organizacion/Gerencia";
//configuracion
import Configuracion from "../views/configuracion/Configuracion";
import Usuarios from "../views/configuracion/Usuarios";
import Roles from "../views/configuracion/Roles";
import personalForm from "../views/personal/personalForm";
import carreraForm from "../views/personal/carreraForm";
import usuarioAccesoForm from "../views/personal/usuarioAccesoForm";
import personalHistorico from "../views/personal/personalHistorico";


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
        },
        {
            path: "/organizacion/gerencia",
            name: "Gerencia",
            meta: {
                auth: true
            },
            component: Gerencia
        }
    ]
  },
    {
        path: "/personal",
        name: "Personal",
        meta: {
            auth: true
        },
        component: Personal,
    },
    {
        path: "/personal/detalle",
        redirect: "/personal/detalle/detalle",
        name: "Detalle",
        meta: {
            auth: true
        },
        component: PersonalDetalle,
        children: [
            {
                path: "/personal/detalle/detalle",
                meta: {
                    auth: true
                },
                component: personalForm,
            },
            {
                path: "/personal/detalle/carrera",
                meta: {
                    auth: true
                },
                component: carreraForm,
            },
            {
                path: "/personal/detalle/cuenta",
                meta: {
                    auth: true
                },
                component: usuarioAccesoForm,
            },
            {
                path: "/personal/detalle/historial",
                meta: {
                    auth: true
                },
                component: personalHistorico,
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
