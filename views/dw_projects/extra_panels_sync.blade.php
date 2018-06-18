<div class="box box-success" id="syncData" style="display: none;">
    <div class="box-header">
        <h4>Sync data from DW</h4>
        If having +1000 row to pull, please turn "DEBUG" to false in .env to avoid huge queryLog from laravel_debug_bar
    </div>
    <div class="box-body">
        <div class="fromSync">
            <!-- Date and time range -->
            @if($dwProject->entityType == 'Q')
                <label>Start date for Sync:</label>
                <div class="form-group row">

                    <div class="col-xs-4">
                        <div class="input-group date" id="syncDataTimeFrom" title="Select start date">
                            <span class="fa fa-calendar input-group-addon" title="Select start date"></span>
                            <input type="text" class="form-control pull-right" title="Select start date" >
                        </div>
                    </div>
                    <!-- /.input group -->
                </div>
            @endif
            <div class="form-group">
                <div class="row" style="padding-left: 20px">
                    <button class="btn btn-success" id="btnCheck" onclick="ajaxSyncSingleProject();">Run sync</button>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="row" id="syncDataResult" style="display: none;">
    <div class="col-md-4">
        <div class="box box-success">
            <div class="box-header">
                <h4>Pull status</h4>
            </div>
            <div id="pullStatus">

            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="box box-warning">
            <div class="box-header">
                <h4>Sync Result</h4>
            </div>
            <div class="box-body">
                <textarea id="syncResult" readonly style="width: 100%; min-height: 300px"></textarea>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    function statusProcessSyncActions(_actionBoxId){
        var btnCheck = $(_actionBoxId).find("#btnCheck");
        btnCheck.addClass('disabled');
        $("#syncResult").text("");
    }
    function statusFinishSyncActions_withoutError(_actionBoxId){
        var btnCheck = $(_actionBoxId).find("#btnCheck");
        btnCheck.removeClass('disabled');
    }
    function statusFinishSyncActions_withError(_actionBoxId){
        var btnCheck = $(_actionBoxId).find("#btnCheck");
        btnCheck.removeClass('disabled');
    }

    function ajaxSyncSingleProject() {
        var _actionBoxId = "#syncData";
        var _idQuestion = '{{$dwProject->id}}';
        var _fromDate =  $("#syncDataTimeFrom").find('input[type=text]').val();
        var url = '{{route('dwsync.dwProjects.sync', '__id__') }}?from='+_fromDate;
        url = url.replace("__id__", _idQuestion);
        console.log("URL : " + url);
        hideNotif();
        statusProcessSyncActions(_actionBoxId);
        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            data: {},
            success: function (data, textStatus) {
                console.log("Data " + JSON.stringify(data));
                var resultJson = data['result'] ? JSON.stringify(data['result']) : "No result";
                var resultSubmissions = data['submissions'] ? JSON.stringify(data['submissions']) : "No submissions";
                var message = data['message']['text'];// + " <br> " + data['submissions']['status'] ;
                $("#syncResult").text(resultJson);
                $("#pullStatus").html(formatQuestionsHtmlFromStatus(data['submissions']['status']));
                statusFinishSyncActions_withoutError(_actionBoxId);
                notifSuccess(message);
            },
            error: function (xhr, textStatus, errorThrown) {
                var message = 'Error : ' + xhr.responseText;
                statusFinishSyncActions_withError(_actionBoxId);
                notifError(message);
            }
        });
    }

    function formatQuestionsHtmlFromStatus(tStatus){
        var vHtml = "<table class='table table-responsive'><thead><th>Status</th><th>Number</th></thead><tbody>";
        for(var sts in tStatus){
            vHtml += "<tr><td>"+sts+"</td><td>" + tStatus[sts] + "</td></tr>";
        }
        vHtml += "</table>";
        return vHtml;
    }
</script>