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
                    <span v-if="order == 'asc'">
                        <i class="fa fa-chevron-down" aria-hidden="true"></i>
                    </span>
                    <span v-else>
                        <i class="fa fa-chevron-up" aria-hidden="true"></i>
                    </span>
                </th>
                <th v-if="actions.status != undefined" class="row-table">Status</th>
                <th v-if="actions.detalhe != undefined" class="row-table">Detalhe</th>
                <th v-if="actions.aprovar != undefined" class="row-table">Aprovar</th>
                <th v-if="actions.reprovar != undefined" class="row-table">Reprovar</th>
                <th v-if="actions.troca != undefined" class="row-table">Troca</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="dado in dados">
                <td class="row-table" v-for="chave in chaves">{{dado[chave]}}</td>
                <td class="row-table" v-if="actions.status != undefined">
                    <i v-if="dado['status'] == 0" class="fa fa-circle green"></i>
                    <i v-if="dado['status'] == 1" class="fa fa-circle red"></i>
                    <i v-if="dado['status'] == -1" class="fa fa-circle orange"></i>
                </td>
                <td class="row-table" v-if="actions.detalhe != undefined">
                    <a v-bind:href="dado['caminho']+'/'+dado['id']"><i class="fa fa-eye"></i></a>
                </td>
                <td class="row-table" v-if="actions.aprovar != undefined" @click="aprovar(dado['id'])">
                    <i class="glyphicon glyphicon-ok grey"></i>
                </td>
                <td class="row-table" v-if="actions.reprovar != undefined" @click="reprovar(dado['id'])">
                    <i class="fa fa-times grey"></i>
                </td>
                <td class="row-table" v-if="actions.troca != undefined">
                    <i class="fa fa-exchange grey"></i>
                </td>
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
            actions: {
                status: '',
                troca: '',
                adicionar: '',
                detalhe: '',
                aprovar: '',
                reprovar: '',
                troca: ''
            },
            colunas: ''
        },
        data: function () {
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
                order: 'asc'
            }
        },
        computed: {

        },
        methods: {
            aprovar: function(id){

            },
            reprovar: function (id) {

            },
            sortTable: function(item){
                if(this.order.localeCompare('desc')==0 || this.order.localeCompare('')==0){
                    this.order = 'asc';
                }else{
                    this.order = 'desc';
                }

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
                    this.params.order= this.order;
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
        created: function () {
            console.log(this.actions.status);
            this.loadData();
        }
    }
</script>

<style>
</style>