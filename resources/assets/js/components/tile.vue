<template>
    <div>
        <table class="element-border">
            <thead>
                <tr><th colspan="3">Últimas Criadas</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td><span>34232</span></td>
                    <td><span>Fábio Moreno</span></td>
                    <td><span>Fiva Home resort</span></td>
                </tr>
                <tr>
                    <td><span>34232</span></td>
                    <td><span>Fábio Moreno</span></td>
                    <td><span>Fiva Home resort</span></td>
                </tr>
                <tr>
                    <td><span>34232</span></td>
                    <td><span>Fábio Moreno</span></td>
                    <td><span>Fiva Home resort</span></td>
                </tr>
                <tr>
                    <td><span>34232</span></td>
                    <td><span>Fábio Moreno</span></td>
                    <td><span>Fiva Home resort</span></td>
                </tr>
                <tr>
                    <td><span>34232</span></td>
                    <td><span>Fábio Moreno</span></td>
                    <td><span>Fiva Home resort</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    export default{
        props:{
            title: '',
            titleColor: '',
        },
        data: function () {
            return{

            }
        },
        methods: {

        },
        created:function () {

        }
    }
</script>
<style>
    .element-border{
        width: 100%;
        border: solid 1px #dddddd;
    }
    .element-border > thead {

        background-color: #9b9b9b;
    }
    .element-border > thead > tr > th{
        padding: 10px 0px 10px 0px;
        color: #f5f5f5;
        font-weight: bold;
        text-align: center;
    }
    .element-border > tbody{
        background-color: white;
    }
    .element-border > tbody > tr {
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