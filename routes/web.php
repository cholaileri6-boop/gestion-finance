<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RevenuController;
use App\Http\Controllers\DepenseController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\AlertController;


Route::get('/', function () {
    return view('landing'); // Utilise le fichier landing.blade.php que tu viens de créer
});

Route::get('/dashboard', [DashboardController::class, 'index'])
->middleware(['auth'])
->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::resource('revenus', RevenuController::class)->middleware('auth');

Route::resource('depenses', DepenseController::class)->middleware('auth');

Route::resource('budgets', BudgetController::class);


Route::middleware(['auth'])->group(function () {
Route::get('/budgets', [BudgetController::class, 'index'])->name('budgets.index');
Route::post('/budgets', [BudgetController::class, 'store'])->name('budgets.store');
});

Route::middleware(['auth'])->group(function(){

Route::get('/rapports',[RapportController::class,'index'])
->name('rapports.index');

});

Route::middleware('auth')->group(function () {
    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');
    Route::post('/alerts/{alert}/read', [AlertController::class, 'markRead'])->name('alerts.read');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users');
    Route::get('/transactions', [App\Http\Controllers\AdminController::class, 'transactions'])->name('transactions');
    Route::get('/budgets', [App\Http\Controllers\AdminController::class, 'budgets'])->name('budgets');
    Route::get('/categories', [App\Http\Controllers\AdminController::class, 'categories'])->name('categories');
    Route::get('/alerts', [App\Http\Controllers\AdminController::class, 'alerts'])->name('alerts');

    // Actions sur les utilisateurs
    Route::patch('/users/{user}/toggle-status', [App\Http\Controllers\AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
    Route::delete('/users/{user}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.destroy');
});

require __DIR__.'/auth.php';
