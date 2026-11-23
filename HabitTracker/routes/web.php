<?php

use App\Http\Controllers\HabitController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcom');
});

Route::get('/habits', [HabitController::class, 'index'])->name('index');
Route::get('/habits/create', [HabitController::class, 'create'])->name('create');
Route::post('/habits', [HabitController::class, 'store'])->name('store');
Route::get('/habits/{habit}', [HabitController::class, 'show'])->name('show');
Route::get('/habits/{habit}/edit', [HabitController::class, 'edit'])->name('edit');
Route::post('/habits/{habit}', [HabitController::class, 'update'])->name('update');
Route::delete('/habits/{habit}', [HabitController::class, 'destroy'])->name('destroy');
