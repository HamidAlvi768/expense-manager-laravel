<?php

namespace App\Http\Controllers\MobileController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\User;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Transfer;
use App\Models\Budget;
use App\Models\DdExpenseCategory;
use App\Models\DdIncomeCategory;
use App\Models\DdAccountType;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

use function PHPUnit\Framework\isNull;

class MobileController extends Controller
{
    //authenticate user by access token
    private function userAuth(Request $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return null;
        }
        $user = User::where('access_token', $token)->first();
        if ($user) {
            return $user;
        }
        return null;
    }
    // login
    public function login(Request $request)
    {

        try {
            // $email = $request->input('email');
            $data = $request->json()->all();
            $email = $data['email'] ?? null;
            $password = $data['password'] ?? null;
            $user = User::where('email', $email)->first();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }
            if (Hash::check($password, $user->password)) {
                $accessToken = Str::random(100);
                $refreshToken = Str::random(100);
                $tokenExpiry = Carbon::now()->addHour();
                $user->access_token = $accessToken;
                $user->refresh_token = $refreshToken;
                $user->token_expiry = $tokenExpiry;
                $user->save();
                $data = [
                    'status' => 'success',
                    'message' => 'Login successful',
                    'data' => [
                        'access_token' => $accessToken,
                        'refresh_token' => $accessToken,
                        'user' => [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'access_token' => $accessToken,
                            'refresh_token' => $refreshToken,
                            'token_expiry' => $tokenExpiry->toDateTimeString(),
                        ]
                    ]
                ];
                return response()->json(
                    $data
                );
            } else {
                return response()->json(['status' => 'error', 'message' => 'Wrong password!'], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => "something went wrong" . $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
    //logout
    public function logout(Request $request)
    {
        try {
            $authUser = $this->userAuth($request);
            if (!$authUser) {
                return [
                    'status' => 'error',
                    'message' => 'User not authenticated',
                    'token' => $request->bearerToken()
                ];
            }
            $authUser->access_token = "";
            $authUser->refresh_token = "";
            $authUser->token_expiry = null;
            $authUser->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Logout successful'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => "something went wrong" + $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
    //add account 
    public function addAccount(Request $request)
    {
        try {
            $authUser = $this->userAuth($request);
            if (!$authUser) {
                return [
                    'status' => 'error',
                    'message' => 'User not authenticated',
                    'token' => $request->bearerToken()
                ];
            }
            $account_title = $request->input('account_title');
            $account_type_id = $request->input('account_type_id');
            $initial_balance = $request->input('balance');
            $note = $request->input('note');
            $user_id = $authUser->id;
            if (is_Null($account_title) || is_Null($account_type_id) || is_Null($initial_balance) || is_Null($user_id)) {
                return response()->json(['status' => 'error', 'message' => 'All fields are required'], 404);
            }
            if ($initial_balance === null || $initial_balance == 0) {
                return response()->json(['status' => 'error', 'message' => 'Balance cannot be null or zero'], 404);
            }
            $account = new Account();
            $account = $account->Create([
                'account_title' => $account_title,
                'account_type_id' => $account_type_id,
                'balance' => $initial_balance,
                'deposit' => $initial_balance,
                'total' => $initial_balance,
                'note' => $note,
                'user_id' => $user_id
            ]);
            $account->save();
            $data = [
                'title' => $account_title,
                'account_type_id' => $account_type_id,
                'initial_balance' => $initial_balance,
                'total' => $initial_balance,

                'note' => $note,
                'user_id' => $user_id
            ];
            return response()->json(
                [
                    'status' => 'success',
                    'data' => $data
                ]
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => "something went wrong" + $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
    //account list of user
    public function accountList(Request $request)
    {
        $authUser = $this->userAuth($request);
        if (!$authUser) {
            return [
                'status' => 'error',
                'message' => 'User not authenticated',
                'token' => $request->bearerToken()
            ];
        }
        $user_id = $authUser->id;
        if (is_Null($user_id)) {
            return response()->json(['status' => 'error', 'message' => 'User id is required'], 404);
        }
        $account = new Account();
        $account_list = $account->with('accountType:id,title')->where('user_id', $user_id)->get();
        if ($account_list->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'No account found'], 404);
        }
        $data = [];
        foreach ($account_list as $account) {
            $data[] = [
                'id' => $account->id,
                'title' => $account->account_title,
                'account_type' => $account->accountType ? $account->accountType->title : null,
                'deposit' => $account->deposit,
                'withdrawal' => $account->withdrawal,
                'balance' => $account->balance,
                'total' => $account->total,
                'notes' => $account->notes,
            ];
        }
        $account_list = $data;
        return response()->json(
            [
                'status' => 'success',
                'data' => $account_list
            ]
        );
    }
    //add income
    public function addIncome(Request $request)
    {
        try {
            $authUser = $this->userAuth($request);
            if (!$authUser) {
                return [
                    'status' => 'error',
                    'message' => 'User not authenticated',
                    'token' => $request->bearerToken()
                ];
            }
            $user_id = $authUser->id;
            $account_title_id = $request->input('account_title_id');
            $date = $request->input('date');
            $income_category_id = $request->input('income_category_id');
            $amount = $request->input('amount');
            $description = $request->input('description');
            if (
                empty($account_title_id) || empty($date) || empty($income_category_id) ||
                empty($amount) || empty($user_id)
            ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'all fields are required'
                ], 404);
            }
            $account = Account::find($account_title_id);
            if (!$account) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'account not exist please select another account'
                ], 404);
            }

            $income = Income::create([
                'user_id'       => $user_id,
                'account_id'    => $account_title_id,
                'income_category_id'   => $income_category_id,
                'amount'        => $amount,
                'income_date'   => $date,
                'description'   => $description
            ]);
            $account->balance += $amount;
            $account->deposit += $amount;
            $account->save();
            $account->total = ($account->deposit) - ($account->withdrawal);
            $account->save();
            return response()->json([
                'status' => 'success',
                'user_id' => $income->user_id,
                'account_id' => $income->account_id,
                'income_category_id' => $income->income_category_id,
                'amount' => $income->amount,
                'income_date' => $income->income_date,
                'description' => $income->description
            ]);
            // $income->user_id = $user_id;
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => "something went wrong" + $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
    //income list
    public function incomeList(Request $request)
    {
        try {
            $authUser = $this->userAuth($request);
            if (!$authUser) {
                return [
                    'status' => 'error',
                    'message' => 'User not authenticated',
                    'token' => $request->bearerToken()
                ];
            }
            $user_id = $authUser->id;
            if (is_Null($user_id)) {
                return response()->json(['status' => 'error', 'message' => 'User id is required'], 404);
            }
            $incomeDetails = Income::with(
                'account:id,account_title',
                'incomeCategory:id,title'
            )->where('user_id', $user_id)->get();
            //fetch income list from database
            $income_list = [];
            foreach ($incomeDetails as $income) {
                $income_list[] = [
                    'user_id' => $income->user_id,
                    'account_title' => $income->account ? $income->account->account_title : null,
                    'income_category' => $income->incomeCategory ? $income->incomeCategory->title : null,
                    'amount' => $income->amount,
                    'description' => $income->description,
                    'income_date' => $income->income_date,
                ];
            }

            return response()->json(
                [
                    'status' => 'success',
                    'data' => $income_list
                ]
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => "something went wrong" . $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
    //add expense 
    public function addExpense(Request $request)
    {
        try {
            $authUser = $this->userAuth($request);
            if (!$authUser) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated',
                    'token' => $request->bearerToken()
                ], 401);
            }
            $user_id = $authUser->id;
            $account_id = $request->input('account_id');
            $date = $request->input('expense_date');
            $expense_category_id = $request->input('expense_category_id');
            $amount = $request->input('amount');
            $description = $request->input('description');
            $reason = $request->input('reason');
            if (empty($account_id) || empty($date) || empty($expense_category_id) || empty($amount)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'all fields are mandatory'
                ], 404);
            }
            $account = Account::find($account_id);
            if (!$account) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'account not exist'
                ]);
            }
            $expense = Expense::create([
                'user_id' => $user_id,
                'account_id' => $account_id,
                'expense_date' => $date,
                'expense_category_id' => $expense_category_id,
                'amount' => $amount,
                'description' => $description,
                'reason' => $reason
            ]);
            $expense->save();
            $account->balance -= $amount;
            $account->withdrawal += $amount;
            $account->total = ($account->deposit) - ($account->withdrawal);
            $account->save();
            return response()->json([
                'status' => 'success',
                'user_id' => $expense->user_id,
                'account_id' => $expense->account_id,
                'expense_date' => $expense->expense_date,
                'expense_category_id' => $expense->category_id,
                'amount' => $expense->amount,
                'description' => $expense->description,
                'reason' => $expense->reason
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => "something went wrong" + $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
    public function expenseList(Request $request)
    {
        try {
            $authUser = $this->userAuth($request);
            if (!$authUser) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated',
                    'token' => $request->bearerToken()
                ], 401);
            }
            $user_id = $authUser->id;
            if (is_Null($user_id)) {
                return response()->json(['status' => 'error', 'message' => 'User id is required'], 404);
            }
            $expenseDetails = Expense::with('account:id,account_title', 'expenseCategory:id,title')->where('user_id', $user_id)->get();
            //fetch expense list from database
            $expense_list = []; //dummy data
            foreach ($expenseDetails as $expense) {
                $expense_list[] = [
                    'user_id' => $expense->user_id,
                    'account_title' => $expense->account ? $expense->account->account_title : null,
                    'expense_category' => $expense->expenseCategory ? $expense->expenseCategory->title : null,
                    'amount' => $expense->amount,
                    'description' => $expense->description,
                    'expense_date' => $expense->expense_date,
                ];
            }
            return response()->json(
                [
                    'status' => 'success',
                    'data' => $expense_list
                ]
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => "something went wrong" + $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
    //transfer amount from one account to another
    public function transfer(Request $request)
    {
        try {
            $authUser = $this->userAuth($request);
            if (!$authUser) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated',
                    'token' => $request->bearerToken()
                ], 401);
            }
            $user_id = $authUser->id;
            $from_account_id = $request->input('from_account_id');
            $to_account_id = $request->input('to_account_id');
            $amount = $request->input('amount');
            $description = $request->input('description');
            $transfer_date = $request->input('transfer_date');

            if (is_Null($from_account_id) || is_Null($to_account_id) || is_Null($amount) || is_Null($user_id)) {
                return response()->json(['status' => 'error', 'message' => 'All fields are required'], 404);
            }
            if ($from_account_id == $to_account_id) {
                return response()->json(['status' => 'error', 'message' => 'From and To account cannot be same'], 404);
            }
            if ($amount <= 0) {
                return response()->json(['status' => 'error', 'message' => 'Amount must be greater than zero'], 404);
            }
            //check from account balance
            $fromAccount = Account::where('id', $from_account_id)->where('user_id', $user_id)->first();
            if (!$fromAccount) {
                return response()->json(['status' => 'error', 'message' => 'From account not found'], 404);
            }
            if ($fromAccount->balance < $amount) {
                return response()->json(['status' => 'error', 'message' => 'Insufficient balance in from account'], 404);
            }
            //check to account
            $toAccount = Account::where('id', $to_account_id)->where('user_id', $user_id)->first();
            if (!$toAccount) {
                return response()->json(['status' => 'error', 'message' => 'To account not found'], 404);
            }
            //deduct amount from from account
            $fromAccount->balance -= $amount;
            $fromAccount->withdrawal += $amount;
            $fromAccount->total -= $amount;
            $fromAccount->save();
            //add amount to to account
            $toAccount->balance += $amount;
            $toAccount->deposit += $amount;
            $toAccount->save();
            $toAccount->total = ($toAccount->deposit) - ($toAccount->withdrawal);
            $toAccount->save();
            $transfer_description = $description ? $description : "Transfer from account ID $from_account_id to account ID $to_account_id";
            $transfer = new Transfer();
            $transfer->Create([
                'from_account_id' => $from_account_id,
                'to_account_id' => $to_account_id,
                'transfer_amount' => $amount,
                'description' => $transfer_description,
                'user_id' => $user_id,
                'transfer_date' => $transfer_date
            ]);

            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Amount transferred successfully'
                ]
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => "something went wrong" + $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
    //transfer index
    public function transferList(Request $request)
    {
        try {
            $authUser = $this->userAuth($request);
            if (!$authUser) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated',
                    'token' => $request->bearerToken()
                ], 401);
            }
            $user_id = $authUser->id;
            $transaction = Transfer::where('user_id', $user_id)->get();
            $data = [];
            foreach ($transaction as $transfer) {
                $data[] = [
                    'From' => $transfer->from_account_id,
                    'To' => $transfer->to_account_id,
                    'amount' => $transfer->transfer_amount,
                    'notes' => $transfer->notes,
                    'date' => $transfer->transfer_date
                ];
            }
            if (!$transfer) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No transfer exist'
                ], 404);
            }
            return response()->json([
                'status' => 'success',
                'date' => $data
            ], 202);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => "something went wrong" + $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
    //allocation of budget for specific month
    public function budgetAllocation(Request $request)
    {
        try {
            $authUser = $this->userAuth($request);
            if (!$authUser) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated',
                    'token' => $request->bearerToken()
                ], 401);
            }
            $user_id = $authUser->id;
            $month = $request->input('month');
            $expense_category_id = $request->input('expense_category_id');
            $amount = $request->input('amount');
            $description = $request->input('description');
            if (empty($user_id) || empty($month) || empty($expense_category_id) || empty($amount) || empty($description)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'all fields are required'
                ], 404);
            }
            $budget = Budget::create([
                'user_id' => $user_id,
                'month' => $month,
                'expense_category_id' => $expense_category_id,
                'amount' => $amount,
                'description' => $description
            ]);
            $budget->save();
            return response()->json([
                'status' => 'success',
                'user_id' => $budget->user_id,
                '$month' => $budget->month,
                'expense_category_id' => $budget->expense_category_id,
                'amount' => $budget->amount,
                'description' => $budget->description
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
    //budget list
    public function budgetList(Request $request)
    {
        try {
            $authUser = $this->userAuth($request);
            if (!$authUser) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated',
                    'token' => $request->bearerToken()
                ], 401);
            }
            $user_id = $authUser->id;
            if (!$user_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'user not exist'
                ]);
            }
            $budget = Budget::with('expenseCategory:id,title')
                ->where('user_id', $user_id)->get();
            $data = [];
            foreach ($budget as $bg) {
                $monthName = date("F", mktime(0, 0, 0, (int)$bg->month, 1));
                $data[] = [
                    'user_id' => $user_id,
                    'category' => $bg->expenseCategory->title,
                    'month' => $monthName,
                    'amount' => $bg->amount,
                    'description' => $bg->description,
                ];
            }
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => "something went wrong" + $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
    //add new expense categories
    public function addExpenceCategory(Request $request)
    {
        try {
            $authUser = $this->userAuth($request);
            if (!$authUser) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated',
                    'token' => $request->bearerToken()
                ], 401);
            }
            $user_id = $authUser->id;
            $title = $request->input('title');
            $expense = DdExpenseCategory::create([
                'title' => $title,
                'created_by' => $user_id
            ]);
            $data = [
                'id' => $expense->id,
                'title' => $expense->title
            ];
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => "something went wrong" . $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
    //add new income categories
    public function addIncomeCategory(Request $request)
    {
        try {
            $authUser = $this->userAuth($request);
            if (!$authUser) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated',
                    'token' => $request->bearerToken()
                ], 401);
            }
            $user_id = $authUser->id;
            $title = $request->input('title');
            $income = DdIncomeCategory::create([
                'title' => $title,
                'created_by' => $user_id
            ]);
            $data = [
                'id' => $income->id,
                'title' => $income->title
            ];
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => "something went wrong" . $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
    public function addAccountType(Request $request)
    {
        try {
            $authUser = $this->userAuth($request);
            if (!$authUser) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated',
                    'token' => $request->bearerToken()
                ], 401);
            }
            $user_id = $authUser->id;
            $title = $request->input('title');
            $account = DdAccountType::create([
                'title' => $title,
                'created_by' => $user_id
            ]);
            $data = [
                'id' => $account->id,
                'title' => $account->title
            ];
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => "something went wrong" . $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
}
