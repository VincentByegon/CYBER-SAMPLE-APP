<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Livewire\Customers\CustomerIndex;
use App\Livewire\Customers\CustomerDetails;
use App\Livewire\Services\ServiceIndex;
use App\Livewire\Orders\OrderIndex;
use App\Livewire\Orders\CreateOrder;
use App\Livewire\Orders\OrderDetails;
use App\Livewire\Payments\PaymentIndex;
use App\Livewire\Payments\CreatePayment;
use App\Livewire\Invoices\InvoiceIndex;
use App\Livewire\Invoices\CreateInvoice;
use App\Livewire\Invoices\InvoiceDetails;
use App\Livewire\Credit\CreditApplications;
use App\Livewire\Invoices\MonthlyInvoiceIndex;
use App\Livewire\Invoices\CreateMonthlyInvoice;
use App\Livewire\Invoices\ShowMonthlyInvoice;
use App\Http\Middleware\EnsureUserIsApproved;
use App\Http\Controllers\MpesaCallbackController;
use App\Livewire\MpesaTransactions;
use App\Livewire\Reports\OrdersReport;
Route::get('/', function () {
    return view('welcome');
})->name('home');


  Route::get('/approval-wait', [\App\Http\Controllers\ApprovalController::class, 'wait'])->name('approval.wait');
Route::middleware(['auth',EnsureUserIsApproved::class])->group(function () {
  Route::view('dashboard', 'dashboard')
    ->name('dashboard');
    // Admin user management
    Route::get('/admin/users', [\App\Http\Controllers\AdminUserController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users/{id}/approve', [\App\Http\Controllers\AdminUserController::class, 'approve'])->name('admin.users.approve');
    Route::delete('/admin/users/{id}', [\App\Http\Controllers\AdminUserController::class, 'destroy'])->name('admin.users.delete');
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
        Route::get('/customers', CustomerIndex::class)->name('customers.index');
Route::get('/customers/{customer}', CustomerDetails::class)->name('customers.show');

Route::get('/services', ServiceIndex::class)->name('services.index');

Route::get('/orders', OrderIndex::class)->name('orders.index');
Route::get('/orders/create', CreateOrder::class)->name('orders.create');
Route::get('/orders/{order}', OrderDetails::class)->name('orders.show');

Route::get('/payments', PaymentIndex::class)->name('payments.index');
Route::get('/payments/create', CreatePayment::class)->name('payments.create');

Route::get('/invoices', InvoiceIndex::class)->name('invoices.index');
Route::get('/invoices/create', CreateInvoice::class)->name('invoices.create');
Route::get('/invoices/{invoice}', InvoiceDetails::class)->name('invoices.show');
Route::get('/invoices/{invoice}/pdf', [\App\Http\Controllers\InvoicePdfController::class, 'show'])->name('invoices.pdf');

Route::get('/credit', CreditApplications::class)->name('credit.applications');
Route::get('/invoices/monthly', function (\Illuminate\Http\Request $request) {
    $month = $request->input('month', date('m'));
    $year = $request->input('year', date('Y'));
    $customerId = $request->input('customer_id');
    $query = \App\Models\Order::whereYear('created_at', $year)
        ->whereMonth('created_at', $month);
    if ($customerId) {
        $query->where('customer_id', $customerId);
    }
    $monthlyOrders = $query->get();
    $customers = \App\Models\Customer::orderBy('name')->get();
    return view('livewire.invoices.create-invoice', compact('monthlyOrders', 'customers', 'customerId'));
})->name('invoices.monthly');
        // Monthly Invoice routes
    // ...existing code...
    Route::get('/monthly-invoices', MonthlyInvoiceIndex::class)->name('monthly-invoices.index');
    Route::get('/monthly-invoices/create', CreateMonthlyInvoice::class)->name('monthly-invoices.create');

    Route::get('/monthly-invoices/{monthlyInvoice}', ShowMonthlyInvoice::class)->name('monthly-invoices.show');
    // PDF route stays the same
   Route::get('/monthly-invoices/{id}/pdf', [\App\Http\Controllers\MonthlyInvoicePdfController::class, 'show'])->name('monthly-invoices.pdf'); 


 Route::get('/mpesa/transactions', MpesaTransactions::class)
         ->name('mpesa.transactions');
   


Route::get('/reports/orders', OrdersReport::class)->name('reports.orders');

// Controller to download PDF
Route::get('/reports/orders/pdf', [\App\Http\Controllers\ReportController::class, 'pdf'])->name('reports.orders.pdf');
});
   Route::post('/validation', [MpesaCallbackController::class, 'validation']);
Route::post('/confirmation', [MpesaCallbackController::class, 'confirmation']);
require __DIR__.'/auth.php';
