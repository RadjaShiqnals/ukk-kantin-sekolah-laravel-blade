<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Web\Admin\UserController;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/stan/dashboard', function () {
        if (auth()->user()->role !== 'admin_stan') {
            return response()->view('components.error-page', [
                'code' => '403',
                'heading' => 'Unauthorized Access',
                'message' => 'Only stan administrators can access this page.'
            ], 403);
        }
        return view('stan.dashboard');
    })->name('stan.dashboard');
    
    Route::get('/siswa/dashboard', function () {
        if (auth()->user()->role !== 'siswa') {
            return response()->view('components.error-page', [
                'code' => '403',
                'heading' => 'Unauthorized Access',
                'message' => 'Only students can access this page.'
            ], 403);
        }
        return view('siswa.dashboard');
    })->name('siswa.dashboard');
    
    // Admin routes with custom error page
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', function() {
            if (auth()->user()->role !== 'admin') {
                return response()->view('components.error-page', [
                    'code' => '403',
                    'heading' => 'Admin Access Required',
                    'message' => 'Only administrators can access this dashboard.'
                ], 403);
            }
            return app(UserController::class)->index(request());
        })->name('admin.dashboard');

        Route::post('/users', function() {
            if (auth()->user()->role !== 'admin') {
                return response()->json([
                    'error' => 'Unauthorized action.',
                    'message' => 'Only administrators can perform this action.'
                ], 403);
            }
            return app(UserController::class)->store(request());
        })->name('admin.users.store');

        Route::put('/users/{user}', function(User $user) {
            if (auth()->user()->role !== 'admin') {
                return response()->json([
                    'error' => 'Unauthorized action.',
                    'message' => 'Only administrators can perform this action.'
                ], 403);
            }
            return app(UserController::class)->update(request(), $user);
        })->name('admin.users.update');

        Route::delete('/users/{user}', function(User $user) {
            if (auth()->user()->role !== 'admin') {
                return response()->json([
                    'error' => 'Unauthorized action.',
                    'message' => 'Only administrators can perform this action.'
                ], 403);
            }
            return app(UserController::class)->destroy($user);
        })->name('admin.users.destroy');

        Route::get('/users/{user}/edit', function(User $user) {
            if (auth()->user()->role !== 'admin') {
                return response()->json([
                    'error' => 'Unauthorized action.',
                    'message' => 'Only administrators can perform this action.'
                ], 403);
            }
            return app(UserController::class)->edit($user);
        })->name('admin.users.edit');
    });
});