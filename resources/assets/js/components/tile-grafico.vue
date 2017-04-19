<template>
    <div>
        <table class="element-grafico">
            <thead>
            <tr><th colspan="3">Ordens de Compra</th></tr>
            </thead>
            <tbody>
            <tr v-if="mydatasets[0].data[0] != undefined">

                <chartjs-bar style="padding: 15px" :height=300 :option="myoption" :labels="mylabels" :datasets="mydatasets" ></chartjs-bar>
            </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    export default{
        props:{
            type: ''
        },
        data: function () {
            return{
                reprovados: 0,
                aprovados: 0,
                emAprovacao: 0,
                apiGet: 'compras/jsonOrdemCompraDashboard',
                mylabels: ["Ordem de Compra"],

                mydatasets:[{
                    label: "Reprovadas",
                    backgroundColor: [
                        'rgba(255,0,0,1)'
                    ],
                    borderColor: [
                        'rgb(249,141,0,1)',
                    ],
                    borderWidth: 1,
                    data: [],
                }],
                myoption: {
                    responsive:true,
                    maintainAspectRatio:true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                // Create scientific notation labels
                                min: 0,
                            }
                        }]
                    },
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            boxWidth:10
                        }
                    }
                },

            }
        },
        watch: {
            mydatasets: function (val) {
                console.log(val);
            },
        },
        methods: {
            getReprovados: function () {
                this.$http.get(this.apiGet,{
                    params: {type: 4}
                }).then(function(resp){
                    console.log(this.mydatasets);
                    this.mydatasets[0].data[0] = 10;
                    console.log(this.mydatasets);
                });
            },
            getEmAprovacao: function () {
                this.$http.get(this.apiGet,{
                    params: {type: 3}
                }).then(function(resp){
                    this.emAprovacao = resp.data.length;
//                    this.mydatasets.push({
//                        label: "Em Aprovação",
//                        backgroundColor: [
//                            'rgba(249,141,0,1)'
//                        ],
//                        borderColor: [
//                            'rgb(249,141,0,1)',
//                        ],
//                        borderWidth: 1,
//                        data: [this.emAprovacao],
//                    });
                });
            },
            getAprovados: function () {
                this.$http.get(this.apiGet,{
                    params: {type: 5}
                }).then(function(resp){
                    this.aprovados = resp.data.length;
//                    this.mydatasets.push({
//                        label: "Aprovadas",
//                        backgroundColor: [
//                            'rgba(126, 211, 33,1)'
//                        ],
//                        borderColor: [
//                            'rgb(126,211,33,1)',
//                        ],
//                        borderWidth: 1,
//                        data: [this.aprovados],
//                    });
                });
            }
        },
        ready: function () {
            this.getReprovados();
            this.getEmAprovacao();
            this.getAprovados();
        },
        mounted:function () {

        }
    }
</script>
<style>
    .element-grafico{
        width: 100%;
        border: solid 1px #dddddd;
    }
    .element-grafico > thead {
        padding: 5px 0px 5px 0px;
        background-color: #474747;
    }
    .element-grafico > thead > tr > th{
        padding: 10px 0px 10px 0px;
        color: #f5f5f5;
        font-weight: bold;
        text-align: center;
    }
    .element-grafico > tbody{
        background-color: white;
    }
</style>