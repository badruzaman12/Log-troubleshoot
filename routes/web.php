<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TroubleshootController;

Route::get('/', [TroubleshootController::class, 'index'])->name('troubleshoot.index');
Route::resource('troubleshoot', TroubleshootController::class)->except('index');