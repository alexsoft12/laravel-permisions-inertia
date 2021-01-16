<?php

use App\Http\Controllers\ManageUserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::group(['middleware' => ['permission:module.users']], function () {

        Route::resource('users', ManageUserController::class);
        /* Route::get('users', function () {
             return $users = \App\Models\User::all();
             // return Inertia::render('User/Index', ['users' => $users]);
         })->name('user.index');*/
    });

    Route::group(['middleware' => ['permission:module.products']], function () {
        Route::get('products', function () {
            $products = [['id' => 1, 'name' => 'Banana', 'price' => 4.0, 'unit' => 'Kg'],
                ['id' => 2, 'name' => 'Apple', 'price' => 6.0, 'unit' => 'Kg']];
            return Inertia::render('Product/Index', ['products' => $products]);
        })->name('products.index');
    });

    Route::group(['middleware' => ['permission:module.purchase']], function () {
        Route::get('purchases', function () {
            $purchases = [['id' => 1, 'product_id' => 1, 'quantity' => 5], ['id' => 2, 'product_id' => 2, 'quantity' => 10]];
            return Inertia::render('Purchase/Index', ['purchases' => $purchases]);
        })->name('purchases.index');
    });

    Route::group(['middleware' => ['permission:module.reports']], function () {
        Route::get('reports', function () {
            $reports = [['name_product' => 'Banana', 'stock' => 5, 'unit' => 'Kg', 'total_amount' => 20.0],
                ['name_product' => 'Apple', 'stock' => 10, 'unit' => 'Kg', 'total_amount' => 60.0]];
            return Inertia::render('Report/Index', ['reports' => $reports]);
        })->name('reports.index');

    });

});
