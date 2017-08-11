{!! Form::hidden('chave', $templateEmail->chave, ['class' => 'form-control']) !!}


        <!-- Nome Field -->
<div class="form-group col-sm-12">
    {!! Form::label('nome', 'Nome:') !!}
    {!! Form::text('nome', null, ['class' => 'form-control']) !!}
</div>

<!-- Texto Field -->
<div class="form-group col-sm-12">
    {!! Form::label('template', 'Template:') !!}

            <!-- Info modal -->

    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                       aria-expanded="false" aria-controls="collapseOne">
                        <i class="fa fa-info-circle" aria-hidden="true"></i> Tags disponíveis para o template
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">

                    <ul class="list-group" id="campos_extras">
                        <?php
                        $campos_extras_count = 0;
                        $campos_extras = [];
                        if(isset($contratoTemplate)){
                            if( strlen(trim($contratoTemplate->campos_extras)) ){
                                $campos_extras = json_decode($contratoTemplate->campos_extras);
                            }
                        }
                        ?>
                        @if(count($campos_extras))
                            @foreach($campos_extras as $campo_extra)
                                <?php $campos_extras_count++; ?>
                                <li id="campos_extras{{ $campos_extras_count }}" class="list-group-item">
                                    <div class="row">
                                        <span class="col-md-1 text-right">
                                            <label>Nome:</label>
                                        </span>
                                        <span class="col-md-3">
                                            <input type="hidden" name="campos_extras[{{ $campos_extras_count }}][tag]"
                                                   id="campo_extra_tag{{ $campos_extras_count }}"
                                                   required="required" value="{{ $campo_extra->tag }}">
                                            <input type="text" class="form-control" value="{{ $campo_extra->nome }}"
                                                   name="campos_extras[{{ $campos_extras_count }}][nome]"
                                                   onkeyup="slugAndShow(1, this.value);" placeholder="Nome do Campo">
                                        </span>
                                        <span class="col-md-1 text-right">
                                            <label>Tipo:</label>
                                        </span>
                                        <span class="col-md-2">
                                            <select name="campos_extras[{{ $campos_extras_count }}][tipo]"
                                                    class="form-control select2" required="required">
                                                <option {{ $campo_extra->tipo == 'texto'? 'selected="selected"':'' }}
                                                        value="texto">Texto</option>
                                                <option  {{ $campo_extra->tipo == 'numero'? 'selected="selected"':'' }}
                                                         value="numero">Número</option>
                                                <option  {{ $campo_extra->tipo == 'data'? 'selected="selected"':'' }}
                                                         value="data">Data</option>
                                            </select>
                                        </span>
                                        <span class="col-md-1 text-right">
                                            <label>Uso:</label>
                                        </span>
                                        <span class="col-md-3">
                                            <span id="campo_extra{{ $campos_extras_count }}"
                                                  class="label label-primary selecionavel">{{ $campo_extra->tag }}</span>
                                        </span>
                                        <span class="col-md-1 text-right">
                                            <button type="button" class="btn btn-danger btn-flat"
                                                    title="remover" onclick="removeTag({{ $campos_extras_count }});">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        @endif

                    </ul>

                    <h5>Para que ao gerar um email os dados reais sejam carregados, é necessário usar estas tags onde
                        o sistema irá substituir automaticamente.
                    </h5>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">TAGS</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [RAZAO_SOCIAL_OBRA]
                                            </span> &nbsp;
                                            Razão social
                                        </li>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>
    {!! Form::textarea('template', null, ['class' => 'form-control htmleditor']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('templateEmails.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>
