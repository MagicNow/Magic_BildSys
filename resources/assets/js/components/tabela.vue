<template>
    <div>
        <table class="table">
            <thead class="head-table">
            <tr>
                <th class="row-table" v-for="item in head">
                    {{ item }}
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="dado in dados">
                <td class="row-table" v-for="chave in chaves">{{dado[chave]}}</td>
            </tr>
            </tbody>
        </table>
        <generic-paginator :pagination="pagination" :callback="loadData" :options="paginationOptions"></generic-paginator>
    </div>
</template>
<script>
    export default{
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
                head: [],
                chaves: [],
                dados: [],
                success: '',
                error: '',
                planejamento_id:'',
                pagination: {
                    type: Object
                },
                paginationOptions: {
                    offset: 4,
                    previousText: 'Anterior',
                    nextText: 'Proxima',
                    alwaysShowPrevNext: false
                },
            }
        },
        computed: {

        },
        methods: {
            getHeader: function () {
                if(this.colunas != null){
                    for(var j in this.colunas){
                        this.head.push(this.colunas[j].label);
                        this.chaves.push(this.colunas[j].campo_db)
                    }
                }else{
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
                let options = {
                    params: {
                        paginate: this.pagination.per_page,
                        page: this.pagination.current_page,
                        planejamento_id: 1
                    }
                };
                this.success = '';
                this.error = '';
                startLoading();
                this.$http.get('/insumos_json', {
                    params: options.params
                }).then(function (resp) {
                    if (typeof resp.data == 'object') {
                        this.dados = resp.data.data;
                        this.pagination = resp.data;
                        if(typeof this.head == 'undefined' || this.head.length == 0){
                            this.getHeader();
                        }
                    } else if (typeof resp.data == 'string') {
                        var response = jQuery.parseJSON(resp.data);
                        this.dados = response.data;
                        this.pagination = response;
                        if(typeof this.head == 'undefined' || this.head.length == 0){
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