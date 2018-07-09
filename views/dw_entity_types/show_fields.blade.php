<!-- Type Field -->
<div class="form-group">
    {!! Form::label('type', 'Type:') !!}
    <p>{!! $dwEntityType->type !!}</p>
</div>

<!-- Comment Field -->
<div class="form-group">
    {!! Form::label('comment', 'Comment:') !!}
    <p>{!! $dwEntityType->comment !!}</p>
</div>

<!-- Apiurl Field -->
<div class="form-group">
    {!! Form::label('apiUrl', 'Apiurl:') !!}
    <p><?php echo config('dwsync.dwBaseUrl').$dwEntityType->apiUrl ?></p>
</div>

