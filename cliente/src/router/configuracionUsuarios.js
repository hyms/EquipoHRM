//configuracion

import ConfiguracionUsuarios from "../views/Configuracion/Usuarios/ConfiguracionUsuarios";
import Usuarios from "../views/Configuracion/Usuarios/Usuarios";
import Roles from "../views/Configuracion/Usuarios/Roles";

export default [
    {
        path: "/configuracion/usuarios/",
        //redirect: "/configuracion/usuarios",
        meta: {
            auth: true
        },
        component: ConfiguracionUsuarios,
        children: [
            {
                path: "usuarios",
                meta: {
                    auth: true
                },
                component: Usuarios
            },
            {
                path: "roles",
                meta: {
                    auth: true
                },
                component: Roles
            },
        ]
    }
]