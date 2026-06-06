<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CabinetController;
use App\Http\Controllers\CnamController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrdonnanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplyItemController;
use App\Http\Controllers\SupplyOrderController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\TreatmentRecordController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ── Public welcome ────────────────────────────────────────────────────────────
Route::get('/', fn () => view('welcome'))->name('home');

// ── PFE Report ────────────────────────────────────────────────────────────────
Route::get('/rapport', [RapportController::class, 'view'])->name('rapport.view');
Route::get('/rapport/download', [RapportController::class, 'download'])->name('rapport.download');
Route::get('/rapport/download-word', [RapportController::class, 'downloadWord'])->name('rapport.downloadWord');

// Redirect Jetstream's Inertia profile route to our Blade profile page
Route::middleware(['auth'])->get('/user/profile', fn () => redirect()->route('profile.show'));

// ── Authenticated routes ──────────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // ── Patients ──────────────────────────────────────────────────────────────
    Route::middleware('role:super_admin,admin,doctor,secretary')->group(function () {
        Route::resource('patients', PatientController::class)->except(['show']);
        Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');
    });

    // ── Appointments ──────────────────────────────────────────────────────────
    Route::middleware('role:super_admin,admin,doctor,secretary,patient')->group(function () {
        Route::get('/appointments/bordereau', [AppointmentController::class, 'bordereau'])->name('appointments.bordereau');
        Route::resource('appointments', AppointmentController::class);
        Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])
             ->name('appointments.status');
        Route::get('/calendar', [AppointmentController::class, 'calendar'])->name('appointments.calendar');
    });

    // ── Treatments ────────────────────────────────────────────────────────────
    Route::middleware('role:super_admin,admin,doctor,secretary,patient')->group(function () {
        Route::resource('treatments', TreatmentController::class);
        Route::get('/treatment-categories', [TreatmentController::class, 'categoriesIndex'])
             ->name('treatments.categories');
        Route::post('/treatment-categories', [TreatmentController::class, 'categoryStore'])
             ->name('treatment-categories.store');
        Route::delete('/treatment-categories/{category}', [TreatmentController::class, 'categoryDestroy'])
             ->name('treatment-categories.destroy');

        Route::resource('treatment-records', TreatmentRecordController::class);
    });

    // ── Invoices ──────────────────────────────────────────────────────────────
    // Static routes MUST come before {invoice} wildcard to avoid /create being caught as a show route
    Route::middleware('role:super_admin,admin,doctor,secretary')->group(function () {
        Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
        Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
        Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
        Route::patch('/invoices/{invoice}/pay', [InvoiceController::class, 'markPaid'])->name('invoices.pay');
        Route::patch('/invoices/{invoice}/cancel', [InvoiceController::class, 'cancel'])->name('invoices.cancel');
        Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    });
    Route::middleware('role:super_admin,admin,doctor,secretary,patient')->group(function () {
        Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    });

    // ── Suppliers & Inventory ─────────────────────────────────────────────────
    // Admin-only: full supply item management + suppliers + categories + order status
    Route::middleware('role:super_admin,admin')->group(function () {
        Route::resource('suppliers', SupplierController::class);

        Route::resource('supply-items', SupplyItemController::class)->except(['show']);

        Route::get('/supply-categories', [SupplyItemController::class, 'categoriesIndex'])
             ->name('supply-categories.index');
        Route::post('/supply-categories', [SupplyItemController::class, 'categoryStore'])
             ->name('supply-categories.store');
        Route::delete('/supply-categories/{category}', [SupplyItemController::class, 'categoryDestroy'])
             ->name('supply-categories.destroy');

        Route::delete('/supply-orders/{supplyOrder}', [SupplyOrderController::class, 'destroy'])
             ->name('supply-orders.destroy');
    });

    // Doctors: view their assigned inventory, consume stock, submit supply demands
    Route::middleware('role:super_admin,admin,doctor')->group(function () {
        Route::get('/supply-items', [SupplyItemController::class, 'index'])->name('supply-items.index');
        Route::patch('/supply-items/{supplyItem}/consume', [SupplyItemController::class, 'consume'])->name('supply-items.consume');
        Route::resource('supply-orders', SupplyOrderController::class)->only(['create', 'store']);
    });

    // Suppliers: view orders addressed to them + update status per role
    Route::middleware('role:super_admin,admin,doctor,supplier')->group(function () {
        Route::resource('supply-orders', SupplyOrderController::class)->only(['index', 'show']);
        Route::patch('/supply-orders/{supplyOrder}/status', [SupplyOrderController::class, 'updateStatus'])
             ->name('supply-orders.status');
    });

    // ── CNAM Bulletins ────────────────────────────────────────────────────────
    Route::middleware('role:doctor,super_admin,admin')->group(function () {
        Route::post('/appointments/{appointment}/ordonnances', [OrdonnanceController::class, 'store'])->name('ordonnances.store');
        Route::get('/ordonnances/{ordonnance}/print', [OrdonnanceController::class, 'print'])->name('ordonnances.print');
        Route::get('/appointments/{appointment}/cnam/create', [CnamController::class, 'create'])->name('cnam.create');
        Route::post('/appointments/{appointment}/cnam', [CnamController::class, 'store'])->name('cnam.store');
        Route::post('/appointments/{appointment}/cnam/skip', [CnamController::class, 'skip'])->name('cnam.skip');
        Route::get('/cnam/{bulletin}/print', [CnamController::class, 'print'])->name('cnam.print');
    });

    // ── Medical Offices ───────────────────────────────────────────────────────
    Route::middleware('role:super_admin')->group(function () {
        Route::resource('cabinets', CabinetController::class)->except(['show']);
    });

    // ── Staff Management ──────────────────────────────────────────────────────
    Route::middleware('role:super_admin,admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::patch('/users/{user}/toggle', [UserController::class, 'toggleActive'])
             ->name('users.toggle');
    });
});
