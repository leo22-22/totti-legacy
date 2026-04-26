<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WebhookController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth — guest only
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/cadastro', [RegisterController::class, 'showRegister'])->name('register');
    Route::post('/cadastro', [RegisterController::class, 'register']);
    Route::get('/esqueci-senha', [PasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/esqueci-senha', [PasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/resetar-senha/{token}', [PasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/resetar-senha', [PasswordController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Área do cliente
Route::middleware('auth')->prefix('minha-conta')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('index');
    Route::put('/perfil', [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::put('/senha', [AccountController::class, 'updatePassword'])->name('password.update');
    Route::get('/pedidos', [AccountController::class, 'orders'])->name('orders');
    Route::get('/pedidos/{orderNumber}', [AccountController::class, 'order'])->name('order');
});

// Loja
Route::prefix('loja')->name('shop.')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('index');
    Route::get('/{slug}', [ShopController::class, 'show'])->name('show');
});

// Carrinho
Route::prefix('carrinho')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/adicionar', [CartController::class, 'add'])->name('add');
    Route::post('/atualizar', [CartController::class, 'update'])->name('update');
    Route::post('/remover', [CartController::class, 'remove'])->name('remove');
});

// Checkout
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/processar', [CheckoutController::class, 'process'])->name('process');
    Route::post('/frete', [CheckoutController::class, 'freight'])->name('freight');
    Route::post('/cupom', [CheckoutController::class, 'applyCoupon'])->name('coupon.apply');
    Route::delete('/cupom', [CheckoutController::class, 'removeCoupon'])->name('coupon.remove');
    Route::get('/sucesso/{orderNumber}', [CheckoutController::class, 'success'])->name('success');
});

// Migration/Seed trigger — protected by APP_KEY, for post-deploy use only
Route::get('/__migrate', function () {
    if (request('secret') !== config('app.key')) {
        abort(403, 'Forbidden');
    }

    $action = request('action', 'migrate');
    $output = [];

    try {
        if ($action === 'migrate') {
            Artisan::call('migrate', ['--force' => true]);
            $output[] = '[MIGRATE] ' . trim(Artisan::output());
        } elseif ($action === 'seed') {
            // Só roda seed se ainda não houver categorias (evita duplicação)
            if (\App\Models\Category::count() === 0) {
                Artisan::call('db:seed', ['--force' => true]);
                $output[] = '[SEED] ' . trim(Artisan::output());
            } else {
                $output[] = '[SEED] Skipped — database already seeded (' . \App\Models\Category::count() . ' categories, ' . \App\Models\Product::count() . ' products found).';
            }
        } else {
            return response('[ERROR] Unknown action. Use action=migrate or action=seed.', 400, ['Content-Type' => 'text/plain']);
        }
    } catch (\Throwable $e) {
        return response('[ERROR] ' . $e->getMessage() . "\n" . $e->getTraceAsString(), 500, ['Content-Type' => 'text/plain']);
    }

    return response(implode("\n", $output), 200, ['Content-Type' => 'text/plain']);
});

// Webhooks — sem CSRF
Route::post('/webhooks/stripe', [WebhookController::class, 'stripe'])
    ->name('webhooks.stripe')
    ->withoutMiddleware([VerifyCsrfToken::class]);

Route::post('/webhooks/mercadopago', [WebhookController::class, 'mercadopago'])
    ->name('webhooks.mercadopago')
    ->withoutMiddleware([VerifyCsrfToken::class]);