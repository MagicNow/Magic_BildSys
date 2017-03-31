/**
 * Created by rafael on 16/09/16.
 */
$(function () {
    $('input').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
        increaseArea: '20%' // optional
    });
    $('.colorbox').colorbox({ transition:"fade", width:"95%", height:"95%"});
    $('form').submit(function (evento) {
        $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
    });
});

$(function () {
    putSession(); 
});
var oTable = null;

function putSession(filters) {
    var initial_date = $('#initial_date').val();
    var final_date = $('#final_date').val();
    var filters_json = null;
    if(filters){
        filters_json = JSON.parse(JSON.stringify(eval("(" + filters + ")")));
    }
    
    $.ajax({
        url: "/admin/putsession",
        data: {
            'initial_date' : initial_date,
            'final_date' : final_date,
            'filters' : filters_json
        }
    }).done(function (json) {
        if(json.success){
            $("#dataTableBuilder").DataTable().draw();
        }
    });
}

function addFilters() {
    var cb_filter = $('.cb_filter');
    var cb_filter_label = $('.cb_filter_label');
    var filters = [];
    for( i=0; i < cb_filter.length; i++ ) {

        if(cb_filter[i].value.split('|')[1] == 'integer'){
            var what = "'"+cb_filter[i].value+'_final'+"'";

            $('#block_fields').append('\
                <div class="row form-group col-md-12">\
                    <div class="col-md-3">\
                    <label>'+cb_filter_label[i].innerHTML+'</label>\
                        <input type="number" id="'+cb_filter[i].value+'_initial" class="form-control filters">\
                    </div>\
                    <div class="col-md-3" style="margin-top: 25px;">\
                        <input type="number" id="'+cb_filter[i].value+'_final" class="form-control filters">\
                    </div>\
                    <div class="col-md-6" style="margin-top: 25px;">\
                        <select class="form-control filters" name="'+cb_filter[i].value+'_option" onchange="filterFieldInteger(this.value, '+what+')">\
                            <option value="between">Entre</option>\
                            <option value="bigger">Maior que</option>\
                            <option value="smaller">Menor que</option>\
                            <option value="bigger_equal">Maior ou igual que</option>\
                            <option value="smaller_equal">Menor ou igual que</option>\
                            <option value="equal">Menor ou igual que</option>\
                        </select>\
                    </div>\
                </div>\
            ');
        }else if(cb_filter[i].value.split('|')[1] == 'boolean'){
            $('#block_fields').append('\
                <div class="form-group col-md-6" style="width: 48.8%;">\
                    <label>'+cb_filter_label[i].innerHTML+'</label>\
                    <select class="form-control filters" id="'+cb_filter[i].value+'">\
                        <option value="1">Sim</option>\
                        <option value="0">Não</option>\
                    </select>\
                </div>\
            ');
        }else if(cb_filter[i].value.split('|')[1] == 'foreign_key'){
            var label = cb_filter_label[i].innerHTML;
            var value = cb_filter_label[i].value;
            $.ajax({
                url: "/admin/getForeignKey",
                data: {
                    foreign_key: cb_filter[i].value.split('|')[0],
                    model: cb_filter[i].value.split('|')[2],
                    field_value: cb_filter[i].value.split('|')[3],
                    field_key: cb_filter[i].value.split('|')[4]
                }
            }).done(function (json) {
                if(json.success == true && json.foreign_key){
                    var options = '';
                    $.each(json.foreign_key, function( index, value ) {
                        options += '<option value="'+index+'">'+value+'</option>';
                    });
                    
                    $('#block_fields').append('\
                        <div class="form-group col-md-6" style="width: 48.8%;">\
                            <label>'+label+'</label>\
                            <select class="form-control filters" id="'+value+'">\
                                <option value="">Selecione</option>' + options + '\
                            </select>\
                        </div>\
                    ');
                }
            });
        }else if(cb_filter[i].value.split('|')[1] == 'date'){
            date = new Date();
            day = ("0" + (date.getDate())).slice(-2);
            month = ("0" + (date.getMonth() + 1)).slice(-2);
            year = date.getFullYear();
            date_actual = year+'-'+month+'-'+day;
                
            $('#block_fields').append('\
                <div class="row form-group col-md-12">\
                    <div class="col-md-6">\
                        <label>'+cb_filter_label[i].innerHTML+'</label>\
                        <input type="date" value="'+date_actual+'" id="'+cb_filter[i].value+'_initial" class="form-control filters">\
                    </div>\
                    <div class="col-md-6" style="margin-top: 25px;">\
                        <input type="date" value="'+date_actual+'" id="'+cb_filter[i].value+'_final" class="form-control filters">\
                    </div>\
                </div>\
            ');
        }else{
            $('#block_fields').append('\
                <div class="row form-group col-md-12">\
                    <div class="col-md-6">\
                        <label>'+cb_filter_label[i].innerHTML+'</label>\
                        <input type="text" id="'+cb_filter[i].value+'" class="form-control filters" onkeyup="addFilterFields()">\
                    </div>\
                    <div class="col-md-6" style="margin-top: 25px;">\
                        <select class="form-control filters" id="'+cb_filter[i].value+'_option" onchange="addFilterFields()">\
                            <option value="between">Entre</option>\
                            <option value="start">Começa com</option>\
                            <option value="end">Termina com</option>\
                        </select>\
                    </div>\
                </div>\
            ');
        }
    }
    addFilterFields();
}

function filterFieldInteger(value, what){
    if(value == 'between'){
        $('#'+what).css('display', '').val('');
    }else{
        $('#'+what).css('display', 'none').val('');
    }
}

function addFilterFields() {
    var filters_fields = $('.filters');
    var filters = '';
    
    for( i=0; i < filters_fields.length; i++ ) {
        filters += '"'+filters_fields[i].id+'" : "'+filters_fields[i].value+'",';
    }

    filters = '{'+filters+'}';
    
    putSession(filters);
}