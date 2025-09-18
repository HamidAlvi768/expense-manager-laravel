<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exports\GenericExport;
use App\Models\DdAccountType;
use App\Models\Transaction;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use App\Mail\ExceptionOccurred;
class AccountController extends Controller
{

    public function index(Request $request)
    {

        if ($request->export) {
            return $this->doExport($request);
        }


        $accounts = $this->filter($request)->orderBy('id', 'desc')->paginate(10);
        $accountTypes = DdAccountType::where('status', '1')->get();

        $assets = $accounts->where('balance', '>', 0)->sum('balance');
        $liabilities = $accounts->where('balance', '<', 0)->sum('balance');
        $total = $accounts->sum('balance');

        return view('accounts.index', compact('accounts', 'accountTypes', 'assets', 'liabilities', 'total'));
    }

    public function doExport(Request $request)
    {
        $accounts = $this->filter($request)
            ->with(['user']) // eager load relationships
            ->get();
        $data = $accounts->map(function ($account) {
            return [
                'ID' => $account->id,
                'User Name' => $account->user->name ?? 'N/A',
                'Account Title' => $account->account_title,
                'Account Type' => $account->accountType->title,
                'Balance' => $account->balance,
                'Description' => $account->notes,
                'Created At' => $account->created_at,
                'Updated At' => $account->updated_at,
            ];
        })->toArray();

        // Define headers for the export
        $headers = ['ID', 'User Name', 'Account Title', 'Account Type', 'Balance', 'Notes', 'Created At', 'Updated At'];

        return Excel::download(new GenericExport($data, $headers), 'Accounts.xlsx');
    }

    private function filter(Request $request)
    {
        $query = Account::query();

        // Filter by Account Title
        if ($request->account_title) {
            $query->where('account_title', 'like', '%' . $request->account_title . '%');
        }

        // Filter by Account Type
        if ($request->account_type_id) {
            $query->where('account_type_id', $request->account_type_id);
        }

        $query->where('user_id', auth()->id());


        return $query;
    }


    public function create()
    {
        $users = User::select('id', 'name')->orderBy('id', 'desc')->get();
        $accountTypes = DdAccountType::where('status', '1')->select('id', 'title')->orderBy('id', 'desc')->get();
        return view('accounts.create', compact('users', 'accountTypes'));
    }


    public function store(Request $request)
    {
        $this->validation($request);
        $data = $request->only(['account_title', 'account_type_id', 'balance', 'notes']);

        DB::transaction(function () use ($data, &$account) {
            $data['user_id'] = auth()->id();
            $data['created_by'] = auth()->id();
            $data['deposit'] = $data['balance'];
            $data['withdrawal'] = 0;
            $data['total'] = $data['balance'];
            $account = Account::create($data);

            Transaction::create([
                'account_id' => $account->id,
                'reference_id' => null, // No related income/expense/transfer record
                'reference_type' => null, // No specific reference type for this case
                'transaction_type' => 'income', // Treat as an income/deposit transaction
                'amount' => $account->balance,
                'description' => 'Initial account balance',
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()->route('accounts.index', $account->id)
            ->with('success', trans('Account Added Successfully'));
    }



    public function show(Account $account)
    {
        return view('accounts.show', compact('account'));
    }

    public function edit(Account $account)
    {
        $accountTypes = DdAccountType::where('status', '1')->select('id', 'title')->orderBy('id', 'desc')->get();
        return view('accounts.edit', compact('account', 'accountTypes'));
    }


    public function update(Request $request, Account $account)
    {
        $this->validation($request, $account->user_id);
        $data = $request->only(['account_title', 'account_type_id', 'notes',]);

        DB::transaction(function () use ($data, $account) {
            $account->updated_by = auth()->id();
            $account->update($data);
        });

        return redirect()->route('accounts.index', $account->id)
            ->with('success', trans('Account Updated Successfully'));
    }


    public function destroy(Account $account)
    {
        $account->delete();
        return redirect()->route('accounts.index')->with('success', trans('Account Deleted Successfully'));
    }


    private function validation(Request $request, $id = 0)
    {

        $request->validate([
            'account_type_id' => 'required|exists:dd_account_types,id',
            'account_title' => 'required|string',
            'balance' => 'nullable',
            'integer',
            'notes' => 'nullable',
            'string',
        ]);
    }
}
