<template>
    <div>
        <table class="element-border">
            <thead v-bind:class="[titleColor]">
            <tr><th colspan="3" >{{title}}</th></tr>
            </thead>
            <tbody>
            <tr v-if="dados.length > 0" v-for="dado in dados">
                <td v-for="chave in chaves" v-on:click="goToDetail(dado['id'])">
                    <span> {{dado[chave]}}</span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    export default{
        props:{
            apiUrl:'',
            title: '',
            titleColor: '',
            type: ''
        },
        data: function () {
            return{
                dados:[],
                chaves:[],
                apiGet: ''
            }
        },
        methods: {
            goToDetail:function (id) {
                window.location.href = "";
            },
            getHeader:function () {
                if(this.dados.length >0){
                    this.chaves = Object.keys(this.dados[0]);
                }
            },
            loadData:function () {
                this.$http.get(this.apiGet,{
                    params:{ type: this.type}
                }).then(function (resp){
                    this.dados = resp.data;
                    this.getHeader();
                });
            }
        },
        created:function () {
            this.loadData();
        }
    }
</script>
<style>
    .element-border{
        width: 100%;
        border: solid 1px #dddddd;
    }
    .element-border > .head-grey {
        background-color: #9b9b9b;
    }
    .element-border > .head-red{
        background-color: #eb0000;
    }
    .element-border > .head-green{
        background-color: #7ed321;
    }
    .element-border > thead > tr > th{
        padding: 10px 0px 10px 0px;
        color: #f5f5f5;
        font-family: Raleway;
        font-weight: bold;
        text-align: center;
    }
    .element-border > tbody{
        background-color: white;
    }
    .element-border > tbody > tr {
        cursor:pointer;
        height: 50px;
        border-bottom: solid 1px #dddddd;
    }
    .element-border > tbody > tr > td {
        text-align: center;
    }
    .element-border > tbody > tr > td >span{
        color: #474747;
    }
    .element-border > tbody > tr > td:first-child >span {
        color: #4a90e2;
        padding: 5px 10px 5px 10px;
        border-right: solid 1px #dddddd;
    }
    .element-border > tbody > tr > td:last-child > span{
        padding: 5px 10px 5px 10px;
        border-left: solid 1px #dddddd;
    }
</style>