<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password', 'Password:') !!}
    {!! Form::password('password', ['class' => 'form-control']) !!}
</div>


@if(!isset($user))
<div class="form-group col-sm-6">
  {!! Form::label('roles', 'Perfil') !!}
  {!! Form::select('roles[]', $roles, null, ['class' => 'select2 form-control', 'multiple']) !!}
</div>
@endif

<!-- Ativo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('active', 'Ativo') !!}
    <div class="form-control">
        {!! Form::checkbox('active', 1,null, [ 'id'=>'active']) !!}
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit( ucfirst( trans('common.save') ), ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('users.index') !!}" class="btn btn-default">{{ ucfirst( trans('common.cancel') )}}</a>
</div>
