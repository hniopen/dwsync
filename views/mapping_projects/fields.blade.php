<!-- Project1 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('project1', 'Project1:') !!}
    {{--{!! Form::number('project1', null, ['class' => 'form-control']) !!}--}}
    {!! Form::select('project1', $projects,null, ['class'=> 'form-control'])  !!}
</div>

<!-- Project2 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('project2', 'Project2:') !!}
{{--    {!! Form::number('project2', null, ['class' => 'form-control']) !!}--}}
    {!! Form::select('project2', $projects,null, ['class'=> 'form-control'])  !!}
</div>

<!-- Isactive Field -->
<div class="form-group col-sm-12">
    {!! Form::label('isActive', 'Isactive:') !!}
    <label class="radio-inline">
        {!! Form::radio('isActive', "0", null) !!} No
    </label>

    <label class="radio-inline">
        {!! Form::radio('isActive', "1", null) !!} Yes
    </label>

</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('dwsync.mappingProjects.index') !!}" class="btn btn-default">Cancel</a>
</div>


