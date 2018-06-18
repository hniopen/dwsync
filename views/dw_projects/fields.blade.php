<!-- Id Field -->
{{--<div class="form-group col-sm-6">--}}
    {{--{!! Form::label('id', 'Id:') !!}--}}
    {{--{!! Form::number('id', null, ['class' => 'form-control']) !!}--}}
{{--</div>--}}

<!-- Questcode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('questCode', 'Questcode:') !!}
    {!! Form::text('questCode', null, ['class' => 'form-control', 'placeholder'=>'eg.: 045']) !!}
</div>

<!-- Submissiontable Field -->
<div class="form-group col-sm-6">
    {!! Form::label('submissionTable', 'Submissiontable:') !!}
    {!! Form::text('submissionTable', null, ['class' => 'form-control', 'placeholder'=>'actually unused']) !!}
</div>

<!-- Parentid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('parentId', 'Parentid:') !!}
    {!! Form::number('parentId', null, ['class' => 'form-control', 'placeholder'=>'actually unused']) !!}
</div>

<!-- Comment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('comment', 'Comment:') !!}
    {!! Form::text('comment', null, ['class' => 'form-control', 'placeholder'=>'eg. : DW questionnaire name']) !!}
</div>

<!-- Isdisplayed Field -->
<div class="form-group col-sm-12">
    {!! Form::label('isDisplayed', 'Isdisplayed:') !!}
    <label class="radio-inline">
        {!! Form::radio('isDisplayed', "1", null) !!} Yes
    </label>

    <label class="radio-inline">
        {!! Form::radio('isDisplayed', "0", null) !!} No
    </label>

</div>

<!-- autoSync Field -->
<div class="form-group col-sm-12">
    {!! Form::label('autoSync', 'AutoSync:') !!}
    <label class="radio-inline">
        {!! Form::radio('autoSync', "1", null) !!} Yes
    </label>

    <label class="radio-inline">
        {!! Form::radio('autoSync', "0", null) !!} No
    </label>

</div>

<!-- Xformurl Field -->
<div class="form-group col-sm-6">
    {!! Form::label('xformUrl', 'Xformurl:') !!}
    {!! Form::text('xformUrl', null, ['class' => 'form-control', 'placeholder'=>'actually unused']) !!}
</div>

<!-- longQuestCode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('longQuestCode', 'longQuestCode:') !!}
    {!! Form::text('longQuestCode', null, ['class' => 'form-control', 'placeholder'=>'eg. : f1ee0aa6992d11e7989822000bb7c4b1']) !!}
</div>

<!-- Credential Field -->
<div class="form-group col-sm-6">
    {!! Form::label('credential', 'Credential:') !!}
    {!! Form::password('credential', ['class' => 'form-control', 'placeholder'=>'eg. : the_dw_account@gmail.com:the_password']) !!}
</div>

<!-- Entitytype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('entityType', 'Entitytype:') !!}
    {!! Form::select('entityType', $dwEntityTypeList, null, ['class' => 'form-control']) !!}
</div>

<!-- Formtype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('formType', 'Formtype:') !!}
    {!! Form::select('formType', ['basic' => 'Basic', 'advanced' => 'Advanced', 'poll' => 'Poll', 'undefined' => 'Undefined'], null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('dwsync.dwProjects.index') !!}" class="btn btn-default">Cancel</a>
</div>
