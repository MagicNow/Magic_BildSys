
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

// Vue.component('example', require('./components/Example.vue'));

Vue.component('generic-grid',require('./components/generic-grid.vue'));

Vue.component('generic-paginator',require('./components/generic-paginator.vue'));

var VueTables = require('vue-tables-2');
Vue.use(VueTables.ClientTable, {
    compileTemplates: true,
    highlightMatches: true,
    pagination: {
        dropdown:true,
        chunk:5
    },
    filterByColumn: true,
    texts: {
        filter: "Search:"
    },
    datepickerOptions: {
        showDropdowns: true
    }
});
const app = new Vue({
    el: '#app',
    data: {
        columns: ['id','name','age'],
        tableData: [
            {id:1, name:"John",age:"20"},
            {id:2, name:"Jane",age:"24"},
            {id:3, name:"Susan",age:"16"},
            {id:4, name:"Chris",age:"55"},
            {id:5, name:"Dan",age:"40"}
        ],
        options: {
            // see the options API
        }
    }
});