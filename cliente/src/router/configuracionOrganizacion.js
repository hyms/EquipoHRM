//configuracion

import ConfiguracionOrganizacion from "../views/Configuracion/Organizacion/ConfiguracionOrganizacion";
import Regional from "../views/Configuracion/Organizacion/Regional";
import Gerencia from "../views/Configuracion/Organizacion/Gerencia";
import Unidadesnegocio from "../views/Configuracion/Organizacion/Unidadesnegocio";
import Areastrabajo from "../views/Configuracion/Organizacion/Areastrabajo";
import Cargos from "../views/Configuracion/Organizacion/Cargos";
import Empresa from "../views/Configuracion/Organizacion/Empresa";

export default [
    {
        path: "/configuracion/organizacion/",
        //redirect: "/configuracion/usuarios",
        meta: {
            auth: true
        },
        component: ConfiguracionOrganizacion,
        children: [
            {
                path: "regional",
                meta: {
                    auth: true
                },
                component: Regional
            },
            {
                path: "gerencia",
                meta: {
                    auth: true
                },
                component: Gerencia
            },
            {
                path: "unidadesnegocio",
                meta: {
                    auth: true
                },
                component: Unidadesnegocio
            },
            {
                path: "areastrabajo",
                meta: {
                    auth: true
                },
                component: Areastrabajo
            },
            {
                path: "cargos",
                meta: {
                    auth: true
                },
                component: Cargos
            },
            {
                path: "empresa",
                meta: {
                    auth: true
                },
                component: Empresa
            },

        ]
    }
]