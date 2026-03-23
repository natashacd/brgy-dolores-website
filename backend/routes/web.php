<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/admin/service-requests', function () {
    return view('admin.resident-request-service');
});

require __DIR__.'/auth.php';
