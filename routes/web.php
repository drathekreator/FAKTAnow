<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\homePageController;

Route::get('/', [homePageController::class, 'index']);
