<?php

use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProjectRequestController as AdminProjectRequestController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoicePdfController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentProofController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDownloadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectRequestController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;



Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/source-code', [ProductController::class, 'index'])->name('products.index');
Route::get('/source-code/{product:slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service:slug}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/blog', [PostController::class, 'index'])
    ->name('blog.index');

Route::get('/blog/{post:slug}', [PostController::class, 'show'])
    ->name('blog.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/checkout/{product:slug}', [CheckoutController::class, 'store'])
        ->name('checkout.store');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    Route::post('/orders/{order}/payment-proof', [PaymentProofController::class, 'store'])
        ->name('orders.payment-proof.store');

    Route::get('/orders/{order}/items/{item}/download', [ProductDownloadController::class, 'download'])
        ->name('orders.items.download');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/project-requests', [ProjectRequestController::class, 'index'])
    ->name('project-requests.index');

    Route::get('/services/{service:slug}/request', [ProjectRequestController::class, 'create'])
        ->name('project-requests.create');

    Route::post('/services/{service:slug}/request', [ProjectRequestController::class, 'store'])
        ->name('project-requests.store');

    Route::get('/project-requests/{projectRequest}', [ProjectRequestController::class, 'show'])
        ->name('project-requests.show');

    Route::get('/orders/{order}/invoice-pdf', [InvoicePdfController::class, 'download'])
        ->name('orders.invoice-pdf');

    Route::get('/client/dashboard', [ClientDashboardController::class, 'index'])
    ->name('client.dashboard');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('categories', AdminCategoryController::class);
        Route::resource('products', AdminProductController::class);
        Route::resource('services', AdminServiceController::class);

        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])
            ->name('orders.update-status');

        Route::get('project-requests', [AdminProjectRequestController::class, 'index'])
    ->name('project-requests.index');

        Route::get('project-requests/{projectRequest}', [AdminProjectRequestController::class, 'show'])
            ->name('project-requests.show');

        Route::patch('project-requests/{projectRequest}/status', [AdminProjectRequestController::class, 'updateStatus'])
            ->name('project-requests.update-status');
        Route::resource('portfolios', AdminPortfolioController::class);

        Route::resource('testimonials', AdminTestimonialController::class);
        Route::resource('posts', AdminPostController::class);

        Route::get('payments', [AdminPaymentController::class, 'index'])
    ->name('payments.index');

        Route::get('payments/{order}', [AdminPaymentController::class, 'show'])
            ->name('payments.show');

        Route::patch('payments/{order}/approve', [AdminPaymentController::class, 'approve'])
            ->name('payments.approve');

        Route::patch('payments/{order}/reject', [AdminPaymentController::class, 'reject'])
            ->name('payments.reject');
        Route::get('clients', [AdminClientController::class, 'index'])
    ->name('clients.index');

        Route::get('clients/{user}', [AdminClientController::class, 'show'])
            ->name('clients.show');


            });

           



require __DIR__.'/auth.php';