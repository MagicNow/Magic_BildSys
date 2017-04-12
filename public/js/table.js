Vue.component('tabela', {
    template: '<table class="table table-bordered">' +
    '<thead >' +
    '<th v-for="head in tableHead">{{ head }}</th>' +
    '</thead>' +
    '<tbody>' +
    '<tr v-for="dado in dados">' +
    '<td v-for="chave in chaves">{{dado[chave]}}</td>' +
    '</tr>' +
    '</tbody>' +
    '</table>',
    props: {
        apiUrl: {
            required: true
        },
        params: [],
        actions: [],
        colunas: ''
    },
    data:function() {
        return{
            tableHead: [],
            chaves: [],
            dados: [],
            success: '',
            error: '',
            pagination: {
                total: 0,
                per_page: 10,
                from: 1,
                to: 0,
                current_page: 1
            },
            offset: 4
        }
    },
    computed: {
        isActived: function () {
            return this.pagination.current_page;
        },
        pagesNumber: function () {
            if (!this.pagination.to) {
                return [];
            }
            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }
            var to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }
            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        },
    },
    methods: {
        getHeader: function () {
            if(this.colunas != null){
                for(var j in this.colunas){
                    this.tableHead.push(this.colunas[j].label);
                    this.chaves.push(this.colunas[j].campo_db)
                }
            }else{
                this.tableHead = Object.keys(this.dados[0]);
                this.chaves = Object.keys(this.dados[0]);
            }

        },
        changePage: function (page) {
            this.pagination.current_page = page;
            this.loadInsumos(page);
        },
        loadData: function (page) {
            this.success = '';
            this.error = '';
            startLoading();
            this.$http.get('/insumos_json', {
                params: {page: page, planejamento_id: 1}
            }).then(function (resp) {
                if (typeof resp.data == 'object') {
                    this.dados = resp.data.data;
                    this.pagination = resp.data;
                    this.getHeader();
                } else if (typeof resp.data == 'string') {
                    var response = jQuery.parseJSON(resp.data);
                    this.dados = response.data;
                    this.pagination = response;
                    this.getHeader();
                }
                stopLoading();
            });
        },
    },

    created: function () {
        this.loadData(1);
    }
});

new Vue({
    el:'#app'
});