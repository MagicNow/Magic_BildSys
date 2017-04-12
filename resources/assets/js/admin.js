function startLoading(){
    if(!$('.loader').length){
        $('body').append('<div class="loader"></div>');
    }
}

function stopLoading(){
    if($('.loader').length) {
        $('.loader').fadeToggle(function () {
            $(this).remove();
        });
    }
}

$(function () {
    $(".select2").select2({
        theme:'bootstrap',

        placeholder:"-",
        language: "pt-BR",
    });

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
    verifyQueryString();
    
    $('.money').mask('0.000.000.000.000,00', {reverse: true});
});
var oTable = null;

function addFilters(query_string) {
    var cb_filter = $('.cb_filter');
    var cb_filter_label = $('.cb_filter_label');
    var block_fields = $('#block_fields');
    var filters_add = false;
    var msg = '';

    if(!query_string){
        query_string = [];
    }
    $('.filter_added').remove();

    block_fields.addClass('thumbnail').append('\
    <div class="col-md-12 page-header filter_added" id="filters_add">\
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

                block_fields.append('\
                <div class="row form-group col-md-12 filter_added">\
                    <div class="col-md-3">\
                    <label>' + cb_filter_label[i].innerHTML + '</label>\
                        <input v-model="filtrolist" type="number" id="' + cb_filter[i].value + '" class="form-control filters" onkeyup="addFilterFields(' + row_integer + ', this.value, \'integer\', this.id)">\
                    </div>\
                    <div class="col-md-3" style="margin-top: 25px;">\
                        <input v-model="filtrolist" type="number" id="' + cb_filter[i].value + '_final" name="' + cb_filter[i].value + '" class="form-control filters select_integer_' + cb_filter[i].value.split('-')[0] + '" onkeyup="addFilterFields(' + row_integer + ', this.value, \'integer\', this.name)">\
                    </div>\
                    <div class="col-md-6" style="margin-top: 25px;">\
                        <select v-model="filtrolist" class="form-control filters" id="' + cb_filter[i].value + '_option" name="' + cb_filter[i].value + '" onchange="filterFieldInteger(this.value, ' + what + '); addFilterFields(' + row_integer + ', this.value, \'integer\', this.name)">\
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

                var value_session_integer = query_string[cb_filter[i].value] ? query_string[cb_filter[i].value] : '';
                var value_session_integer_final = query_string[cb_filter[i].value+'_final'] ? query_string[cb_filter[i].value+'_final'] : '';
                var value_session_integer_option = query_string[cb_filter[i].value+'_option'] ? query_string[cb_filter[i].value+'_option'] : 'between';

                $('#'+cb_filter[i].value).val(value_session_integer);
                $('#'+cb_filter[i].value+'_final').val(value_session_integer_final);
                $('#'+cb_filter[i].value+'_option').val(value_session_integer_option);

                if(value_session_integer_option == 'between'){
                    $('#'+cb_filter[i].value+'_final').css('display', '');
                }else{
                    $('#'+cb_filter[i].value+'_final').css('display', 'none');
                }
                
                var value_integer_option = document.getElementById(cb_filter[i].value + '_option').options[document.getElementById(cb_filter[i].value + '_option').selectedIndex].text;
                var value_integer_initial = $('#' + cb_filter[i].value).val();
                var value_integer_final = $('#' + cb_filter[i].value + '_final').val();

                if (value_integer_option == 'Entre') {
                    msg = value_integer_option + ' ' + value_integer_initial + ' e ' + value_integer_final;
                } else {
                    msg = value_integer_option + ' ' + value_integer_initial;
                }

                $('#block_fields_minimize').append('<label class="filter_added">' + cb_filter_label[i].innerHTML.replace(/\s+$/, '') + ':</label><span id="row_' + cb_filter[i].value + '" class="filter_added"> ' + msg + ' </span>');
                filters_add = true;
            } else if (cb_filter[i].value.split('-')[1] == 'boolean') {
                var row_boolean = "'row_" + cb_filter[i].value + "'";

                block_fields.append('\
                <div class="form-group col-md-6 filter_added" style="width: 48.8%;">\
                    <label>' + cb_filter_label[i].innerHTML + '</label>\
                    <select v-model="filtrolist" class="form-control filters" id="' + cb_filter[i].value + '" onchange="addFilterFields(' + row_boolean + ', this.value, \'boolean\', this.id)">\
                        <option value="1">Sim</option>\
                        <option value="0">Não</option>\
                    </select>\
                </div>\
            ');

                var value_session_boolean = query_string[cb_filter[i].value] ? query_string[cb_filter[i].value] : 1;

                $('#'+cb_filter[i].value).val(value_session_boolean);

                $('#block_fields_minimize').append('<label class="filter_added">' + cb_filter_label[i].innerHTML.replace(/\s+$/, '') + ':</label><span id="row_' + cb_filter[i].value + '" class="filter_added"> ' + document.getElementById(cb_filter[i].value).options[document.getElementById(cb_filter[i].value).selectedIndex].text + ' </span>');
                filters_add = true;

            } else if (cb_filter[i].value.split('-')[1] == 'foreign_key') {
                var label = cb_filter_label[i].innerHTML;
                var value = cb_filter[i].value;
                var row_foreign_key = "'row_" + cb_filter[i].value + "'";

                foreign(label, value, row_foreign_key, cb_filter[i], query_string, block_fields);
                filters_add = true;

            } else if (cb_filter[i].value.split('-')[1] == 'date') {
                date = new Date();
                day = ("0" + (date.getDate())).slice(-2);
                month = ("0" + (date.getMonth() + 1)).slice(-2);
                year = date.getFullYear();
                date_actual = year + '-' + month + '-' + day;

                var row_date = "'row_" + cb_filter[i].value + "'";

                block_fields.append('\
                <div class="row form-group col-md-12 filter_added">\
                    <div class="col-md-6">\
                        <label>' + cb_filter_label[i].innerHTML + '</label>\
                        <input v-model="filtrolist" type="date" value="' + date_actual + '" id="' + cb_filter[i].value + '_initial" name="' + cb_filter[i].value + '" class="form-control filters" onchange="addFilterFields(' + row_date + ', this.value, \'date\', this.name)">\
                    </div>\
                    <div class="col-md-6" style="margin-top: 25px;">\
                        <input v-model="filtrolist" type="date" value="' + date_actual + '" id="' + cb_filter[i].value + '_final" name="' + cb_filter[i].value + '" class="form-control filters" onchange="addFilterFields(' + row_date + ', this.value, \'date\', this.name)">\
                    </div>\
                </div>\
            ');

                var value_session_date_initial = query_string[cb_filter[i].value+'_initial'] ? query_string[cb_filter[i].value+'_initial'] : date_actual;
                var value_session_date_final = query_string[cb_filter[i].value+'_final'] ? query_string[cb_filter[i].value+'_final'] : date_actual;

                $('#'+cb_filter[i].value+'_initial').val(value_session_date_initial);
                $('#'+cb_filter[i].value+'_final').val(value_session_date_final);
                
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

                $('#block_fields_minimize').append('<label class="filter_added">' + cb_filter_label[i].innerHTML.replace(/\s+$/, '') + ':</label><span id="row_' + cb_filter[i].value + '" class="filter_added"> ' + msg + ' </span>');
                filters_add = true;

            } else {
                var row_string = "'row_" + cb_filter[i].value + "'";
                
                block_fields.append('\
                <div class="row form-group col-md-12 filter_added">\
                    <div class="col-md-6">\
                        <label>' + cb_filter_label[i].innerHTML + '</label>\
                        <input v-model="filtrolist" type="text" id="' + cb_filter[i].value + '" class="form-control filters" onkeyup="addFilterFields(' + row_string + ', this.value, \'string\')">\
                    </div>\
                    <div class="col-md-6" style="margin-top: 25px;">\
                        <select v-model="filtrolist" class="form-control filters" id="' + cb_filter[i].value + '_option" onchange="addFilterFields()">\
                            <option value="between">Entre</option>\
                            <option value="start">Começa com</option>\
                            <option value="end">Termina com</option>\
                        </select>\
                    </div>\
                </div>\
            ');

                var value_session_string = query_string[cb_filter[i].value] ? query_string[cb_filter[i].value] : '';
                var value_session_string_option = query_string[cb_filter[i].value+'_option'] ? query_string[cb_filter[i].value+'_option'] : 'between';
                
                $('#'+cb_filter[i].value).val(value_session_string);
                $('#'+cb_filter[i].value+'_option').val(value_session_string_option);

                $('#block_fields_minimize').append('<label class="filter_added">' + cb_filter_label[i].innerHTML.replace(/\s+$/, '') + ':</label><span id="row_' + cb_filter[i].value + '" class="filter_added"> ' + value_session_string + ' </span>');
                filters_add = true;
            }
        }
    }

    if(!filters_add){
        $('#filters_add').remove();
        block_fields.removeClass('thumbnail');
        history.pushState("", document.title, '' + window.location.href.split("?")[0]);
    }

    addFilterFields();
    addQuery();
}

function addFilterFields(target_id, value, type, element_id) {
    var msg = '';

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
    addQuery();
}

function filterFieldInteger(value, what){
    if(value == 'between'){
        $('.'+what).css('display', '').val('');
    }else{
        $('.'+what).css('display', 'none').val('');
    }
    addFilterFields();
}


function minimizeFilters() {
    $('#block_fields_thumbnail').css('display', '');
    $('#block_fields').toggle('slow');
}

function maximizeFilters() {
    $('#block_fields_thumbnail').css('display', 'none');
    $('#block_fields').toggle('slow');
}

function verifyQueryString() {
    var result = {}, keyValuePairs = location.search.slice(1).split("&");
    keyValuePairs.forEach(function(keyValuePair) {
        keyValuePair = keyValuePair.split('=');
        result[decodeURIComponent(keyValuePair[0])] = decodeURIComponent(keyValuePair[1]) || '';
    });

    if(Object.keys(result).length){
        $.each(result, function (index, value) {
            $('#check_'+index).prop('checked', true).parent().addClass('checked');
            $('#check_'+index.replace('_initial', '')).prop('checked', true).parent().addClass('checked');
        });
        addFilters(result);
    }
}

function foreign(label, value, row_foreign_key, cb_filter_i, query_string, block_fields){
    $.ajax({
        url: "/admin/getForeignKey",
        data: {
            foreign_key: cb_filter_i.value.split('-')[0],
            model: cb_filter_i.value.split('-')[2],
            field_value: cb_filter_i.value.split('-')[3],
            field_key: cb_filter_i.value.split('-')[4]
        }
    }).done(function (json) {
        if (json.success == true && json.foreign_key) {
            var options = '';
            $.each(json.foreign_key, function (index, value) {
                options += '<option value="' + index + '">' + value + '</option>';
            });

            block_fields.append('\
                        <div class="form-group col-md-6 filter_added" style="width: 48.8%;">\
                            <label>' + label + '</label>\
                            <select v-model="filtrolist" class="form-control filters" id="' + value + '" onchange="addFilterFields(' + row_foreign_key + ', this.value, \'foreign_key\', this.id)">\
                                <option value="">Selecione</option>' + options + '\
                            </select>\
                        </div>\
                    ');

            var value_session_foreign_key = query_string[value] ? query_string[value] : '';

            $('#'+value).val(value_session_foreign_key);

            $('#block_fields_minimize').append('<label class="filter_added">' + label.replace(/\s+$/, '') + ':</label><span id="row_' + value + '" class="filter_added"> ' + document.getElementById(value).options[document.getElementById(value).selectedIndex].text + ' </span>');
            filters_add = true;
        }
        addQuery();
    });
}

function addQuery() {
    var filters_fields = $('.filters');
    var filters = '';
    
    for( i=0; i < filters_fields.length; i++ ) {
        filters += ''+filters_fields[i].id+'='+filters_fields[i].value+'&';
    }

    filters = '?'+filters;
    filters = filters.substring(0,(filters.length - 1));

    history.pushState("", document.title, '' + filters);
}