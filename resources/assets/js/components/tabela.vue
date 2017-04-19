<template>
    <!-- Componente tabela vue -->
    <div>
        <!--Bloco de filtros -->
        <div v-if="actions.filtros != undefined">
            <div id="block_fields" class="col-md-12" style="margin-bottom: 20px" ></div>
            <div id="block_fields_thumbnail" class="col-md-12 thumbnail" style="margin-bottom: 20px;display: none;">
                <div id="block_fields_minimize" class="col-md-11"></div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-default" onclick="maximizeFilters();">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Adicionar filtros</h4>
                        </div>
                        <div class="modal-body">
                            <p v-for="(filtro, campo) in filtros">
                                <input class="cb_filter" type="checkbox" v-bind:id="'check_'+campo" v-bind:value="campo"/>
                                <label v-bind:for="'check_'+campo" style="cursor: pointer;" class="cb_filter_label">
                                    {{filtro}}
                                </label>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="addFilters()">Adicionar</button>
                        </div>
                    </div>
                </div>
            </div>

            <ol class="breadcrumb" style="margin-bottom: 0px;">
                <li class="col-md-5">
                    <input type="text" @keyup="loadData()" id="find" placeholder="Procurar" onkeyup="filterFind(this.value);" class="form-control" style="border-color:#f5f5f5;background-color:#f5f5f5;">
                </li>
                <li>
                    <a @click="loadData()" id="period_hoje" class="period" onclick="filterPeriod('hoje');" style="cursor: pointer">Hoje</a>
                </li>
                <li>
                    <a @click="loadData()" id="period_7" class="period" onclick="filterPeriod(7);" style="cursor: pointer">7 dias</a>
                </li>
                <li>
                    <a @click="loadData()" id="period_15" class="period" onclick="filterPeriod(15);" style="cursor: pointer">15 dias</a>
                </li>
                <li>
                    <a @click="loadData()" id="period_30" class="period" onclick="filterPeriod(30);" style="cursor: pointer">30 dias</a>
                </li>
                <li>
                    <input type="number" @keyup="loadData()" id="other_period" onkeyup="filterPeriod(this.value);" placeholder="Outro periodo" class="form-control" style="border-color:#f5f5f5;background-color:#f5f5f5;">
                </li>
                <input type="hidden" id="period_find" value="periodo=&procurar=">
                <li>
                    <a href="" data-toggle="modal" data-target="#myModal" class="grey">
                        Adicionar filtros <i class="fa fa-filter" aria-hidden="true"></i>
                    </a>
                </li>
            </ol>
        </div>
        <!--FIm Bloco de filtros -->
        <!--Tabela -->
        <table class="table">
            <thead class="head-table">
            <tr>
                <th class="row-table"
                    v-for="item in head"
                    @click="sortTable(item)"
                >
                    {{ item }}
                    <span v-if="order == 'asc' && dados.length>1">
                        <i class="fa fa-chevron-down" aria-hidden="true"></i>
                    </span>
                    <span v-else-if="order == 'desc' && dados.length>1">
                        <i class="fa fa-chevron-up" aria-hidden="true"></i>
                    </span>
                </th>
                <th v-if="actions.status != undefined" class="row-table">Status</th>
                <th v-if="actions.detalhe != undefined" class="row-table">Detalhe</th>
                <th v-if="actions.aprovar != undefined" class="row-table">Aprovar</th>
                <th v-if="actions.reprovar != undefined" class="row-table">Reprovar</th>
                <th v-if="actions.quantidade != undefined" class="row-table">Quantidade Compra</th>
                <th v-if="actions.troca != undefined" class="row-table">Troca</th>
                <th v-if="actions.adicionar != undefined" class="row-table">Adicionar</th>
            </tr>
            </thead>
            <tbody>
                <tr v-if="dados.length >0" v-for="(dado,i) in dados">

                    <td class="row-table" v-for="(chave,index) in chaves" >
                        <i v-if="dado['filho']>0 && dado['filho'] != undefined && index == 0" class="fa fa-share"></i>
                        {{dado[chave]}}
                    </td>
                    <td class="row-table" v-if="actions.status != undefined">
                        <i v-if="dado['status'] == 0" class="fa fa-circle green"></i>
                        <i v-if="dado['status'] == 1" class="fa fa-circle red"></i>
                        <i v-if="dado['status'] == -1" class="fa fa-circle orange"></i>
                    </td>
                    <td class="row-table" v-if="actions.detalhe != undefined">
                        <a v-bind:href="actions.detalhe_url+'/'+dado['id']"><i class="fa fa-eye"></i></a>
                    </td>
                    <td class="row-table" v-if="actions.aprovar != undefined" @click="aprovar(dado['id'])">
                        <i class="glyphicon glyphicon-ok grey"></i>
                    </td>
                    <td class="row-table" v-if="actions.reprovar != undefined" @click="reprovar(dado['id'])">
                        <i class="fa fa-times grey"></i>
                    </td>
                    <td class="row-table" v-if="actions.quantidade != undefined" @click="reprovar(dado['id'])">
                        <input @blur="adicionar(dado, i)" v-model.number="quant[i]" type="number" v-bind:value="quant[i]">
                    </td>
                    <td class="row-table" v-if="actions.troca != undefined">
                        <a  v-if="dado['pai']>0 && dado['pai'] != undefined && dado['unidade_sigla'] == 'VB'" v-bind:href="actions.troca_url+'/'+dado['id'] ">
                            <i class="fa fa-exchange blue"></i>
                        </a>
                        <a  v-if="dado['filho']>0 && dado['filho'] != undefined && dado['unidade_sigla'] == 'VB'" v-bind:href="actions.troca_remove+'/'+dado['planejamento_compra_id']">
                            <i class="fa fa-times red"></i>
                        </a>
                        <a  v-if="dado['filho']==0 && dado['pai']==0 && dado['unidade_sigla'] == 'VB'" v-bind:href="actions.troca_url+'/'+dado['id']">
                            <i class="fa fa-exchange grey"></i>
                        </a>
                    </td>
                    <td  class="row-table" v-if="actions.adicionar != undefined && dado.adicionado > 0">
                        <i class="fa fa-check green"></i>
                    </td>
                    <td class="row-table" v-else-if="actions.adicionar != undefined">
                        <button @click="adicionar(dado, i)" type="button" class="btn btn-xs btn-link">
                            <i class="fa fa-plus grey"></i>
                        </button>
                    </td>
                </tr>
                <tr v-else>
                    <td>Não há dados</td>
                </tr>
            </tbody>
        </table>
        <div v-if="pagination.last_page >1" class="text-center">
            <generic-paginator :pagination="pagination" :callback="loadData"
                               :options="paginationOptions"></generic-paginator>
        </div>
        <!-- Fim Tabela-->
    </div>
    <!-- Fim Componente tabela vue -->
</template>
<script>
    export default{
        props: {
            apiUrl: {
                required: true
            },
            apiFiltros:'',
            params: {
                type: Object
            },
            apiAdicionar: '',
            _token: '',
            actions: {
                status: '',
                troca: '',
                adicionar: '',
                detalhe: '',
                aprovar: '',
                reprovar: '',
                quantidade:'',
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
                filtros: [],
                pagination: {
                    type: Object
                },
                paginationOptions: {
                    offset: 4,
                    previousText: '',
                    nextText: '',
                    alwaysShowPrevNext: false
                },
                order: 'asc',
                quant: {}
            }
        },
        methods: {
            //Método da action aprovar onClick
            aprovar: function(id){

            },
            //Método da action adicionar onClick
            adicionar: function(item,i){
                if(this.actions.quantidade){
                    item['quantidade_compra'] = this.quant[i];
                }
                item['_token'] =this._token;
                if(!item['quantidade_compra'] && !item['adicionado']){
//                    swal('Insira uma quantidade!','','error');
                    return false;
                }
                this.$http.post(this.apiAdicionar, item)
                    .then(function (resp) {
                        if(resp.status){
                            var titulo = 'Adicionado';
                            if(item['adicionado']){
                                titulo = 'Alterado';
                            }
                            if(item['adicionado'] && !this.quant[i]){
                                titulo = 'Removido';
                                swal(titulo,'','success');
                            }
                            this.loadData();
                        }
                    })
                    .bind(this)
            },
            updateQuant: function (item) {

            },
            //Método da action reprovar onClick
            reprovar: function (id) {

            },
            //Mètodo de ordenação de tabela
            sortTable: function(item){
                Array.prototype.getIndexBy = function (name, value) {
                    for (var i = 0; i < this.length; i++) {
                        if (this[i][name] == value) {
                            return i;
                        }
                    }
                    return -1;
                }
                if(this.order.localeCompare('desc')==0 || this.order.localeCompare('')==0){
                    this.order = 'asc';
                }else{
                    this.order = 'desc';
                }
                if (typeof this.colunas == 'undefined' || this.colunas[0].length == 0) {
                    this.params.orderkey = item;
                    this.params.order= this.order;
                    this.loadData();
                }else{
                    var a = this.colunas[this.colunas.getIndexBy("label", item)]
                    this.params.orderkey = a.campo_db;
                    this.params.order= this.order;
                    this.loadData();
                }
            },
            //Método para preencher a header da table e para criar array de chaves
            getHeader: function () {
                if (this.colunas != null) {
                    for (var j in this.colunas) {
                        this.head.push(this.colunas[j].label);
                        this.chaves.push(this.colunas[j].campo_db)
                    }
                } else {
                    if(this.dados.length >0){
                        this.head = Object.keys(this.dados[0]);
                        this.chaves = Object.keys(this.dados[0]);
                    }
                }
            },
            //Método auxiliar de pegar parametro único da url
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
            //Método auxiliar de pegar todos parametros da url
            getParametersUrl: function(){
                var search = window.location.href.substr(window.location.href.indexOf("?") + 1);
                if(search.indexOf('http') === -1){
                    search = search?JSON.parse('{"' + search.replace(/&/g, '","').replace(/=/g,'":"') + '"}',
                        function(key, value) { return key===""?value:decodeURIComponent(value) }):{}
                    for (var key in search) {
                        this.params[key] = search[key];
                    }
                }
            },
            //Carrega os filtros disponiveis (linkado com o filtro do jhonatan)
            loadFilters: function () {
                this.$http.get(this.apiFiltros)
                    .then(function (resp) {
                        if(typeof resp.body == 'object'){
                            this.filtros = resp.body;
                        }
                })
            },
            //Faz a requisição dos dados e também funciona como callback do generic pagination
            loadData: function () {
                this.getParametersUrl();
                this.params.paginate = this.pagination.per_page;
                this.params.page = this.pagination.current_page;
                this.success = '';
                this.error = '';
                if(this.apiUrl){
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
                        if(this.actions.quantidade != undefined){
                            for (var j in this.dados) {
                               this.quant[j] = this.dados[j].quantidade_compra;
                            }
                        }
                        //Para animação loader
                        stopLoading();
                    });
                }
            },
        },
        created: function () {
            //Inicia Animação
            startLoading();
            //Bind dos filtros
            $('body').on('change','.filter_added',function () {
                this.loadData();
            }.bind(this));
            $('body').on('keyup','.filter_added',function () {
                this.loadData();
            }.bind(this));

            //Inicia mètodos principais
            this.loadFilters();
            this.loadData();
        }
    }
</script>

<style>
</style>