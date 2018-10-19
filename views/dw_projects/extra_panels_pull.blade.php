<div class="box box-primary" id="fromSubmissions" style="display: none;">
    <div class="box-header">
        <h4>Pull from existing submissions</h4>
    </div>
    <div class="box-body">
        <div class="">
            <!-- Date and time range -->
            <div class="form-group">
                <label>Start date for Sync:</label>
                <div class="form-group row">

                    <div class="col-xs-4">
                        <div class="input-group date" id="pullDataTimeFrom" title="Select start date">
                            <span class="fa fa-calendar input-group-addon" title="Select start date"></span>
                            <input type="text" class="form-control pull-right" title="Select start date" >
                        </div>
                        <br/>
                        <div class="input-group date" id="pullDataTimeTo" title="Select End date">
                            <span class="fa fa-calendar input-group-addon" title="Select End date"></span>
                            <input type="text" class="form-control pull-right" title="Select End date" >
                        </div>
                    </div>
                </div>
                    <!-- /.input group -->
                </div>
                <!-- /.input group -->
            </div>
            <div class="row" style="padding-left: 20px">
                <button class="btn btn-default" id="btnCheck" onclick="ajaxCheckQuestionsFromDwSubmissions();">Check</button>
                <button class="btn btn-default btn-success" id="btnInsert" style="display: none" onclick="ajaxInsertQuestionsFromDwSubmissions();">insert questions</button>
            </div>
        </div>

    </div>
</div>
<div class="box box-primary" id="fromXform" style="display: none;">
    <div class="box-header">
        <h4>Pull from xform</h4>
    </div>
    <div class="box-body">
        <div class="row" style="padding-left: 20px">
            <button class="btn btn-default" id="btnCheck" onclick="ajaxCheckQuestionsFromDwXform()">Check</button>
            <button class="btn btn-default btn-success" id="btnInsert" style="display: none" onclick="ajaxInsertQuestionsFromDwXform();">insert questions</button>
        </div>
    </div>
</div>
<div class="box box-primary" id="fromXls" style="display: none;">
    <div class="box-header">
        <h4>Pull from xlsform</h4>
    </div>
    <div class="box-body">
        {!! Form::open(['url' => '#','files'=>'true', 'id'=>'formXls']) !!}
        <div class="row">
            <!-- Xlsform Field -->
            <div class="form-group col-sm-6">
                {!! Form::label('xlsformFileId', 'Xlsform File:') !!}
                {!! Form::file('xlsform', $attributes = array()) !!}
                {!! Form::hidden('_token', csrf_token()) !!}
            </div>
        </div>
        <div class="row" style="padding-left: 20px">
            <button class="btn btn-default" type="button" id="btnCheck" onclick="ajaxCheckQuestionsFromDwXls();">Check</button>
            <button class="btn btn-default btn-success" id="btnInsert" type="button" style="display: none" onclick="ajaxInsertQuestionsFromDwXls();">insert questions</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<div class="box box-danger" id="removeAll" style="display: none;">
    <div class="box-header">
        <h4>Remove all related questions</h4>
    </div>
    <div class="box-body">
        {!! Form::open(['url' => '#','files'=>'true', 'id'=>'removeAllQuestions']) !!}
        <div class="row">
            <!-- Remove Field -->
            <div class="form-group col-sm-6">
                {!! Form::hidden('_token', csrf_token()) !!}
            </div>
        </div>
        <div class="row" style="padding-left: 20px">
            <button class="btn btn-warning" type="button" id="btnRemove" onclick="ajaxRemoveQuestions();">Confirm remove all</button>
            {{--<button class="btn btn-default btn-danger" id="btnInsert" type="button" style="display: none" onclick="ajaxRemoveAllQuestions();">Confirm remove all questions</button>--}}
        </div>
        {!! Form::close() !!}
    </div>
</div>
<div class="row" id="pullResult" style="display: none;">
    <div class="col-md-12">
        <div class="box box-success">
            <div class="box-header">
                <h4>Found questions</h4>
            </div>
            <div id="foundQuesitons">

            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box box-warning">
            <div class="box-header">
                <h4>Result</h4>
            </div>
            <div class="box-body">
                <textarea id="result" readonly style="width: 100%; min-height: 300px"></textarea>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var questionsFromSubmissions = [];
    var questionsFromXform = [];
    var questionsFromXls = [];
    var existingQuestions = [];
    $(function () {
        $("#pullQuestionTime").datetimepicker({
            format: 'MM/DD/YYYY h:mm A'
        });
    });


    function statusProcessCheckActions(_actionBoxId){
        var btnCheck = $(_actionBoxId).find("#btnCheck");
        var btnInsert = $(_actionBoxId).find("#btnInsert");
        var btnRemove = $(_actionBoxId).find("#btnRemove");
        btnCheck.addClass('disabled');
        btnInsert.addClass('disabled');
        btnRemove.addClass('disabled');
        $("#result").text("");
        $("#foundQuesitons").html("");
    }
    function statusProcessInsertActions(_actionBoxId){
        var btnCheck = $(_actionBoxId).find("#btnCheck");
        var btnInsert = $(_actionBoxId).find("#btnInsert");
        btnCheck.addClass('disabled');
        btnInsert.addClass('disabled');
    }
    function statusFinishCheckActions_withoutError(_actionBoxId){
        var btnCheck = $(_actionBoxId).find("#btnCheck");
        var btnInsert = $(_actionBoxId).find("#btnInsert");
        var btnRemove = $(_actionBoxId).find("#btnRemove");
        btnCheck.removeClass('disabled');
        btnInsert.show();
        btnInsert.removeClass('disabled');
        btnRemove.removeClass('disabled');
    }
    function statusFinishInsertActions_withoutError(_actionBoxId){
        var btnCheck = $(_actionBoxId).find("#btnCheck");
        var btnInsert = $(_actionBoxId).find("#btnInsert");
        var btnRemove = $(_actionBoxId).find("#btnRemove");
        btnCheck.removeClass('disabled');
        btnInsert.removeClass('disabled');
        btnRemove.addClass('disabled');
    }
    function statusFinishCheckActions_withError(_actionBoxId){
        var btnCheck = $(_actionBoxId).find("#btnCheck");
        var btnInsert = $(_actionBoxId).find("#btnInsert");
        btnCheck.removeClass('disabled');
        btnInsert.hide();
    }
    function statusFinishInsertActions_withError(_actionBoxId){
        var btnCheck = $(_actionBoxId).find("#btnCheck");
        var btnInsert = $(_actionBoxId).find("#btnInsert");
        btnCheck.removeClass('disabled');
        btnInsert.removeClass('disabled');
    }

    //From submissions [
    function ajaxCheckQuestionsFromDwSubmissions() {
        var _actionBoxId = "#fromSubmissions";
        var _projectId = '{{$dwProject->id}}';
        var _fromDate = $("#pullDataTimeFrom").find('input[type=text]').val();
        var _toDate = $("#pullDataTimeTo").find('input[type=text]').val();
        var url = '{{route('dwsync.dwProjects.checkFromSubmissions', '__id__')}}';
        url = url.replace("__id__", _projectId);
        console.log("URL : " + url);
        hideNotif();
        statusProcessCheckActions(_actionBoxId);
        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            data: {
                fromDate:_fromDate,
                toDate:_toDate
            },
            success: function (data, textStatus) {
                console.log("Data " + JSON.stringify(data));
                var result = data['result'] ? JSON.stringify(data['result']) : "No result";
                var questions = data['questions'] ? data['questions'] : [];
                var message = "Success checking for "+data['message']['text'];
                $("#result").text(result);
                $("#foundQuesitons").html(formatQuestionsHtmlFromSubmissions(questions));
                statusFinishCheckActions_withoutError(_actionBoxId);
                notifSuccess(message);
                questionsFromSubmissions = questions;
            },
            error: function (xhr, textStatus, errorThrown) {
                var message = 'Error : ' + xhr.responseText;
                statusFinishCheckActions_withError(_actionBoxId);
                notifError(message);
            }
        });
    }

    function formatQuestionsHtmlFromSubmissions(question){
        var vHtml = "<table class='table table-responsive'><thead><th>#</th><th>QuestionId</th></thead><tbody>";
        for(var i=0; i < question.length; i++){
            vHtml += "<tr><td>"+(i+1)+"</td><td>" + question[i] + "</td></tr>";
        }
        vHtml += "</table>";
        return vHtml;
    }

    function ajaxInsertQuestionsFromDwSubmissions() {
        var _actionBoxId = "#fromSubmissions";
        var _projectId = '{{$dwProject->id}}';
        var url = '{{route('dwsync.dwProjects.insertFromSubmissions')}}';
        console.log("URL : " + url);
        hideNotif();
        statusProcessInsertActions(_actionBoxId);
        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            data: {_token: "{{ csrf_token() }}", projectId :_projectId, questions:questionsFromSubmissions},
            success: function (data, textStatus) {
                console.log("Data " + JSON.stringify(data));
                var message = data['message']['text'];
                statusFinishInsertActions_withoutError(_actionBoxId);
                notifSuccess(message);
            },
            error: function (xhr, textStatus, errorThrown) {
                var message = 'Error : ' + xhr.responseText;
                statusFinishInsertActions_withError(_actionBoxId);
                notifError(message);
            }
        });
    }
    //]--- From Submissions

    //From Xform [
    function ajaxCheckQuestionsFromDwXform() {
        var _actionBoxId = "#fromXform";
        var _projectId = '{{$dwProject->id}}';
        var url = '{{route('dwsync.dwProjects.checkFromXform', '__id__')}}';
        url = url.replace("__id__", _projectId);
        console.log("URL : " + url);
        hideNotif();
        statusProcessCheckActions(_actionBoxId);
        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            data: {},
            success: function (data, textStatus) {
                console.log("Data " + JSON.stringify(data));
                var result = data['result'] ? JSON.stringify(data['result']) : "No result";
                var questions = data['questions'] ? data['questions'] : [];
                var message = "Success checking for "+data['message']['text'];
                $("#result").text(result);
                $("#foundQuesitons").html(formatQuestionsHtmlFromXform(questions));
                statusFinishCheckActions_withoutError(_actionBoxId);
                notifSuccess(message);
                questionsFromXform = questions;
            },
            error: function (xhr, textStatus, errorThrown) {
                var message = 'Error : ' + xhr.responseText;
                statusFinishCheckActions_withError(_actionBoxId);
                notifError(message);
            }
        });
    }

    function formatQuestionsHtmlFromXform(question){
        var vHtml = "<table class='table table-responsive'><thead><th>#</th><th>QuestionId</th><th>Label</th><th>Type</th></thead><tbody>";
        var i = 1;
        for(var key in question){
            vHtml += "<tr><td>"+i+"</td><td>" + key + "</td><td>"+question[key].label+"</td><td>"+question[key].type+"</td></tr>";
            i++;
        }
        vHtml += "</table>";
        return vHtml;
    }

    function ajaxInsertQuestionsFromDwXform() {
        var _actionBoxId = "#fromXform";
        var _projectId = '{{$dwProject->id}}';
        var url = '{{route('dwsync.dwProjects.insertFromXform')}}';
        console.log("URL : " + url);
        hideNotif();
        statusProcessInsertActions(_actionBoxId);
        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            data: {_token: "{{ csrf_token() }}", projectId :_projectId, questions:questionsFromXform},
            success: function (data, textStatus) {
                console.log("Data " + JSON.stringify(data));
                var message = data['message']['text'];
                statusFinishInsertActions_withoutError(_actionBoxId);
                notifSuccess(message);
            },
            error: function (xhr, textStatus, errorThrown) {
                var message = 'Error : ' + xhr.responseText;
                statusFinishInsertActions_withError(_actionBoxId);
                notifError(message);
            }
        });
    }

    // ] ---- From xform

    //From Xls [
//    $("#formXls").submit(function(e){
//        e.preventDefault();
//        ajaxCheckQuestionsFromDwXls();
//        e.preventDefault();
//    });
    function ajaxCheckQuestionsFromDwXls() {
        var _actionBoxId = "#fromXls";
        var _projectId = '{{$dwProject->id}}';
        var url = '{{route('dwsync.dwProjects.checkFromXls', '__id__')}}';
        url = url.replace("__id__", _projectId);
        console.log("URL : " + url);
        hideNotif();
        statusProcessCheckActions(_actionBoxId);
        var formData = new FormData($("#formXls")[0]);
        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data, textStatus) {
                console.log("Data " + JSON.stringify(data));
                var result = data['result'] ? JSON.stringify(data['result']) : "No result";
                var questions = data['questions'] ? data['questions'] : [];
                var message = "Success checking for "+data['message']['text'];
                $("#result").text(result);
                $("#foundQuesitons").html(formatQuestionsHtmlFromXls(questions));
                statusFinishCheckActions_withoutError(_actionBoxId);
                notifSuccess(message);
                questionsFromXls = questions;
            },
            error: function (xhr, textStatus, errorThrown) {
                var message = 'Error : ' + xhr.responseText;
                statusFinishCheckActions_withError(_actionBoxId);
                notifError(message);
            }
        });
    }

    function formatQuestionsHtmlFromXls(question){
        var vHtml = "<table class='table table-responsive'><thead><th>Order</th><th>QuestionId</th><th>Label</th><th>Type</th><th>Path</th><th>xformQuestionId</th></thead><tbody>";
        var i = 1;
        for(var key in question){
            vHtml += "<tr><td>"+question[key].order+"</td><td>" + key + "</td><td>"+question[key].label+"</td><td>"+question[key].type+"</td><td>"+question[key].path+"</td><td>"+question[key].name+"</td></tr>";
            i++;
        }
        vHtml += "</table>";
        return vHtml;
    }

    function ajaxInsertQuestionsFromDwXls() {
        var _actionBoxId = "#formXls";
        var _projectId = '{{$dwProject->id}}';
        var url = '{{route('dwsync.dwProjects.insertFromXls')}}';
        console.log("URL : " + url);
        hideNotif();
        statusProcessInsertActions(_actionBoxId);
        var n = 100;
        var splitedQuestions = splitObjectByNProp(questionsFromXls, n);
        recursiveInsert(splitedQuestions, 0, "");
    }

    function recursiveInsert(splitedQuestions, i, oldMsg){
        var _actionBoxId = "#formXls";
        var _projectId = '{{$dwProject->id}}';
        var url = '{{route('dwsync.dwProjects.insertFromXls')}}';
        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            data: {_token: "{{ csrf_token() }}", projectId: _projectId, questions: splitedQuestions[i]},
            success: function (data, textStatus) {
                console.log("Data " + JSON.stringify(data));
                var message = oldMsg + data['message']['text'];
                if(typeof splitedQuestions[i+1] !== 'undefined') {// next exist
                    recursiveInsert(splitedQuestions, i+1, message)
                    console.log("next " + (i+1));
                }
                else {// next does not exist
                    statusFinishInsertActions_withoutError(_actionBoxId);
                    notifSuccess(message);
                    console.log("Finished !");
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                var message = 'Error : ' + xhr.responseText;
                statusFinishInsertActions_withError(_actionBoxId);
                notifError(message);
            }
        });
    }

    function splitObjectByNProp(myObj, n){
        var splitedObj = [];
        var keys = Object.keys(myObj);
        for(var i = 0, j = 0; i <= keys.length - 1; i += n, j++){
            var slicedKeys = keys.slice(i, i+n-1);
            splitedObj[j] = {};
            for(var k in slicedKeys){
                if (slicedKeys.hasOwnProperty(k)) {// check if the property/key is defined in the object itself, not in parent
                    var question = slicedKeys[k];
                    splitedObj[j][question] = myObj[question];
                }
            }
        }
        return splitedObj;
    }
    // ] --- Xls

    // Remove all [
    function ajaxCheckExistingQuestions() {
        var _actionBoxId = "#removeAll";
        var _projectId = '{{$dwProject->id}}';
        var url = '{{route('dwsync.dwProjects.checkExistingQuestions', '__id__')}}';
        url = url.replace("__id__", _projectId);
        console.log("URL : " + url);
        hideNotif();
        statusProcessCheckActions(_actionBoxId);
        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            data: {},
            success: function (data, textStatus) {
                console.log("Data " + JSON.stringify(data));
                var result = data['result'] ? JSON.stringify(data['result']) : "No result";
                var questions = data['questions'] ? data['questions'] : [];
                var message = "Success checking for "+data['message']['text'];
                $("#result").text(result);
                $("#foundQuesitons").html(formatQuestionsHtmlFromXform(questions));
                statusFinishCheckActions_withoutError(_actionBoxId);
                notifSuccess(message);
                existingQuestions = questions;
            },
            error: function (xhr, textStatus, errorThrown) {
                var message = 'Error : ' + xhr.responseText;
                statusFinishCheckActions_withError(_actionBoxId);
                notifError(message);
            }
        });
    }

    function ajaxRemoveQuestions() {
        var _actionBoxId = "#removeAll";
        var _projectId = '{{$dwProject->id}}';
        var url = '{{route('dwsync.dwProjects.removeExistingQuestions')}}';
        console.log("URL : " + url);
        hideNotif();
        statusProcessInsertActions(_actionBoxId);
        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            data: {_token: "{{ csrf_token() }}", projectId :_projectId, questions:existingQuestions},
            success: function (data, textStatus) {
                console.log("Data " + JSON.stringify(data));
                var message = data['message']['text'];
                statusFinishInsertActions_withoutError(_actionBoxId);
                notifSuccess(message);
            },
            error: function (xhr, textStatus, errorThrown) {
                var message = 'Error : ' + xhr.responseText;
                statusFinishInsertActions_withError(_actionBoxId);
                notifError(message);
            }
        });
    }

</script>