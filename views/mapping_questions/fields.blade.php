<!-- Mappingprojectid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('mappingProjectId', 'Mapping project:') !!}
    {!! Form::select('mappingProjectId', $mappingProjects,null, ['class'=> 'form-control'])  !!}
</div>

<!-- Question1 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('question1', 'Question1:') !!}
{{--    {!! Form::number('question1', null, ['class' => 'form-control']) !!}--}}
    {!! Form::select('question1', $questions,null, ['class'=> 'form-control'])  !!}
</div>

<!-- Question2 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('question2', 'Question2:') !!}
{{--    {!! Form::number('question2', null, ['class' => 'form-control']) !!}--}}
    {!! Form::select('question2', $questions,null, ['class'=> 'form-control'])  !!}
</div>

<!-- Functions Field -->
<div class="form-group col-sm-6">
    {!! Form::label('functions', 'Functions:') !!}
    {!! Form::text('functions', null, ['class' => 'form-control']) !!}
</div>

<!-- Arg1 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('arg1', 'Arg1:') !!}
    {!! Form::text('arg1', null, ['class' => 'form-control']) !!}
</div>

<!-- Arg2 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('arg2', 'Arg2:') !!}
    {!! Form::text('arg2', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('dwsync.mappingQuestions.index') !!}" class="btn btn-default">Cancel</a>
</div>
