<?php

use Illuminate\Support\Facades\Route;

Route::any('/', function () {
    return response()->json((object)[]);
});
