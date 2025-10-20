<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MobileController\MobileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

// Route::get('/doctor-list', [App\Http\Controllers\DoctorDetailController::class, 'getDoctorList']);
// Route::get('/doctor-schedules', [App\Http\Controllers\PatientAppointmentController::class, 'getScheduleDoctorWise']);
// Route::get('/book-schedules', [App\Http\Controllers\PatientAppointmentController::class, 'bookAppointment']);
//Login and logout
Route::post('/login', [MobileController::class, 'login']);
Route::post('/logout', [MobileController::class, 'logout']);
Route::post('/add-account', [MobileController::class, 'addAccount']);
//account list
Route::get('/account-list', [MobileController::class, 'accountList']);
//add income
Route::post('/add-income', [MobileController::class, 'addIncome']);
//income list
Route::get('/income-list', [MobileController::class, 'incomeList']);
//add expense
Route::post('/add-expense', [MobileController::class, 'addExpense']);
//expense list
Route::get('/expense-list', [MobileController::class, 'expenseList']);
//transfer amount form one account to another
Route::post('/transfer', [MobileController::class, 'transfer']);
//transfer list
Route::get('/transfer-list', [MobileController::class, 'transferList']);
//budget allocation
Route::post('budget-allocation', [MobileController::class, 'budgetAllocation']);
//budget list 
Route::get('budget-list', [MobileController::class, 'budgetList']);
//add new expense category
Route::post('add-expense-category', [MobileController::class, 'addExpenceCategory']);
Route::post('add-income-category', [MobileController::class, 'addIncomeCategory']);
Route::post('add-account-type', [MobileController::class, 'addAccountType']);
Route::get('all-account', [MobileController::class, 'allAccount']);
//list of catergories
Route::get('list-expense-category', [MobileController::class, 'listExpenseCategory']);
Route::get('list-income-category', [MobileController::class, 'listIncomeCategory']);
//transfer felter
Route::get('transfer-filter', [MobileController::class, 'transferSearch']);
//income filter
Route::get('income-filter', [MobileController::class, 'incomeSearch']);
//expense filter
Route::get('expense-filter', [MobileController::class, 'expenseSearch']);
//account type
Route::get('account-type', [MobileController::class, 'accountType']);
//single api for the income account category accountType
Route::get('list', [MobileController::class, 'list']);
Route::post('keywords', [MobileController::class, 'keywords']);
Route::get('keywords-list', [MobileController::class, 'keywordsList']);
