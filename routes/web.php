<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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
    return view('login');
})->middleware('guest');


Auth::routes([
    'login' => false,
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);



Route::get('/get-user', function(){
    if(Auth::check()){
        return Auth::user();
    }

    return [];
});



Route::post('/custom-login', [App\Http\Controllers\Auth\LoginController::class, 'login']);

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



//for open request
//ADDRESS
Route::get('/load-provinces', [App\Http\Controllers\AddressController::class, 'loadProvinces']);
Route::get('/load-cities', [App\Http\Controllers\AddressController::class, 'loadCities']);
Route::get('/load-barangays', [App\Http\Controllers\AddressController::class, 'loadBarangays']);

Route::get('/load-financial-years', [App\Http\Controllers\OpenController::class, 'loadFinancialYears']);
Route::get('/load-offices', [App\Http\Controllers\OpenController::class, 'loadOffices']);
Route::get('/load-allotment-classes', [App\Http\Controllers\OpenController::class, 'loadAllotmentClasses']);
Route::get('/load-transaction-types', [App\Http\Controllers\OpenController::class, 'loadTransactionTypes']);
Route::get('/load-documentary-attachments', [App\Http\Controllers\OpenController::class, 'loadDocumentaryAttachments']);



Route::get('/load-fund-sources', [App\Http\Controllers\OpenController::class, 'loadFundSources']);

Route::get('/get-modal-offices', [App\Http\Controllers\OpenModalResourcesController::class, 'getModalOffices']);




/*     ADMINSITRATOR          */

//authenticate
Route::middleware(['auth'])->group(function() {

    Route::resource('/dashboard', App\Http\Controllers\Administrator\DashboardController::class);
    Route::get('/get-offices-for-routes', [App\Http\Controllers\Administrator\OfficeController::class, 'getOfficesForRoutes']);

    Route::get('/load-accounting-utilizations/{financial}', [App\Http\Controllers\Administrator\DashboardController::class, 'loadAccountingUtilizations']);
   // Route::get('/load-budgeting-utilizations/{financial}', [App\Http\Controllers\Administrator\DashboardController::class, 'loadBudgetingUtilizations']);
    //Route::get('/load-procurement-utilizations/{financial}', [App\Http\Controllers\Administrator\DashboardController::class, 'loadProcurementUtilizations']);

    //dashboard report fetch data
    Route::get('/load-report-dashboard', [App\Http\Controllers\Administrator\DashboardController::class, 'loadReportDashboard']);

    //Route::get('/load-allotment-budgeting/{financial}/{allotmentId}', [App\Http\Controllers\Administrator\DashboardController::class, 'loadAllotmentBudgeting']);

    Route::get('/report-transaction-by-office', [App\Http\Controllers\Report\ReportTransactionByOfficeController::class, 'index']);
    Route::get('/load-report-transaction-by-office', [App\Http\Controllers\Report\ReportTransactionByOfficeController::class, 'loadReportTransacationByOffice']);

});


Route::middleware(['auth', 'admin'])->group(function() {

    Route::resource('/financial-years', App\Http\Controllers\Administrator\FinancialYearController::class);
    Route::get('/get-financial-years', [App\Http\Controllers\Administrator\FinancialYearController::class, 'getData']);


    Route::resource('/users', App\Http\Controllers\Administrator\UserController::class);
    Route::get('/get-users', [App\Http\Controllers\Administrator\UserController::class, 'getUsers']);
    Route::post('/reset-password/{id}', [App\Http\Controllers\Administrator\UserController::class, 'resetPassword']);




    Route::resource('/allotment-classes', App\Http\Controllers\Administrator\AllotmentClassController::class);
    Route::get('/get-allotment-classes', [App\Http\Controllers\Administrator\AllotmentClassController::class, 'getData']);

    Route::resource('/object-expenditures', App\Http\Controllers\Administrator\ObjectExpenditureController::class);
    Route::get('/get-object-expenditures', [App\Http\Controllers\Administrator\ObjectExpenditureController::class, 'getData']);

    // Route::resource('/allotment-class-accounts', App\Http\Controllers\Administrator\AllotmentClassAccountController::class);
    // Route::get('/get-allotment-class-accounts', [App\Http\Controllers\Administrator\AllotmentClassAccountController::class, 'getData']);


    //Route::resource('/priority-programs', App\Http\Controllers\Administrator\PriorityProgramController::class);
    //Route::get('/get-priority-programs', [App\Http\Controllers\Administrator\PriorityProgramController::class, 'getData']);

    Route::resource('/services', App\Http\Controllers\Administrator\ServiceController::class);
    Route::get('/get-services', [App\Http\Controllers\Administrator\ServiceController::class, 'getData']);



    Route::resource('/supplier-payee', App\Http\Controllers\Administrator\PayeeController::class);
    Route::get('/get-supplier-payee', [App\Http\Controllers\Administrator\PayeeController::class, 'getData']);


    Route::resource('/transaction-types', App\Http\Controllers\Administrator\TransactionTypeController::class);
    Route::get('/get-transaction-types', [App\Http\Controllers\Administrator\TransactionTypeController::class, 'getData']);

    Route::resource('/documentary-attachments', App\Http\Controllers\Administrator\DocumentaryAttachmentController::class);
    Route::get('/get-documentary-attachments', [App\Http\Controllers\Administrator\DocumentaryAttachmentController::class, 'getData']);

    Route::resource('/offices', App\Http\Controllers\Administrator\OfficeController::class);
    Route::get('/get-offices', [App\Http\Controllers\Administrator\OfficeController::class, 'getOffices']);


});



Route::middleware(['auth', 'astaff'])->group(function() {

    Route::resource('/accounting', App\Http\Controllers\Administrator\AccountingController::class);
    Route::post('/accounting-update/{id}', [App\Http\Controllers\Administrator\AccountingController::class, 'updateAccounting']);
    Route::get('/get-accounting-records', [App\Http\Controllers\Administrator\AccountingController::class, 'getData']);
    Route::delete('/accounting-documentary-attachment-delete/{docId}', [App\Http\Controllers\Administrator\AccountingController::class, 'deleteAcctgDocAttachment']);
    Route::get('/fetch-accountings', [App\Http\Controllers\Administrator\AccountingController::class, 'fetchAccountings']);
    //get processors
    Route::get('/get-modal-processors', [App\Http\Controllers\Administrator\AccountingController::class, 'getModalProcessor']);
    Route::post('/accounting-assign-processor', [App\Http\Controllers\Administrator\AccountingController::class, 'assignProcessor']);

});



Route::middleware(['auth'])->group(function() {
    Route::get('/get-modal-object-expenditures', [App\Http\Controllers\Administrator\ObjectExpenditureController::class, 'getModalObjectExpenditures']);
    Route::get('/get-modal-allotment-class-accounts', [App\Http\Controllers\Administrator\AllotmentClassAccountController::class, 'getModalAllotmentClassAccounts']);
    Route::get('/get-modal-payee', [App\Http\Controllers\Administrator\PayeeController::class, 'getModalPayee']);

});

Route::middleware(['auth', 'budget'])->group(function() {
    Route::resource('/budgeting', App\Http\Controllers\Administrator\BudgetingController::class);
    Route::post('/budgeting-update/{id}', [App\Http\Controllers\Administrator\BudgetingController::class, 'updateBudgeting']);
    Route::get('/get-budgeting-records', [App\Http\Controllers\Administrator\BudgetingController::class, 'getData']);

    Route::post('/budgeting-documentary-attachment-delete/{docId}', [App\Http\Controllers\Administrator\BudgetingController::class, 'deleteDocAttachment']);
    Route::get('/fetch-budgetings', [App\Http\Controllers\Administrator\BudgetingController::class, 'fetchBudgetings']);

});




Route::middleware(['auth', 'procurement'])->group(function() {

    Route::resource('/procurements', App\Http\Controllers\Administrator\ProcurementController::class);
    Route::post('/procurements-update/{id}', [App\Http\Controllers\Administrator\ProcurementController::class, 'updateProcurement']);

    Route::get('/get-procurements-records', [App\Http\Controllers\Administrator\ProcurementController::class, 'getData']);
    Route::post('/procurement-documentary-attachment-delete/{docId}', [App\Http\Controllers\Administrator\ProcurementController::class, 'deleteProcurementDocAttachment']);
    Route::get('/fetch-procurements', [App\Http\Controllers\Administrator\ProcurementController::class, 'fetchProcurements']);


});




Route::middleware(['auth', 'processor'])->group(function() {

    Route::resource('/documents', App\Http\Controllers\Processor\DocumentController::class);
    Route::get('/get-documents', [App\Http\Controllers\Processor\DocumentController::class, 'getData']);

    Route::post('/document-mark-receive/{accountingId}', [App\Http\Controllers\Processor\DocumentController::class, 'documentMarkReceive']);

    Route::post('/document-forward/{option}/{accountingId}', [App\Http\Controllers\Processor\DocumentController::class, 'documentMark']);


    Route::post('/document-bid-award-status/{acctgId}', [App\Http\Controllers\Processor\DocumentController::class, 'documentBidAwardUpdate']);
    Route::post('/document-bid-award-remarks/{acctgId}', [App\Http\Controllers\Processor\DocumentController::class, 'documentBidAwardUpdateRemarks']);

    Route::post('/document-city-budget-status/{acctgId}', [App\Http\Controllers\Processor\DocumentController::class, 'cityBudgetUpdateStatus']);
    Route::post('/document-city-budget-remarks/{acctgId}', [App\Http\Controllers\Processor\DocumentController::class, 'cityBudgetUpdateRemarks']);

    Route::post('/document-city-accounting-status/{acctgId}', [App\Http\Controllers\Processor\DocumentController::class, 'cityAccountingUpdateStatus']);
    Route::post('/document-city-accounting-remarks/{acctgId}', [App\Http\Controllers\Processor\DocumentController::class, 'cityAccountingUpdateRemarks']);

    Route::post('/document-city-treasurer-status/{acctgId}', [App\Http\Controllers\Processor\DocumentController::class, 'cityTreasurerUpdateStatus']);
    Route::post('/document-city-treasurer-remarks/{acctgId}', [App\Http\Controllers\Processor\DocumentController::class, 'cityTreasurerUpdateRemarks']);

    Route::post('/document-college-accounting-status/{acctgId}', [App\Http\Controllers\Processor\DocumentController::class, 'collegeAccountingUpdateStatus']);
    Route::post('/document-college-accounting-remarks/{acctgId}', [App\Http\Controllers\Processor\DocumentController::class, 'collegeAccountingUpdateRemarks']);

});








Route::get('/session', function(){
    return Session::all();
});


Route::get('/applogout', function(Request $req){
    \Auth::logout();
    $req->session()->invalidate();
    $req->session()->regenerateToken();
});

//hello kigwa//
//Testingtesting
//okay
Route::get('/test', function(){
    return 'hi i am test';
});
Route::get('/student-profile', function(){
    return  view('student.student-profile');
});
