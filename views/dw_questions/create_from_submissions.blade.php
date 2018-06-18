@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Dw Question
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        @if(!empty($error))
            @if($error['code'] > 0)
                <div class="alert alert-danger" id="errorId">
                    {{ $error['message'] }}
                </div>

            @elseif($error['code'] == 0)
                <div class="alert alert-success" id="errorId">
                    Checking done !
                </div>
            @endif
        @endif
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                {!! Form::open(['route' => 'dwsync.dwQuestions.checkFromSubmissions', 'id'=>'formId']) !!}

                <!-- Projectid Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('projectId', 'Projectid:') !!}
                        {!! Form::select('projectId', $dwProjectList, null, ['class' => 'form-control']) !!}
                    </div>

                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Check from submissions', ['class' => 'btn btn-primary']) !!}
                        <a href="{!! route('dwsync.dwQuestions.index') !!}" class="btn btn-default">Cancel</a>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        <!-- Checked questions -->
        @if(!empty($questionsList))
            @foreach($questionsList as $item)
                <div id="box{{$item}}" class="box box-warning">
                    <div class="box-header">
                        <h2>Question : {{$item}}</h2>
                    </div>
                    <div class="box-body">
                        <div class="row">
                        {!! Form::open(['id'=>'questionFormId'.$item]) !!}
                        @include('dwsync::dw_questions.fields')

                        <!-- Flag for ajax -->
                        {!! Form::hidden('forAjax', 'yes') !!}
                        {!! Form::hidden('questionIdUpdate', 0, ['id'=>'questionIdUpdate'.$item]) !!}
                        <!-- Submit Field -->
                            <div class="form-group col-sm-12">
                                {!! Form::button("Add $item", ['id'=>'btnAdd'.$item, 'class' => 'btn btn-primary saveQuestion', 'onclick'=>"createQuestion('$item');"]) !!}
                                {!! Form::button("Update $item", ['id'=>'btnUpdate'.$item,
                                'class' => 'btn btn-warning saveQuestion', 'onclick'=>"updateQuestion('$item');", 'style'=>'display:none;']) !!}
                                <div class="alert alert-danger pull-right" id="notif_error{{$item}}" style="display: none;">
                                </div>
                                <div class="alert alert-success pull-right" id="notif_success{{$item}}" style="display: none;">
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <script language="JavaScript">
        function hideNotif(_item){
            $("#notif_success"+_item).hide();
            $("#notif_error"+_item).hide();
        }
        function notifError(_item, _msg){
            $("#notif_error"+_item).html(_msg);
            $("#notif_error"+_item).show();
        }
        function notifSuccess(_item, _msg){
            $("#notif_success"+_item).html(_msg);
            $("#notif_success"+_item).show();
        }
        function createQuestion(_item){
            var questionForm = $("#questionFormId"+_item);
            var url = '{{route('dwsync.dwQuestions.store')}}';
            console.log("Item " + _item + " | URL : " + url);
            hideNotif(_item);
            $.ajax({
                type: questionForm.attr('method'),
                url: url,
                dataType: 'json',
                data: questionForm.serialize(),
                success: function (data, textStatus) {
                    var message = data['message'];
                    var question = data['entity'];
                    console.log("msg:" + message + " | id : " + question['id']);
                    notifSuccess(_item, message);
                    $("#questionIdUpdate"+_item).val(question['id']);
                    $("#box"+_item).removeClass("box-warning");
                    $("#box"+_item).addClass("box-success");
                    $("#btnAdd"+_item).hide();
                    $("#btnUpdate"+_item).show();
                },
                error:function(xhr, textStatus, errorThrown){
                    var message = 'request failed';
                    notifError(_item,message);
                }
            });
        }

        function updateQuestion(_item){
            var questionForm = $("#questionFormId"+_item);
            var id = $("#questionIdUpdate"+_item).val();
            var url = '{{route('dwsync.dwQuestions.update', ['dwQuestions' => '__id__'])}}';
            url = url.replace('__id__',id);
            console.log("Item " + _item + " | URL : " + url);
            hideNotif(_item);
            $.ajax({
                type: 'patch',
                url: url,
                dataType: 'json',
                data: questionForm.serialize(),
                success: function (data, textStatus) {
                    var message = 'Question was updated';
                    notifSuccess(_item, message);
                },
                error:function(xhr, textStatus, errorThrown){
                    var message = 'request failed';
                    notifError(_item,message);
                }
            });
        }
    </script>
@endsection
