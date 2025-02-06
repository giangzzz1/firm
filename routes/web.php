<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminExchangeController;
use App\Http\Controllers\AdminOrdersController;
use App\Http\Controllers\AdminWalletController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DiemdanhController;
use App\Http\Controllers\ExchangePointController;
use App\Http\Controllers\ManagerUserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VecuabanController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

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

// Route cho Account
Route::controller(AccountController::class)->group(function () {
    Route::get('account/register', 'register')->name('register.form');
    Route::post('account/register', 'register_')->name('register');

    Route::get('account/login', 'login')->name('login.form');
    Route::post('account/login', 'login_')->name('login');

    Route::get('account/password/forgot', 'rspassword')->name('forgot.form');
    Route::post('password/forgot', 'rspassword_')->name('password.forgot');

    Route::get('account/password/reset/{token}', 'updatepassword')->name('password.reset');
    Route::post('account/password/reset', 'updatepassword_')->name('password.update');

    Route::post('account/logout', 'logout')->name('logout');
});

//Các route cho client
Route::controller(ClientController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('clients/shop', 'shop')->name('shop.index');
    Route::get('clients/contact', 'contact')->name('contact');
    Route::get('clients/about', 'about')->name('about');
    Route::get('ticket/{id}', [TicketController::class, 'show'])->name('ticket.show');
    Route::get('/search-tickets', [TicketController::class, 'searchTickets'])->name('search.tickets');
});

// Route cho Admin
Route::controller(AdminController::class)
    ->middleware(['auth', 'AdminOrManager'])
    ->group(function () {
        Route::get('admin/dashboard', 'index')->name('admin.dashboard');
        // Đổi mật khẩu
        Route::get('/admin/change-password', 'changepass')->name('admin.changepass.form');
        Route::post('/admin/change-password', 'changepass_')->name('admin.password.change');
        // Cập nhật tài khoản
        Route::get('/admin/edit', 'edit')->name('admin.edit');
        Route::post('/admin/update', 'update')->name('admin.update');

        //route chức năng
        Route::resource('admin/adminorders', AdminOrdersController::class);
        Route::resource('admin/Adminwallet', AdminWalletController::class);
        Route::resource('admin/categories', CategoryController::class);
        Route::resource('admin/tickets', TicketController::class);
        Route::resource('managers', ManagerUserController::class)->middleware(['auth', 'admin']);

        Route::get('/charts', [ChartController::class, 'getChartData'])->name('charts');

        Route::get('/transaction', [TransactionController::class, 'index']);
        Route::post('/transaction', [TransactionController::class, 'return']);

        Route::get('/adminexchange', [AdminExchangeController::class, 'index']);
    });

// Route cho User
Route::controller(UserController::class)
    ->middleware(['auth', 'user'])
    ->group(function () {
        Route::get('user/dashboard', 'user')->name('user.dashboard');

        Route::get('user/change-password', 'changepass')->name('user.changepass.form');
        Route::post('user/change-password', 'changepass_')->name('user.password.change');

        Route::get('user/edit', 'edit')->name('user.edit');
        Route::post('user/update', 'update')->name('user.update');


    //route chuc nang 
    Route::resource('user/wallet', WalletController::class);

    Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('cart.add');
    Route::resource('user/carts', CartController::class);
    Route::resource('user/orders', OrderController::class);

    Route::resource('user/diemdanh', DiemdanhController::class);
    Route::post('/diemdanh/makeup', [DiemdanhController::class, 'makeupAttendance'])->name('diemdanh.makeup');
    Route::post('/diemdanh/exchange', [DiemdanhController::class, 'exchangePoints'])->name('diemdanh.exchange');

    Route::resource('user/donmua', PurchaseController::class);
    Route::resource('user/vecuaban', VecuabanController::class);

    Route::resource('/user/exchange', ExchangePointController::class);
});

