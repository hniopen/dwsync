<?php
Route::group(['middleware' => 'web'], function () {
    Route::group(['middleware' => ['auth', 'roles'], 'roles'=>['dwsync_admin','dwsync_run_sync'], 'prefix' => 'dwsync', 'as' => 'dwsync.'], function () {
        Route::get('dwEntityTypes', ['as' => 'dwEntityTypes.index', 'uses' => 'Hni\Dwsync\Http\Controllers\DwEntityTypeController@index']);
        Route::post('dwEntityTypes', ['as' => 'dwEntityTypes.store', 'uses' => 'Hni\Dwsync\Http\Controllers\DwEntityTypeController@store']);
        Route::get('dwEntityTypes/create', ['as' => 'dwEntityTypes.create', 'uses' => 'Hni\Dwsync\Http\Controllers\DwEntityTypeController@create']);
        Route::put('dwEntityTypes/{dwEntityTypes}', ['as' => 'dwEntityTypes.update', 'uses' => 'Hni\Dwsync\Http\Controllers\DwEntityTypeController@update']);
        Route::patch('dwEntityTypes/{dwEntityTypes}', ['as' => 'dwEntityTypes.update', 'uses' => 'Hni\Dwsync\Http\Controllers\DwEntityTypeController@update']);
        Route::delete('dwEntityTypes/{dwEntityTypes}', ['as' => 'dwEntityTypes.destroy', 'uses' => 'Hni\Dwsync\Http\Controllers\DwEntityTypeController@destroy']);
        Route::get('dwEntityTypes/{dwEntityTypes}', ['as' => 'dwEntityTypes.show', 'uses' => 'Hni\Dwsync\Http\Controllers\DwEntityTypeController@show']);
        Route::get('dwEntityTypes/{dwEntityTypes}/edit', ['as' => 'dwEntityTypes.edit', 'uses' => 'Hni\Dwsync\Http\Controllers\DwEntityTypeController@edit']);

        Route::get('dwProjects', ['as' => 'dwProjects.index', 'uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@index']);
        Route::post('dwProjects', ['as' => 'dwProjects.store', 'uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@store']);
        Route::get('dwProjects/create', ['as' => 'dwProjects.create', 'uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@create']);
        Route::put('dwProjects/{dwProjects}', ['as' => 'dwProjects.update', 'uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@update']);
        Route::patch('dwProjects/{dwProjects}', ['as' => 'dwProjects.update', 'uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@update']);
        Route::delete('dwProjects/{dwProjects}', ['as' => 'dwProjects.destroy', 'uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@destroy']);
        Route::get('dwProjects/{dwProjects}', ['as' => 'dwProjects.show', 'uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@show']);
        Route::get('dwProjects/{dwProjects}/edit', ['as' => 'dwProjects.edit', 'uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@edit']);
        Route::get('dwProjects/{dwProjects}/extra', ['as' => 'dwProjects.extra', 'uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@extra']);
        Route::get('dwProjects/check/from/submissions/{dwProjects}', ['as' => 'dwProjects.checkFromSubmissions', 'uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@checkFromSubmissions']);
        Route::post('dwProjects/insert/from/submissions/', ['as' => 'dwProjects.insertFromSubmissions', 'uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@insertFromSubmissions']);
        Route::get('dwProjects/check/from/xform/{dwProjects}', ['as' => 'dwProjects.checkFromXform', 'uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@checkFromXform']);
        Route::post('dwProjects/insert/from/xform/', ['as' => 'dwProjects.insertFromXform', 'uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@insertFromXform']);
        Route::post('dwProjects/check/from/xls/{dwProjects}', ['as' => 'dwProjects.checkFromXls', 'uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@checkFromXls']);
        Route::post('dwProjects/insert/from/xls/', ['as' => 'dwProjects.insertFromXls', 'uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@insertFromXls']);
        Route::get('dwProjects/check/existing/questions/{dwProjects}', ['as' => 'dwProjects.checkExistingQuestions', 'uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@checkExistingQuestions']);
        Route::post('dwProjects/remove/existing/questions/', ['as' => 'dwProjects.removeExistingQuestions', 'uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@removeExistingQuestions']);
        Route::get('dwProjects/update/fromdb/{dwProjects}', ['as' => 'dwProjects.updateModelFromDB', 'uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@updateModelFromDB']);

        Route::get('dwQuestions', ['as' => 'dwQuestions.index', 'uses' => 'Hni\Dwsync\Http\Controllers\DwQuestionController@index']);
        Route::get('dwQuestions/of/{dwProjects}', ['as' => 'dwQuestions.listQuestions', 'uses' => 'Hni\Dwsync\Http\Controllers\DwQuestionController@listQuestions']);
        Route::post('dwQuestions', ['as' => 'dwQuestions.store', 'uses' => 'Hni\Dwsync\Http\Controllers\DwQuestionController@store']);
        Route::get('dwQuestions/create', ['as' => 'dwQuestions.create', 'uses' => 'Hni\Dwsync\Http\Controllers\DwQuestionController@create']);
        Route::put('dwQuestions/{dwQuestions}', ['as' => 'dwQuestions.update', 'uses' => 'Hni\Dwsync\Http\Controllers\DwQuestionController@update']);
        Route::patch('dwQuestions/{dwQuestions}', ['as' => 'dwQuestions.update', 'uses' => 'Hni\Dwsync\Http\Controllers\DwQuestionController@update']);
        Route::delete('dwQuestions/{dwQuestions}', ['as' => 'dwQuestions.destroy', 'uses' => 'Hni\Dwsync\Http\Controllers\DwQuestionController@destroy']);
        Route::get('dwQuestions/{dwQuestions}', ['as' => 'dwQuestions.show', 'uses' => 'Hni\Dwsync\Http\Controllers\DwQuestionController@show']);
        Route::get('dwQuestions/{dwQuestions}/edit', ['as' => 'dwQuestions.edit', 'uses' => 'Hni\Dwsync\Http\Controllers\DwQuestionController@edit']);
        Route::get('dwQuestions/create/from/submissions', ['as' => 'dwQuestions.createFromSubmissions', 'uses' => 'Hni\Dwsync\Http\Controllers\DwQuestionController@createFromSubmissions']);
        Route::post('dwQuestions/check/from/submissions', ['as' => 'dwQuestions.checkFromSubmissions', 'uses' => 'Hni\Dwsync\Http\Controllers\DwQuestionController@checkFromSubmissions']);
        Route::post('dwQuestions/store/from/submissions', ['as' => 'dwQuestions.storeFromSubmissions', 'uses' => 'Hni\Dwsync\Http\Controllers\DwQuestionController@storeFromSubmissions']);
        Route::get('dwQuestions/create/from/xlsform', ['as' => 'dwQuestions.createFromXlsform', 'uses' => 'Hni\Dwsync\Http\Controllers\DwQuestionController@createFromXlsform']);
        Route::post('dwQuestions/store/from/xlsform', ['as' => 'dwQuestions.storeFromXlsform', 'uses' => 'Hni\Dwsync\Http\Controllers\DwQuestionController@storeFromXlsform']);

        Route::get('mappingProjects', ['as'=> 'mappingProjects.index', 'uses' => 'Hni\Dwsync\Http\Controllers\MappingProjectController@index']);
        Route::post('mappingProjects', ['as'=> 'mappingProjects.store', 'uses' => 'Hni\Dwsync\Http\Controllers\MappingProjectController@store']);
        Route::get('mappingProjects/create', ['as'=> 'mappingProjects.create', 'uses' => 'Hni\Dwsync\Http\Controllers\MappingProjectController@create']);
        Route::put('mappingProjects/{mappingProjects}', ['as'=> 'mappingProjects.update', 'uses' => 'Hni\Dwsync\Http\Controllers\MappingProjectController@update']);
        Route::patch('mappingProjects/{mappingProjects}', ['as'=> 'mappingProjects.update', 'uses' => 'Hni\Dwsync\Http\Controllers\MappingProjectController@update']);
        Route::delete('mappingProjects/{mappingProjects}', ['as'=> 'mappingProjects.destroy', 'uses' => 'Hni\Dwsync\Http\Controllers\MappingProjectController@destroy']);
        Route::get('mappingProjects/{mappingProjects}', ['as'=> 'mappingProjects.show', 'uses' => 'Hni\Dwsync\Http\Controllers\MappingProjectController@show']);
        Route::get('mappingProjects/{mappingProjects}/edit', ['as'=> 'mappingProjects.edit', 'uses' => 'Hni\Dwsync\Http\Controllers\MappingProjectController@edit']);


        Route::get('mappingQuestions', ['as'=> 'mappingQuestions.index', 'uses' => 'Hni\Dwsync\Http\Controllers\MappingQuestionController@index']);
        Route::post('mappingQuestions', ['as'=> 'mappingQuestions.store', 'uses' => 'Hni\Dwsync\Http\Controllers\MappingQuestionController@store']);
        Route::get('mappingQuestions/create', ['as'=> 'mappingQuestions.create', 'uses' => 'Hni\Dwsync\Http\Controllers\MappingQuestionController@create']);
        Route::put('mappingQuestions/{mappingQuestions}', ['as'=> 'mappingQuestions.update', 'uses' => 'Hni\Dwsync\Http\Controllers\MappingQuestionController@update']);
        Route::patch('mappingQuestions/{mappingQuestions}', ['as'=> 'mappingQuestions.update', 'uses' => 'Hni\Dwsync\Http\Controllers\MappingQuestionController@update']);
        Route::delete('mappingQuestions/{mappingQuestions}', ['as'=> 'mappingQuestions.destroy', 'uses' => 'Hni\Dwsync\Http\Controllers\MappingQuestionController@destroy']);
        Route::get('mappingQuestions/{mappingQuestions}', ['as'=> 'mappingQuestions.show', 'uses' => 'Hni\Dwsync\Http\Controllers\MappingQuestionController@show']);
        Route::get('mappingQuestions/{mappingQuestions}/edit', ['as'=> 'mappingQuestions.edit', 'uses' => 'Hni\Dwsync\Http\Controllers\MappingQuestionController@edit']);

        Route::get('/count/project/if/{entityType}', ['uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@countIfType', 'as' => 'project.count.ifType']);
        Route::get('/count/data/if/{entityType}', ['uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@countDataIfType', 'as' => 'data.count.ifType']);
    });
 });

//Do not group in middleware
Route::get('dwsync/dwProjects/sync/{dwProjects}', ['as' => 'dwsync.dwProjects.sync', 'uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@sync']);
Route::get('dwsync/dwProjects/syncing/all', ['as' => 'dwsync.dwProjects.syncAll', 'uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@syncAll']);
Route::get('dwsync/dwProjects/sync/all/marked', ['as' => 'dwsync.dwProjects.syncAllMarked', 'uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@syncAllMarked']);
Route::get('dwsync/dwProjects/push_idnr/all', ['as' => 'dwsync.dwProjects.pushIdnr', 'uses' => 'Hni\Dwsync\Http\Controllers\DwProjectController@pushIdnr']);


//SMS
Route::get('dwsync/dwSms/send', ['as' => 'dwsync.dwSms.send', 'uses' => 'Hni\Dwsync\Http\Controllers\DwSmsController@send']);
Route::get('test', function () {
    echo 'Hello from dw sync!';
});

