<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['prefix' => 'v1', 'namespace' => 'Api\v1'], function () use ($router) {
    $router->get('units', 'UnitController@getUnits');
    $router->get('semester-aktif', 'SemesterProdiController@getSemesterAktif');
    
    $router->get('/mahasiswa', 'Mahasiswa\MyController@getListData');
    $router->group(['prefix' => 'mahasiswa/{nim}', 'namespace' => 'Mahasiswa'], function () use ($router) {
        $router->get('/', 'MyController@getData');
        $router->get('/krs', 'KrsController@getListData');
        $router->get('/list-semester', 'SemesterController@getListData');
        $router->get('/sum-sks', 'SksController@getSumery');
        $router->get('/transkrip', 'TranskripController@getListTranskrip');
        
        $router->get('/kelas', 'KelasController@getListKelasNim');
        $router->get('/kelas/{klsId}', 'KelasController@getDetailKelasNim');
    });
});
