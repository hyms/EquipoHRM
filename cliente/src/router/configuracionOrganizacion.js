//configuracion

import ConfiguracionOrganizacion from "../views/Configuracion/Organizacion/ConfiguracionOrganizacion";
import Regional from "../views/Configuracion/Organizacion/Regional";
import Gerencia from "../views/Configuracion/Organizacion/Gerencia";
import Unidadesnegocio from "../views/Configuracion/Organizacion/Unidadesnegocio";
import Areastrabajo from "../views/Configuracion/Organizacion/Areastrabajo";

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

        ]
    }
]