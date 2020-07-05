import moment from 'moment'
import Vue from 'vue'

Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('DD/MM/YYYY hh:mm')
    }
});
Vue.filter('formatDateOnly', function(value) {
    if (value) {
        return moment(String(value)).format('DD/MM/YYYY')
    }
});

Vue.filter('formatState', function(value) {
        return (value === 1) ? 'Activo' : 'Inactivo'
});

Vue.filter('formatElementName', function(value,table) {
    if(table) {
        const index = table.findIndex((element) => element['id'] === value);
        if (index > -1)
            return table[index]['nombre'];
    }
    return value;
});