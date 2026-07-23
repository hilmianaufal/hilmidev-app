<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProjectRequestController as AdminProjectRequestController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\CourseCatalogController;
use App\Http\Controllers\CoursePreviewController;
use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoicePdfController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentProofController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDownloadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectRequestController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/source-code', [ProductController::class, 'index'])
    ->name('products.index');

Route::get('/source-code/{product:slug}', [ProductController::class, 'show'])
    ->name('products.show');

Route::get('/services', [ServiceController::class, 'index'])
    ->name('services.index');

Route::get('/services/{service:slug}', [ServiceController::class, 'show'])
    ->name('services.show');

Route::get('/blog', [PostController::class, 'index'])
    ->name('blog.index');

Route::get('/blog/{post:slug}', [PostController::class, 'show'])
    ->name('blog.show');

Route::get('/kelas-coding', [CourseCatalogController::class, 'index'])
    ->name('courses.index');

Route::get('/kelas-coding/preview/{lesson}', [CoursePreviewController::class, 'show'])
    ->name('courses.lessons.preview');

Route::get('/kelas-coding/preview/{lesson}/video', [CoursePreviewController::class, 'video'])
    ->name('courses.lessons.preview-video');

Route::get('/kelas-coding/{course}', [CourseCatalogController::class, 'show'])
    ->name('courses.show');

Route::get('/dashboard', DashboardRedirectController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/checkout/{product:slug}', [CheckoutController::class, 'store'])
        ->name('checkout.store');

    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders.index');

    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->name('orders.show');

    Route::post('/orders/{order}/payment-proof', [PaymentProofController::class, 'store'])
        ->name('orders.payment-proof.store');

    Route::get('/orders/{order}/items/{item}/download', [ProductDownloadController::class, 'download'])
        ->name('orders.items.download');

    Route::get('/orders/{order}/invoice-pdf', [InvoicePdfController::class, 'download'])
        ->name('orders.invoice-pdf');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('/project-requests', [ProjectRequestController::class, 'index'])
        ->name('project-requests.index');

    Route::get('/services/{service:slug}/request', [ProjectRequestController::class, 'create'])
        ->name('project-requests.create');

    Route::post('/services/{service:slug}/request', [ProjectRequestController::class, 'store'])
        ->name('project-requests.store');

    Route::get('/project-requests/{projectRequest}', [ProjectRequestController::class, 'show'])
        ->name('project-requests.show');

    Route::get('/client/dashboard', [ClientDashboardController::class, 'index'])
        ->name('client.dashboard');
});

Route::middleware([
    'auth',
    \App\Http\Middleware\EnsureActiveMembership::class,
])
    ->prefix('member')
    ->name('member.')
    ->group(function () {
        Route::get('/dashboard', [
            \App\Http\Controllers\Member\DashboardController::class,
            'index',
        ])->name('dashboard');

        Route::get('/kelas/{course}', [
            \App\Http\Controllers\Member\CourseController::class,
            'show',
        ])->name('courses.show');

        Route::get('/materi/{lesson}', [
            \App\Http\Controllers\Member\LessonController::class,
            'show',
        ])->name('lessons.show');

        Route::get('/materi/{lesson}/video', [
            \App\Http\Controllers\Member\LessonController::class,
            'video',
        ])->name('lessons.video');

        Route::get('/materi/{lesson}/attachment', [
            \App\Http\Controllers\Member\LessonController::class,
            'attachment',
        ])->name('lessons.attachment');

        Route::post('/materi/{lesson}/progress', [
            \App\Http\Controllers\Member\LessonProgressController::class,
            'store',
        ])->name('lessons.progress');
        Route::post('/materi/{lesson}/catatan', [
            \App\Http\Controllers\Member\LessonNoteController::class,
            'store',
        ])->name('lessons.notes.store');

        Route::delete('/materi/{lesson}/catatan', [
            \App\Http\Controllers\Member\LessonNoteController::class,
            'destroy',
        ])->name('lessons.notes.destroy');

        Route::post('/materi/{lesson}/diskusi', [
            \App\Http\Controllers\Member\LessonDiscussionController::class,
            'store',
        ])->name('lessons.discussions.store');

        Route::delete('/diskusi/{discussion}', [
            \App\Http\Controllers\Member\LessonDiscussionController::class,
            'destroy',
        ])->name('discussions.destroy');
    });

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('categories', AdminCategoryController::class)
            ->except('show');

        Route::resource('products', AdminProductController::class);

        Route::resource('services', AdminServiceController::class)
            ->except('show');

        Route::resource('portfolios', AdminPortfolioController::class)
            ->except('show');

        Route::resource('testimonials', AdminTestimonialController::class)
            ->except('show');

        Route::resource('posts', AdminPostController::class)
            ->except('show');

        Route::get('orders', [AdminOrderController::class, 'index'])
            ->name('orders.index');

        Route::get('orders/{order}', [AdminOrderController::class, 'show'])
            ->name('orders.show');

        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])
            ->name('orders.update-status');

        Route::get('project-requests', [AdminProjectRequestController::class, 'index'])
            ->name('project-requests.index');

        Route::get('project-requests/{projectRequest}', [AdminProjectRequestController::class, 'show'])
            ->name('project-requests.show');

        Route::patch(
            'project-requests/{projectRequest}/status',
            [AdminProjectRequestController::class, 'updateStatus']
        )->name('project-requests.update-status');

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

        Route::resource(
            'membership-plans',
            \App\Http\Controllers\Admin\MembershipPlanController::class
        )->except('show');

        Route::resource(
            'members',
            \App\Http\Controllers\Admin\MemberController::class
        )->except('show');

        Route::resource(
            'courses',
            \App\Http\Controllers\Admin\CourseController::class
        )->except('show');

        Route::post('/courses/{course}/modules', [
            \App\Http\Controllers\Admin\CourseModuleController::class,
            'store',
        ])->name('courses.modules.store');

        Route::put('/modules/{module}', [
            \App\Http\Controllers\Admin\CourseModuleController::class,
            'update',
        ])->name('modules.update');

        Route::delete('/modules/{module}', [
            \App\Http\Controllers\Admin\CourseModuleController::class,
            'destroy',
        ])->name('modules.destroy');

        Route::post('/modules/{module}/lessons', [
            \App\Http\Controllers\Admin\CourseLessonController::class,
            'store',
        ])->name('modules.lessons.store');

        Route::put('/lessons/{lesson}', [
            \App\Http\Controllers\Admin\CourseLessonController::class,
            'update',
        ])->name('lessons.update');

        Route::delete('/lessons/{lesson}', [
            \App\Http\Controllers\Admin\CourseLessonController::class,
            'destroy',
        ])->name('lessons.destroy');
        Route::get('/lesson-discussions', [
            \App\Http\Controllers\Admin\LessonDiscussionController::class,
            'index',
        ])->name('lesson-discussions.index');

        Route::get('/lesson-discussions/{discussion}', [
            \App\Http\Controllers\Admin\LessonDiscussionController::class,
            'show',
        ])->name('lesson-discussions.show');

        Route::post('/lesson-discussions/{discussion}/reply', [
            \App\Http\Controllers\Admin\LessonDiscussionController::class,
            'reply',
        ])->name('lesson-discussions.reply');

        Route::patch('/lesson-discussions/{discussion}/status', [
            \App\Http\Controllers\Admin\LessonDiscussionController::class,
            'updateStatus',
        ])->name('lesson-discussions.status');

        Route::delete('/lesson-discussions/{discussion}', [
            \App\Http\Controllers\Admin\LessonDiscussionController::class,
            'destroy',
        ])->name('lesson-discussions.destroy');
    });

require __DIR__ . '/auth.php';
