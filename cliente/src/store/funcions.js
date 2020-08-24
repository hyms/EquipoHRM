import moment from "moment";
import Vue from "vue";

Vue.filter("roles", function (value = null) {
  const roles = [
    {id: 0, name: "sadmin"},
    {id: 1, name: "RRHH"},
    {id: 2, name: "Gerente"},
    {id: 3, name: "Encargado/Jefe"}
  ];
  if (value) {
    return roles[value].name;
  }
  return roles;
});

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
Vue.filter("formatPostDateOnly", function (value) {
  if (value) {
    return moment(value).format("YYYY-MM-DD");
  }
});

Vue.filter("formatStateLeave", function (value) {
  let estados = ["pendiente", "aprovado", "registrado", "concluido"];
  estados[10] = "Declinado";
  return estados[value];
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
  },
  tipoDiaVacacion(id = null) {
    const estado = [
      {value: 0, name: "Media Jornada"},
      {value: 1, name: "Jornada Completa"}
    ];
    if (id) return estado[id];
    return estado;
  }
};

export default Helpers;
