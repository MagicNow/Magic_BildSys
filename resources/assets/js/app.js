
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

// Vue.component('generic-grid',require('./components/generic-grid.vue'));

import GenericGrid from './components/generic-grid.vue'

Vue.component('generic-grid', GenericGrid);

// Vue.component('generic-paginator',require('./components/generic-paginator.vue'));

import GenericPaginator from './components/generic-paginator.vue'

Vue.component('generic-paginator', GenericPaginator);

const app = new Vue({
    el: '#root',
    components: {
        GenericGrid,
        GenericPaginator
    },
    data: {
        searchQuery: '',
        gridColumns: ['codigo', 'descricao', 'servico', '#'],
        gridData: [

        ],
        pagination: {
            total: 0,
            per_page: 12,    // required
            current_page: 1, // required
            last_page: 0,    // required
            from: 1,
            to: 12           // required
        },
        paginationOptions: {
            offset: 4,
            previousText: 'Anterior',
            nextText: 'Proxima',
            alwaysShowPrevNext: true
        }
    },
    methods:{
        loadData(){
            let options = {
                params: {
                    paginate: this.pagination.per_page,
                    page: this.pagination.current_page,
                    /* additional parameters */
                }
            };
            $.getJSON('/compras/insumos/lista', options.params, function (response) {
                app.gridData = response.data;
                app.pagination.total = response.total;
                app.pagination.last_page = response.last_page;
                app.pagination.current_page = response.current_page;
                app.pagination.from = response.from;
                app.pagination.to = response.to;
            });
        }
    }
    ,
    created: function () {
        this.loadData();
    }
});
