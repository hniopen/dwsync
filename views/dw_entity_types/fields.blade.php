<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type', 'Type:') !!}
    {!! Form::select('type', ['Q' => 'Questionnaire', 'I' => 'Idnr', 'DS' => 'Datasender'], null, ['class' => 'form-control']) !!}
</div>

<!-- Comment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('comment', 'Comment:') !!}
    {!! Form::text('comment', null, ['class' => 'form-control']) !!}
</div>

<!-- Apiurl Field -->
<div class="form-group col-sm-6">
    {!! Form::label('apiUrl', 'Apiurl:') !!}
    {!! Form::text('apiUrl', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('dwsync.dwEntityTypes.index') !!}" class="btn btn-default">Cancel</a>
</div>
