<?php

namespace Hni\Dwsync\DataTables;

use Hni\Dwsync\Models\MappingQuestion;
use Form;
use Yajra\Datatables\Services\DataTable;

class MappingQuestionDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'dwsync::mapping_questions.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $mappingQuestions = MappingQuestion::query();

        return $this->applyScopes($mappingQuestions);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->addAction(['width' => '10%'])
            ->ajax('')
            ->parameters([
                'dom' => 'Bfrtip',
                'scrollX' => false,
                'buttons' => [
                    'print',
                    'reset',
                    'reload',
                    [
                         'extend'  => 'collection',
                         'text'    => '<i class="fa fa-download"></i> Export',
                         'buttons' => [
                             'csv',
                             'excel',
                             'pdf',
                         ],
                    ],
                    'colvis'
                ]
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            'id' => ['name' => 'id', 'data' => 'id'],
            'mappingProjectId' => ['name' => 'mappingProjectId', 'data' => 'mappingProjectId'],
            'question1' => ['name' => 'question1', 'data' => 'question1'],
            'question2' => ['name' => 'question2', 'data' => 'question2'],
            'functions' => ['name' => 'functions', 'data' => 'functions'],
            'arg1' => ['name' => 'arg1', 'data' => 'arg1'],
            'arg2' => ['name' => 'arg2', 'data' => 'arg2']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'mappingQuestions';
    }
}
