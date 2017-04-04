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
    $('#block_fields').addClass('thumbnail').append('\
    <div class="col-md-12 page-header">\
        <div class="col-md-11">\
            <h2>Filtros</h2>\
        </div>\
        <div class="col-md-1" style="padding-top: 15px;">\
            <button type="button" class="btn btn-default" onclick="minimizeFilters();">\
                    <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>\
            </button>\
        </div>\
    </div>\
    ');
    
    for( i=0; i < cb_filter.length; i++ ) {
        if(cb_filter[i].checked) {
            if (cb_filter[i].value.split('-')[1] == 'integer') {
                var what = "'select_integer_" + cb_filter[i].value.split('-')[0] + "'";
                var row_integer = "'row_" + cb_filter[i].value + "'";

                $('#block_fields').append('\
                <div class="row form-group col-md-12">\
                    <div class="col-md-3">\
                    <label>' + cb_filter_label[i].innerHTML + '</label>\
                        <input type="number" id="' + cb_filter[i].value + '" class="form-control filters" onkeyup="addFilterFields(' + row_integer + ', this.value, \'integer\', this.id)">\
                    </div>\
                    <div class="col-md-3" style="margin-top: 25px;">\
                        <input type="number" id="' + cb_filter[i].value + '_final" name="' + cb_filter[i].value + '" class="form-control filters select_integer_' + cb_filter[i].value.split('-')[0] + '" onkeyup="addFilterFields(' + row_integer + ', this.value, \'integer\', this.name)">\
                    </div>\
                    <div class="col-md-6" style="margin-top: 25px;">\
                        <select class="form-control filters" id="' + cb_filter[i].value + '_option" name="' + cb_filter[i].value + '" onchange="filterFieldInteger(this.value, ' + what + '); addFilterFields(' + row_integer + ', this.value, \'integer\', this.name)">\
                            <option value="between">Entre</option>\
                            <option value="bigger">Maior que</option>\
                            <option value="smaller">Menor que</option>\
                            <option value="bigger_equal">Maior ou igual que</option>\
                            <option value="smaller_equal">Menor ou igual que</option>\
                            <option value="equal">Igual</option>\
                        </select>\
                    </div>\
                </div>\
            ');

                var value_integer_option = document.getElementById(cb_filter[i].value + '_option').options[document.getElementById(cb_filter[i].value + '_option').selectedIndex].text;
                var value_integer_initial = $('#' + cb_filter[i].value).val();
                var value_integer_final = $('#' + cb_filter[i].value + '_final').val();

                if (value_integer_option == 'Entre') {
                    msg = value_integer_option + ' ' + value_integer_initial + ' e ' + value_integer_final;
                } else {
                    msg = value_integer_option + ' ' + value_integer_initial;
                }

                $('#block_fields_minimize').append('<label>' + cb_filter_label[i].innerHTML.replace(/\s+$/, '') + ':</label><span  id="row_' + cb_filter[i].value + '"> ' + msg + ' </span>');
            } else if (cb_filter[i].value.split('-')[1] == 'boolean') {
                var row_boolean = "'row_" + cb_filter[i].value + "'";

                $('#block_fields').append('\
                <div class="form-group col-md-6" style="width: 48.8%;">\
                    <label>' + cb_filter_label[i].innerHTML + '</label>\
                    <select class="form-control filters" id="' + cb_filter[i].value + '" onchange="addFilterFields(' + row_boolean + ', this.value, \'boolean\', this.id)">\
                        <option value="1">Sim</option>\
                        <option value="0">Não</option>\
                    </select>\
                </div>\
            ');

                $('#block_fields_minimize').append('<label>' + cb_filter_label[i].innerHTML.replace(/\s+$/, '') + ':</label><span id="row_' + cb_filter[i].value + '"> ' + document.getElementById(cb_filter[i].value).options[document.getElementById(cb_filter[i].value).selectedIndex].text + ' </span>');

            } else if (cb_filter[i].value.split('-')[1] == 'foreign_key') {
                var label = cb_filter_label[i].innerHTML;
                var value = cb_filter[i].value;
                var row_foreign_key = "'row_" + cb_filter[i].value + "'";

                $.ajax({
                    url: "/admin/getForeignKey",
                    data: {
                        foreign_key: cb_filter[i].value.split('-')[0],
                        model: cb_filter[i].value.split('-')[2],
                        field_value: cb_filter[i].value.split('-')[3],
                        field_key: cb_filter[i].value.split('-')[4]
                    }
                }).done(function (json) {
                    if (json.success == true && json.foreign_key) {
                        var options = '';
                        $.each(json.foreign_key, function (index, value) {
                            options += '<option value="' + index + '">' + value + '</option>';
                        });

                        $('#block_fields').append('\
                        <div class="form-group col-md-6" style="width: 48.8%;">\
                            <label>' + label + '</label>\
                            <select class="form-control filters" id="' + value + '" onchange="addFilterFields(' + row_foreign_key + ', this.value, \'foreign_key\', this.id)">\
                                <option value="">Selecione</option>' + options + '\
                            </select>\
                        </div>\
                    ');

                        $('#block_fields_minimize').append('<label>' + label.replace(/\s+$/, '') + ':</label><span id="row_' + value + '"> ' + document.getElementById(value).options[document.getElementById(value).selectedIndex].text + ' </span>');
                    }
                });

            } else if (cb_filter[i].value.split('-')[1] == 'date') {
                date = new Date();
                day = ("0" + (date.getDate())).slice(-2);
                month = ("0" + (date.getMonth() + 1)).slice(-2);
                year = date.getFullYear();
                date_actual = year + '-' + month + '-' + day;

                var row_date = "'row_" + cb_filter[i].value + "'";

                $('#block_fields').append('\
                <div class="row form-group col-md-12">\
                    <div class="col-md-6">\
                        <label>' + cb_filter_label[i].innerHTML + '</label>\
                        <input type="date" value="' + date_actual + '" id="' + cb_filter[i].value + '_initial" name="' + cb_filter[i].value + '" class="form-control filters" onchange="addFilterFields(' + row_date + ', this.value, \'date\', this.name)">\
                    </div>\
                    <div class="col-md-6" style="margin-top: 25px;">\
                        <input type="date" value="' + date_actual + '" id="' + cb_filter[i].value + '_final" name="' + cb_filter[i].value + '" class="form-control filters" onchange="addFilterFields(' + row_date + ', this.value, \'date\', this.name)">\
                    </div>\
                </div>\
            ');

                value_date_initial = new Date($('#' + cb_filter[i].value + '_initial').val().replace(/-/g, ','));
                value_day_initial = ("0" + (value_date_initial.getDate())).slice(-2);
                value_month_initial = ("0" + (value_date_initial.getMonth() + 1)).slice(-2);
                value_year_initial = value_date_initial.getFullYear();
                value_date_initial = value_day_initial + '/' + value_month_initial + '/' + value_year_initial;

                value_date_final = new Date($('#' + cb_filter[i].value + '_final').val().replace(/-/g, ','));
                value_day_final = ("0" + (value_date_final.getDate())).slice(-2);
                value_month_final = ("0" + (value_date_final.getMonth() + 1)).slice(-2);
                value_year_final = value_date_final.getFullYear();
                value_date_final = value_day_final + '/' + value_month_final + '/' + value_year_final;

                if (value_date_initial != value_date_final) {
                    msg = 'Entre ' + value_date_initial + ' e ' + value_date_final;
                } else {
                    msg = value_date_initial;
                }

                $('#block_fields_minimize').append('<label>' + cb_filter_label[i].innerHTML.replace(/\s+$/, '') + ':</label><span id="row_' + cb_filter[i].value + '"> ' + msg + ' </span>');

            } else {
                var row_string = "'row_" + cb_filter[i].value + "'";
                $('#block_fields').append('\
                <div class="row form-group col-md-12">\
                    <div class="col-md-6">\
                        <label>' + cb_filter_label[i].innerHTML + '</label>\
                        <input type="text" id="' + cb_filter[i].value + '" class="form-control filters" onkeyup="addFilterFields(' + row_string + ', this.value, \'string\')">\
                    </div>\
                    <div class="col-md-6" style="margin-top: 25px;">\
                        <select class="form-control filters" id="' + cb_filter[i].value + '_option" onchange="addFilterFields()">\
                            <option value="between">Entre</option>\
                            <option value="start">Começa com</option>\
                            <option value="end">Termina com</option>\
                        </select>\
                    </div>\
                </div>\
            ');

                var value_string = $('#' + cb_filter[i].value).val();

                $('#block_fields_minimize').append('<label>' + cb_filter_label[i].innerHTML.replace(/\s+$/, '') + ':</label><span id="row_' + cb_filter[i].value + '"> ' + value_string + ' </span>');
            }
        }
    }
    addFilterFields();
}

function filterFieldInteger(value, what){
    if(value == 'between'){
        $('.'+what).css('display', '').val('');
    }else{
        $('.'+what).css('display', 'none').val('');
    }
    addFilterFields();
}

function addFilterFields(target_id, value, type, element_id) {
    if(type == 'integer'){
        var value_integer_option = document.getElementById(element_id+'_option').options[document.getElementById(element_id+'_option').selectedIndex].text;
        var value_integer_initial = $('#'+element_id).val();
        var value_integer_final = $('#'+element_id+'_final').val();
        
        if(value_integer_option == 'Entre'){
            msg = value_integer_option + ' ' + value_integer_initial + ' e ' + value_integer_final;
        }else{
            msg = value_integer_option + ' ' + value_integer_initial;
        }
        $('#'+target_id).html(msg+' ');
    }else if(type == 'foreign_key'){
        element_value = document.getElementById(element_id).options[document.getElementById(element_id).selectedIndex].text;
        $('#'+target_id).html(element_value+' ');
    }else if(type == 'date'){
        value_date_initial = new Date($('#'+element_id+'_initial').val().replace(/-/g, ','));
        value_day_initial = ("0" + (value_date_initial.getDate())).slice(-2);
        value_month_initial = ("0" + (value_date_initial.getMonth() + 1)).slice(-2);
        value_year_initial = value_date_initial.getFullYear();
        value_date_initial = value_day_initial+'/'+value_month_initial+'/'+value_year_initial;

        value_date_final = new Date($('#'+element_id+'_final').val().replace(/-/g, ','));
        value_day_final = ("0" + (value_date_final.getDate())).slice(-2);
        value_month_final = ("0" + (value_date_final.getMonth() + 1)).slice(-2);
        value_year_final = value_date_final.getFullYear();
        value_date_final = value_day_final+'/'+value_month_final+'/'+value_year_final;
        
        if(value_date_initial != value_date_final){
            msg = 'Entre ' + value_date_initial + ' e ' + value_date_final;
        }else{
            msg = value_date_initial;
        }
        $('#'+target_id).html(msg+' ');
    }else if(type == 'boolean'){
        element_value = document.getElementById(element_id).options[document.getElementById(element_id).selectedIndex].text;
        $('#'+target_id).html(element_value+' ');
    }else{
        $('#'+target_id).html(value+' ');
    }
    
    var filters_fields = $('.filters');
    var filters = '';
    
    for( i=0; i < filters_fields.length; i++ ) {
        filters += '"'+filters_fields[i].id+'" : "'+filters_fields[i].value+'",';
    }

    filters = '{'+filters+'}';
    
    putSession(filters);
}

function minimizeFilters() {
    $('#block_fields_thumbnail').css('display', '');
    $('#block_fields').toggle('slow');
}

function maximizeFilters() {
    $('#block_fields_thumbnail').css('display', 'none');
    $('#block_fields').toggle('slow');
}