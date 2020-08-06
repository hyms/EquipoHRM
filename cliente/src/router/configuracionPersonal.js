//configuracion

import Personal from "../views/Configuracion/Empleado/Personal";
import PersonalDetalle from "../views/Configuracion/Empleado/PersonalDetalle";
import PersonalForm from "../views/Configuracion/Empleado/PersonalForm";
import carreraForm from "../views/Configuracion/Empleado/carreraForm";
import PersonalUsuarios from "../views/Configuracion/Empleado/PersonalUsuarios";

export default [
    {
        path: "/configuracion/personal/",
        meta: {
            auth: true
        },
        component: Personal
    },
    {
        path: "/configuracion/personal/detalle",
        redirect: {name: "detalle"},
        meta: {
            auth: true
        },
        component: PersonalDetalle,
        children: [
            {
                path: "detalle",
                name: "detalle",
                meta: {
                    auth: true
                },
                component: PersonalForm
            },
            {
                path: "carrera",
                meta: {
                    auth: true
                },
                component: carreraForm
            },
            {
                path: "cuenta",
                meta: {
                    auth: true
                },
                component: PersonalUsuarios
            }
        ]
    }
]