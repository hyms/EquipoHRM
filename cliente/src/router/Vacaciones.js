//configuracion


import TipoVacacion from "../views/Vacaciones/TipoVacacion";
import DiasFestivos from "../views/Vacaciones/DiasFestivos";

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
    }
]