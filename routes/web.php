<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['prefix' => 'v1', 'namespace' => 'Api\v1'], function () use ($router) {
    $router->get('units', 'UnitController@getUnits');
    $router->get('semester-aktif', 'SemesterProdiController@getSemesterAktif');
    
    $router->get('/mahasiswa', 'MahasiswaController@getListData');
    $router->group(['prefix' => 'mahasiswa/{nim}'], function () use ($router) {
        $router->get('/', 'MahasiswaController@getData');
        $router->get('/krs', 'MahasiswaController@getKrs');
        $router->get('/list-semester', 'MahasiswaController@getListSemester');
        $router->get('/sum-sks', 'MahasiswaController@getSumSks');
        $router->get('/transkrip', 'MahasiswaController@getListTranskrip');
    });
});
