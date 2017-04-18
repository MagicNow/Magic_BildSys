<template>
    <div>
        <table class="element-grafico">
            <thead>
            <tr><th colspan="3">Últimas Criadas</th></tr>
            </thead>
            <tbody>
            <tr>
                <chartjs-bar style="padding: 15px" :height=300 :option="myoption" :labels="mylabels" :datasets="mydatasets" ></chartjs-bar>
            </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    export default{
        props:{

        },
        data: function () {
            return{
                mylabels: ["Ordem de Compra"],

                mydatasets:[
                    {
                        label: "Reprovadas",
                        backgroundColor: [
                            'rgba(255,0,0,1)'
                        ],
                        borderColor: [
                            'rgb(249,141,0,1)',
                        ],
                        borderWidth: 1,
                        data: [200],
                    },
                    {
                        label: "Em Aprovação",
                        backgroundColor: [
                            'rgba(249,141,0,1)'
                        ],
                        borderColor: [
                            'rgb(249,141,0,1)',
                        ],
                        borderWidth: 1,
                        data: [600],
                    },
                    {
                        label: "Aprovadas",
                        backgroundColor: [
                            'rgba(126, 211, 33,1)'
                        ],
                        borderColor: [
                            'rgb(126,211,33,1)',
                        ],
                        borderWidth: 1,
                        data: [300],
                    },
                ],
                myoption: {
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
                        position: 'bottom'
                    }
                },

            }
        },
        methods: {

        },
        created:function () {

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
        background-color: #9b9b9b;
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