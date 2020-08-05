import moment from "moment";
import Vue from "vue";

Vue.filter("formatDate", function (value) {
    if (value) {
        return moment(String(value)).format("DD/MM/YYYY hh:mm");
    }
});
Vue.filter("formatDateOnly", function (value) {
    if (value) {
        return moment(String(value)).format("DD/MM/YYYY");
    }
});

Vue.filter("formatState", function (value) {
    return value === 1 ? "Activo" : "Inactivo";
});

Vue.filter("formatElementName", function (value, table) {
    if (table) {
        const index = table.findIndex(element => element["id"] === value);
        if (index > -1) return table[index]["nombre"];
    }
    return value;
});
Vue.filter("formatCivilState", function (value) {
    if (value) return Helpers.estadoCivil(value);
    return value;
});

let Helpers = {
    estadoCivil(id = null) {
        const estado = ["Soltero/a", "Casado/a", "Divorciado/a", "Viudo/a"];
        if (id) return estado[id];
        return estado;
    },
    tipoEmpleado(id = null) {
        const estado = ["Obrero/a", "Oficinista"];
        if (id) return estado[id];
        return estado;
    }
}

export default Helpers;


