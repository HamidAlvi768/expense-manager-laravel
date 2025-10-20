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
use App\Models\Keyword;

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
                $tokenExpiry = Carbon::now()->addHour(24);
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
        $account_list = $account->with('accountType:id,title')->where('user_id', $user_id)->orderBy('created_at', 'desc')->get();
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
            $date = date('Y-m-d', strtotime($request->input('date')));
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
                'user_id' => $user_id,
                'account_id' => $account_title_id,
                'income_category_id' => $income_category_id,
                'amount' => $amount,
                'income_date' => $date,
                'description' => $description
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
            // $authUser = $this->userAuth($request);
            // if (!$authUser) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'User not authenticated',
            //         'token' => $request->bearerToken()
            //     ], 401);
            // }
            // $user_id = $authUser->id;
            $user_id = $request->input('user_id');
            if (is_null($user_id)) {
                return response()->json(['status' => 'error', 'message' => 'User ID is required'], 404);
            }

            $query = Income::with('account:id,account_title', 'incomeCategory:id,title')
                ->where('user_id', $user_id);

            $hasFilters = collect([
                'date_from',
                'date_to',
                'account_id',
                'income_category_id',
                'amount',
                'description'
            ])->contains(fn($key) => $request->filled($key));

            if ($hasFilters) {
                if (!empty($request->date_from) && !empty($request->date_to)) {
                    $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
                } elseif (!empty($request->date_from)) {
                    $query->whereDate('created_at', '>=', $request->date_from);
                } elseif (!empty($request->date_to)) {
                    $query->whereDate('created_at', '<=', $request->date_to);
                }

                if (!empty($request->account_id)) {
                    $query->where('account_id', $request->account_id);
                }

                if (!empty($request->income_category_id)) {
                    $query->where('income_category_id', $request->income_category_id);
                }

                if (!empty($request->amount)) {
                    $query->where('amount', $request->amount);
                }

                if (!empty($request->description)) {
                    $query->where('description', 'like', "%{$request->description}%");
                }
            }

            $incomes = $query->get();

            if ($incomes->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $hasFilters
                        ? 'No income found for given filters'
                        : 'No income records found'
                ], 404);
            }

            $data = $incomes->map(function ($income) {
                return [
                    'user_id' => $income->user_id,
                    'account' => $income->account->account_title ?? null,
                    'income_category' => $income->incomeCategory->title ?? null,
                    'amount' => $income->amount,
                    'description' => $income->description,
                    'income_date' => $income->income_date,
                ];
            });

            return response()->json([
                'status' => 'success',
                'filters_applied' => $hasFilters,
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
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
            // $date = $request->input('expense_date');
            $date = date('Y-m-d', strtotime($request->input('expense_date')));
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
                'message' => "something went wrong" . $e->getMessage(),
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
            if (is_null($user_id)) {
                return response()->json(['status' => 'error', 'message' => 'User ID is required'], 404);
            }

            $query = Expense::with('account:id,account_title', 'expenseCategory:id,title')
                ->where('user_id', $user_id);

            $hasFilters = collect([
                'date_from',
                'date_to',
                'account_id',
                'expense_category_id',
                'amount',
                'description'
            ])->contains(fn($key) => $request->filled($key));

            if ($hasFilters) {
                if (!empty($request->date_from) && !empty($request->date_to)) {
                    $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
                } elseif (!empty($request->date_from)) {
                    $query->whereDate('created_at', '>=', $request->date_from);
                } elseif (!empty($request->date_to)) {
                    $query->whereDate('created_at', '<=', $request->date_to);
                }

                if (!empty($request->account_id)) {
                    $query->where('account_id', $request->account_id);
                }

                if (!empty($request->expense_category_id)) {
                    $query->where('expense_category_id', $request->expense_category_id);
                }

                if (!empty($request->amount)) {
                    $query->where('amount', $request->amount);
                }

                if (!empty($request->description)) {
                    $query->where('description', 'like', "%{$request->description}%");
                }
            }

            $expenses = $query->get();

            if ($expenses->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $hasFilters
                        ? 'No expense found for given filters'
                        : 'No expenses found'
                ], 404);
            }

            $data = $expenses->map(function ($expense) {
                return [
                    'user_id' => $expense->user_id,
                    'account' => $expense->account->account_title ?? null,
                    'expense_category' => $expense->expenseCategory->title ?? null,
                    'amount' => $expense->amount,
                    'description' => $expense->description,
                    'expense_date' => $expense->expense_date,
                ];
            });

            return response()->json([
                'status' => 'success',
                'filters_applied' => $hasFilters,
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
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
            // $transfer_date = $request->input('transfer_date');
            $transfer_date = date('Y-m-d', strtotime($request->input('transfer_date')));


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
                'message' => "something went wrong" . $e->getMessage(),
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

            $query = Transfer::with(['fromAccount', 'toAccount'])
                ->where('user_id', $user_id);

            $hasFilters = collect([
                'date_from',
                'date_to',
                'from_account_id',
                'to_account_id',
                'amount',
                'notes'
            ])->contains(fn($key) => $request->filled($key));

            if ($hasFilters) {
                if (!empty($request->date_from) && !empty($request->date_to)) {
                    $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
                } elseif (!empty($request->date_from)) {
                    $query->whereDate('created_at', '>=', $request->date_from);
                } elseif (!empty($request->date_to)) {
                    $query->whereDate('created_at', '<=', $request->date_to);
                }

                if (!empty($request->from_account_id)) {
                    $query->where('from_account_id', $request->from_account_id);
                }

                if (!empty($request->to_account_id)) {
                    $query->where('to_account_id', $request->to_account_id);
                }

                if (!empty($request->amount)) {
                    $query->where('transfer_amount', $request->amount);
                }

                if (!empty($request->notes)) {
                    $query->where('notes', 'like', "%{$request->notes}%");
                }
            }

            $transactions = $query->get();

            if ($transactions->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $hasFilters
                        ? 'No transactions found for given filters'
                        : 'No transfer records found',
                ], 404);
            }

            $data = [];
            foreach ($transactions as $transaction) {
                $data[] = [
                    'user_id' => $transaction->user_id,
                    'from_account_id' => $transaction->from_account_id,
                    'from_account_name' => $transaction->fromAccount->account_title ?? 'N/A',
                    'to_account_id' => $transaction->to_account_id,
                    'to_account_name' => $transaction->toAccount->account_title ?? 'N/A',
                    'transfer_amount' => $transaction->transfer_amount,
                    'note' => $transaction->notes,
                    'description' => $transaction->description,
                    'transfer_date' => $transaction->transfer_date,
                    'status' => $transaction->status,
                ];
            }

            return response()->json([
                'status' => 'success',
                'data' => $data,
                'filters_applied' => $hasFilters ? true : false
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage(),
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
                $monthName = date("F", mktime(0, 0, 0, (int) $bg->month, 1));
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
    public function allAccount(Request $request)
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
            $accounts = Account::with('accountType')->where('user_id', $user_id)->get();
            $data = [];
            foreach ($accounts as $account) {
                $data[] = [
                    'user_id' => $user_id,
                    'account' => $account->account_title,
                    'account_type' => $account->accountType->title
                ];
            }
            return response()->json([
                'status' => 'succes',
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
    public function listExpenseCategory(Request $request)
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
            $expenses = DdExpenseCategory::all();
            $data = [];
            foreach ($expenses as $expense) {
                $data[] = [
                    'id' => $expense->id,
                    'title' => $expense->title,
                    'status' => $expense->status
                ];
            }

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => "something went wrong" . $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ];
        }
    }
    public function listIncomeCategory(Request $request)
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
            $incomes = DdIncomeCategory::all();
            $data = [];
            foreach ($incomes as $income) {
                $data[] = [
                    'id' => $income->id,
                    'title' => $income->title,
                    'status' => $income->status
                ];
            }
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => "something went wrong" . $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ];
        }
    }
    public function transferSearch(Request $request)
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
            $query = Transfer::query();
            $query->where('user_id', $user_id);
            // if ($request->has('user_id') && !empty($request->user_id)) {
            //     $query->where('user_id', $request->user_id);
            // } else {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'user_id required hai'
            //     ]);
            // }
            if (!empty($request->date_from) && !empty($request->date_to)) {
                $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
            } elseif (!empty($request->date_from)) {
                $query->whereDate('created_at', '>=', $request->date_from);
            } elseif (!empty($request->date_to)) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }
            if (!empty($request->from_account_id)) {
                $query->where('from_account_id', $request->from_account_id);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'select the account from'
                ]);
            }
            if (!empty($request->to_account_id)) {
                $query->where('to_account_id', $request->to_account_id);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'select the account to'
                ]);
            }

            if (!empty($request->amount)) {
                $query->where('transfer_amount', $request->amount);
            }
            if (!empty($request->notes)) {
                $query->where('notes', 'like', "%{$request->notes}%");
            }
            $transactions = $query->with(['fromAccount', 'toAccount'])->get();
            $data = [];
            foreach ($transactions as $transaction) {
                $data[] = [
                    'user_id' => $transaction->user_id,
                    'fromAccount' => $transaction->fromAccount->account_title,
                    'toAccount' => $transaction->toAccount->account_title,
                    'transfer_ammount' => $transaction->transfer_amount,
                    'note' => $transaction->notes,
                    'description' => $transaction->description,
                    'transfer_date' => $transaction->transfer_date,
                    'status' => $transaction->status,
                ];
            }
            if ($transactions->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No transactions found for given filters'
                ]);
            }
            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',

            ]);
        }
    }

    public function accountType(Request $request)
    {
        try {
            $accounts = DdAccountType::all();
            if (!$accounts) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'no account type exist'
                ]);
            }
            $data = [];
            foreach ($accounts as $account) {
                $data[] = [

                    'account_type_id' => $account->id,
                    'account_type' => $account->title,
                ];
            }
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                $e->getCode(),
                $e->getLine()
            ], 500);
        }
    }
    public function list(Request $request)
    {
        try {
            //income
            // $user_id = $request->input('user_id');
            $authUser = $this->userAuth($request);
            if (!$authUser) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated',
                    'token' => $request->bearerToken()
                ], 401);
            }
            $user_id = $authUser->id;
            $accounts = Account::where('user_id', $user_id)->get();
            $incomeCategory = DdIncomeCategory::get();
            $expenseCategory = DdExpenseCategory::get();
            $accountFrom = [];
            $accountTo = [];
            $income = [];
            $expense = [];
            $accountData = [];
            foreach ($accounts as $account) {
                $accountData[] = [
                    'account_id' => $account->id,
                    'account_title' => $account->account_title
                ];
            }
            foreach ($incomeCategory as $category) {
                $income[] = [
                    'income_category_id' => $category->id,
                    'income_category_title' => $category->title
                ];
            }
            foreach ($expenseCategory as $exp) {
                $expense[] = [
                    'expense_category_id' => $exp->id,
                    'expense_category_title' => $exp->title
                ];
            }
            $accountFrom = $accountData;
            $accountTo = $accountData;
            return response()->json([
                'status' => 'success',
                'data' => [
                    'account' => $accountData,
                    'income_category' => $income,
                    'expense_category' => $expense,
                    'accountFrom' => $accountFrom,
                    'accountTo' => $accountTo
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                $e->getCode(),
                $e->getLine()
            ], 500);
        }
    }
    public function keywords(Request $request)
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
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'type' => 'required|string',
                'category_id' => 'required|integer'
            ]);
            $keywords = Keyword::Create([
                'title' => $validated['title'],
                'type' => $validated['type'],
                'category_id' => $validated['category_id'],
            ]);
            $data = [
                'id' => $keywords->id,
                'title' => $keywords->title,
                'type' => $keywords->type,
                'category_id' => $keywords->category_id
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
    public function keywordsList(Request $request)
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
            $keywords = Keyword::with('category')->get();
            $data = [];
            foreach ($keywords as $keyword) {
                $data[] = [
                    'id' => $keyword->id,
                    'title' => $keyword->title,
                    'type' => $keyword->type,
                    'category_id' => $keyword->category_id,
                    'category_title' => $keyword->category->title ?? null
                ];
            }
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
