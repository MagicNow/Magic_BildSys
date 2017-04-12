<div id="block_fields" class="col-md-12" style="margin-bottom: 20px"></div>
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
                @foreach($filters as $field => $filter)
                    <p>
                        <input class="cb_filter" type="checkbox" id="check_{{$field}}" value="{{$field}}"/>
                        <label for="check_{{$field}}" style="cursor: pointer;" class="cb_filter_label">
                            {{$filter}}
                        </label>
                    </p>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="addFilters()">Adicionar</button>
            </div>
        </div>
    </div>
</div>
<ol class="breadcrumb" style="margin-bottom: 0px;">
    <li class="col-md-6">
        <a href="#"><i class="fa fa-search" aria-hidden="true"></i> Procurar ordem</a>
    </li>
    <li>
        <a href="#">Hoje</a>
    </li>
    <li>
        <a href="#">7 dias</a>
    </li>
    <li>
        <a href="#">15 dias</a>
    </li>
    <li>
        <a href="#">30 dias</a>
    </li>
    <li>
        <a href="#">Outro periodo</a>
    </li>
    <li>
        <a href="" data-toggle="modal" data-target="#myModal" class="grey">
            Adicionar filtros <i class="fa fa-filter" aria-hidden="true"></i>
        </a>
    </li>
</ol>