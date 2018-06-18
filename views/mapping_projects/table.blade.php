<table class="table table-responsive" id="mappingProjects-table">
    <thead>
    <tr>
        <th>Project 1</th>
        <th>Project 2</th>
        <th>Date Last exported</th>
        <th colspan="3">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($mappingProjects as $mappingProject)
        <tr>
            <td>
                <span class="label label-default">{!! $mappingProject->type_project1 !!} : {!! $mappingProject->name_project1 !!}</span>
            </td>
            <td>
                <span class="label label-default">{!! $mappingProject->type_project2 !!} : {!! $mappingProject->name_project2 !!}</span>
            </td>
            <td>{!! $mappingProject->dateLastExported !!}</td>
            <td>
                {!! Form::open(['route' => ['dwsync.mappingProjects.destroy', $mappingProject->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('dwsync.mappingProjects.show', [$mappingProject->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('dwsync.mappingProjects.edit', [$mappingProject->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

