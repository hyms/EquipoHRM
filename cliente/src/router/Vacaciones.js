//configuracion


import TipoVacacion from "../views/Vacaciones/TipoVacacion";
import DiasFestivos from "../views/Vacaciones/DiasFestivos";
import Vacaciones from "../views/Vacaciones/Vacaciones";
import VacacionesEstados from "../views/Vacaciones/VacacionesEstados";

export default [
    {
        path: "/vacaciones/tipovacacion/",
        meta: {
            auth: true
        },
        component: TipoVacacion,
    },
    {
        path: "/vacaciones/diasfestivos/",
        meta: {
            auth: true
        },
        component: DiasFestivos,
    },
    {
        path: "/vacaciones/vacaciones/",
        meta: {
            auth: true
        },
        component: Vacaciones,
    }, {
        path: "/vacaciones/estado/",
        meta: {
            auth: true
        },
        component: VacacionesEstados,
    }
]