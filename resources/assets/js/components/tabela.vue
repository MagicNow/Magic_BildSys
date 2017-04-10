<template>
    <div>
        <table class="table">
            <thead class="head-table">
            <tr>
                <th class="row-table"
                    v-for="item in head"
                    @click="sortTable(item)"
                >
                    {{ item }}
                    <span>
                        <i class="fa fa-arrow-down" aria-hidden="true"></i>
                    </span>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="dado in dados">
                <td class="row-table" v-for="chave in chaves">{{dado[chave]}}</td>
            </tr>
            </tbody>
        </table>
        <div class="text-center">
            <generic-paginator :pagination="pagination" :callback="loadData"
                               :options="paginationOptions"></generic-paginator>
        </div>
    </div>
</template>
<script>
    export default{
        props: {
            apiUrl: {
                required: true
            },
            params: {
                type: Object
            },
            actions: [],
            colunas: ''
        },
        data: function () {
            var sortOrders = {}
            for (var j in this.chaves){
                sortOrders[this.colunas[j]] = 1
            }
            return {
                head: [],
                chaves: [],
                dados: [],
                success: '',
                error: '',
                pagination: {
                    type: Object
                },
                paginationOptions: {
                    offset: 4,
                    previousText: 'Anterior',
                    nextText: 'Proxima',
                    alwaysShowPrevNext: false
                },
                sortKey: '',
                sortOrders: sortOrders
            }
        },
        computed: {

        },
        methods: {
            sortTable: function(item){
                if (typeof this.colunas[0] == 'undefined' || this.colunas[0].length == 0) {

                }else{
                    Array.prototype.getIndexBy = function (name, value) {
                        for (var i = 0; i < this.length; i++) {
                            if (this[i][name] == value) {
                                return i;
                            }
                        }
                        return -1;
                    }
                    var a = this.colunas[this.colunas.getIndexBy("label", item)]
                    this.params.orderkey = a.campo_db;
                    this.params.order= 'desc';
                    this.loadData();
                }
            },
            getHeader: function () {
                if (this.colunas != null) {
                    for (var j in this.colunas) {
                        this.head.push(this.colunas[j].label);
                        this.chaves.push(this.colunas[j].campo_db)
                    }
                } else {
                    this.head = Object.keys(this.dados[0]);
                    this.chaves = Object.keys(this.dados[0]);
                }
            },
            getParameterByName: function (name, url) {
                if (!url) {
                    url = window.location.href;
                }
                name = name.replace(/[\[\]]/g, "\\$&");
                var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                    results = regex.exec(url);
                if (!results) return null;
                if (!results[2]) return '';
                return decodeURIComponent(results[2].replace(/\+/g, " "));
            },
            loadData: function () {
                this.params.paginate = this.pagination.per_page;
                this.params.page = this.pagination.current_page;
                this.success = '';
                this.error = '';
                startLoading();
                this.$http.get(this.apiUrl, {
                    params: this.params
                }).then(function (resp) {
                    if (typeof resp.data == 'object') {
                        this.dados = resp.data.data;
                        this.pagination = resp.data;
                        if (typeof this.head == 'undefined' || this.head.length == 0) {
                            this.getHeader();
                        }
                    } else if (typeof resp.data == 'string') {
                        var response = jQuery.parseJSON(resp.data);
                        this.dados = response.data;
                        this.pagination = response;
                        if (typeof this.head == 'undefined' || this.head.length == 0) {
                            this.getHeader();
                        }
                    }
                    stopLoading();
                });
            },
        },
        mounted: function () {
            this.loadData();
        }
    }
</script>

<style>
</style>