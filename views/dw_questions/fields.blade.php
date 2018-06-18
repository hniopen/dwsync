<!-- Id Field -->
{{--<div class="form-group col-sm-6">--}}
{{--{!! Form::label('id', 'Id:') !!}--}}
{{--{!! Form::number('id', null, ['class' => 'form-control']) !!}--}}
{{--</div>--}}

<!-- Projectid Field -->
@if(empty($selectedProject))
    <div class="form-group col-sm-6">
        {!! Form::label('projectId', 'Projectid:') !!}
        {!! Form::select('projectId', $dwProjectList, null, ['class' => 'form-control']) !!}
    </div>
@else
    {!! Form::hidden('projectId', $selectedProject->id, ['class' => 'form-control']) !!}
@endif

<!-- Order Field -->
<div class="form-group col-sm-6">
    {!! Form::label('order', 'Order:') !!}
    {!! Form::number('order', null, ['class' => 'form-control']) !!}
</div>

<!-- Xformquestionid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('xformQuestionId', 'Xformquestionid:') !!}
    @if(empty($item))
        {!! Form::text('xformQuestionId', null, ['class' => 'form-control']) !!}
    @else
        {!! Form::text('xformQuestionId', $item, ['class' => 'form-control', 'readonly']) !!}
    @endif
</div>

<!-- Questionid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('questionId', 'Questionid:') !!}
    {!! Form::text('questionId', null, ['class' => 'form-control']) !!}
</div>

<!-- Path Field -->
<div class="form-group col-sm-6">
    {!! Form::label('path', 'Path:') !!}
    {!! Form::text('path', null, ['class' => 'form-control']) !!}
</div>

<!-- Labeldefault Field -->
<div class="form-group col-sm-6">
    {!! Form::label('labelDefault', 'Labeldefault:') !!}
    {!! Form::text('labelDefault', null, ['class' => 'form-control']) !!}
</div>

<!-- Labelfr Field -->
<div class="form-group col-sm-6">
    {!! Form::label('labelFr', 'Labelfr:') !!}
    {!! Form::text('labelFr', null, ['class' => 'form-control']) !!}
</div>

<!-- Labelus Field -->
<div class="form-group col-sm-6">
    {!! Form::label('labelUs', 'Labelus:') !!}
    {!! Form::text('labelUs', null, ['class' => 'form-control']) !!}
</div>

<!-- Datatype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('dataType', 'Datatype:') !!}
    {!! Form::text('dataType', null, ['class' => 'form-control']) !!}
</div>

<!-- Dataformat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('dataFormat', 'Dataformat:') !!}
    {!! Form::text('dataFormat', null, ['class' => 'form-control']) !!}
</div>

<!-- Linkedidnr Field -->
<div class="form-group col-sm-6">
    {!! Form::label('linkedIdnr', 'Linkedidnr:') !!}
    {!! Form::select('linkedIdnr', $dwIdnrList, null, ['class' => 'form-control']) !!}
</div>

<!-- Periodtype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('periodType', 'Periodtype:') !!}
    {!! Form::select('periodType', ['' => 'Non period field', 'd' => 'Day', 'w' => 'Week', 'm' => 'Month', 'y' => 'Year', 'ym' => 'Year and Month', 'ymd' => 'Year-Month-Day'], null, ['class' => 'form-control']) !!}
</div>

<!-- Periodtypeformat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('periodTypeFormat', 'Periodtypeformat:') !!}
    {!! Form::text('periodTypeFormat', null, ['class' => 'form-control']) !!}
</div>

<!-- Isunique Field -->
<div class="form-group col-sm-12">
    {!! Form::label('isUnique', 'Isunique:') !!}
    <label class="radio-inline">
        {!! Form::radio('isUnique', "0", null) !!} No
    </label>

    <label class="radio-inline">
        {!! Form::radio('isUnique', "1", null) !!} Yes
    </label>

</div>
