<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/characters-view', function () {
    return view('index'); // Devuelve la vista 'characters.blade.php'
});

