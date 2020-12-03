<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['prefix' => 'v1', 'namespace' => 'Api\v1'], function () use ($router) {
    $router->get('units', 'UnitController@getUnits');
    $router->get('semester-aktif', 'SemesterController@getSemesterAktif');
    $router->get('list-semester', 'SemesterController@getListSemester');

    $router->get('/admin/{username}', 'Admin\MyController@getData');
    
    $router->get('/mahasiswa', 'Mahasiswa\MyController@getListData');
    $router->group(['prefix' => 'mahasiswa/{nim}', 'namespace' => 'Mahasiswa'], function () use ($router) {
        $router->get('/', 'MyController@getData');
        $router->get('/list-semester', 'SemesterController@getListData');

        $router->group(['prefix' => 'krs'], function () use ($router) {
            $router->get('', 'KrsController@getListData');
            $router->post('/entry', 'KrsController@entry'); //x
            $router->get('/isCanEntry', 'KrsController@isCanEntry'); //x
            $router->get('/{semester}', 'KrsController@getListDataSemester');
            $router->post('/{krsdtId}/chage-status/{status}','KrsController@changeStatus'); //x
        });
        
        $router->get('/sks-sum', 'SksController@getSumery');
        $router->get('/transkrip', 'TranskripController@getListTranskrip');
        $router->get('/staticA', 'TranskripController@staticA');
        $router->get('/staticB', 'TranskripController@staticB');
        
        $router->get('/kelas', 'KelasController@getListKelasNim');
        $router->get('/kelas/{klsId}', 'KelasController@getDetailKelasNim');
    });

    $router->get('/dosen', 'Dosen\MyController@getListData');
    $router->group(['prefix' => 'dosen/{nip}', 'namespace' => 'Dosen'], function () use ($router) {
        $router->get('/', 'MyController@getData');
        $router->get('/mahasiswa-bimbingan', 'BimbinganController@getListMahasiswa');
    });
});
