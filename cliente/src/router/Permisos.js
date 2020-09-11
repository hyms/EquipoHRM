//configuracion


import TipoPermiso from "../views/Permisos/TipoPermiso";
import Permisos from "../views/Permisos/Permisos";

export default [
    {
        path: "/permisos/tipopermisos/",
        meta: {
            auth: true
        },
        component: TipoPermiso,
    },
    {
        path: "/permisos/permisos/",
        meta: {
            auth: true
        },
        component: Permisos,
    },

]