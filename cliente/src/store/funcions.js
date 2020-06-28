import moment from 'moment'
import Vue from 'vue'

Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('DD/MM/YYYY hh:mm')
    }
});

Vue.filter('formatState', function(value) {
        return (value === 1) ? 'Activo' : 'Inactivo'
});