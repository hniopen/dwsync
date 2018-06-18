<table class="table table-responsive" id="mappingQuestions-table">
    <thead>
    <tr>
        <th>Mapping questionnaire</th>
        <th>Question 1</th>
        <th>Question 2</th>
        <th>Function</th>
        <th>Arg 1</th>
        <th>Arg 2</th>
        <th colspan="3">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($mappingQuestions as $mappingQuestion)
        <tr>
            <td>
                <span class="label label-info">{!! $mappingQuestion->type_project1 !!} : {!! $mappingQuestion->name_project1 !!}</span> to
                <span class="label label-success"> {!! $mappingQuestion->type_project2 !!} : {!! $mappingQuestion->name_project2 !!}</span>
            </td>
            <td>{!! empty($mappingQuestion->name_question1)?'Code Village': $mappingQuestion->name_question1!!}</td>
            <td>{!! $mappingQuestion->name_question2 !!}</td>
            <td>{!! $mappingQuestion->functions !!}</td>
            <td>{!! $mappingQuestion->arg1 !!}</td>
            <td>{!! $mappingQuestion->arg2 !!}</td>
            <td>
                {!! Form::open(['route' => ['dwsync.mappingQuestions.destroy', $mappingQuestion->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('dwsync.mappingQuestions.show', [$mappingQuestion->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('dwsync.mappingQuestions.edit', [$mappingQuestion->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>