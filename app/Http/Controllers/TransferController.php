<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Transfer;
use App\Models\DdExpenseCategory;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Exports\GenericExport;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Carbon;


class TransferController extends Controller
{

    public function index(Request $request)
    {
        if ($request->export) {
            return $this->doExport($request);
        }
<<<<<<< HEAD
    
=======

>>>>>>> 59200bb (Initial commit with expense manager code)
        $transfers = $this->filter($request)
            ->with(['fromAccount', 'toAccount', 'user'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        $accounts = Account::where('user_id', auth()->id())->get();

        $total = $accounts->sum('transfer_amount');

<<<<<<< HEAD
        return view('transfer.index', compact('transfers','accounts','total'));
    }
    
=======
        return view('transfer.index', compact('transfers', 'accounts', 'total'));
    }

>>>>>>> 59200bb (Initial commit with expense manager code)
    public function doExport(Request $request)
    {
        $transfers = $this->filter($request)
            ->with(['user', 'fromAccount', 'toAccount'])
            ->get();
<<<<<<< HEAD
    
=======

>>>>>>> 59200bb (Initial commit with expense manager code)
        // Prepare data for export
        $data = $transfers->map(function ($transfer) {
            return [
                'ID' => $transfer->id,
                'User Name' => $transfer->user->name ?? 'N/A',
                'From Account' => $transfer->fromAccount->account_title ?? "-",
                'To Account' => $transfer->toAccount->account_title ?? "-",
                'Amount' => $transfer->transfer_amount,
                'Notes' => $transfer->notes,
                'Transfer Date' => $transfer->transfer_date,
            ];
        })->toArray();
<<<<<<< HEAD
    
        // Define headers for the export
        $headers = ['ID', 'User Name', 'From Account', 'To Account', 'Amount', 'Notes', 'Transfer Date'];
    
        return Excel::download(new GenericExport($data, $headers), 'transfers.xlsx');
    }
    
    private function filter(Request $request)
    {
        $query = Transfer::query();
    
=======

        // Define headers for the export
        $headers = ['ID', 'User Name', 'From Account', 'To Account', 'Amount', 'Notes', 'Transfer Date'];

        return Excel::download(new GenericExport($data, $headers), 'transfers.xlsx');
    }

    private function filter(Request $request)
    {
        $query = Transfer::query();

>>>>>>> 59200bb (Initial commit with expense manager code)
        // Filter by "From Account"
        if ($request->from_account) {
            $query->where('from_account_id', $request->from_account);
        }
<<<<<<< HEAD
    
=======

>>>>>>> 59200bb (Initial commit with expense manager code)
        // Filter by "To Account"
        if ($request->to_account) {
            $query->where('to_account_id', $request->to_account);
        }
<<<<<<< HEAD
    
=======

>>>>>>> 59200bb (Initial commit with expense manager code)
        // Filter by "Amount From" (Minimum Transfer Amount)
        if ($request->amount_from) {
            $query->where('transfer_amount', '>=', $request->amount_from);
        }
<<<<<<< HEAD
    
=======

>>>>>>> 59200bb (Initial commit with expense manager code)
        // Filter by "Amount To" (Maximum Transfer Amount)
        if ($request->amount_to) {
            $query->where('transfer_amount', '<=', $request->amount_to);
        }
<<<<<<< HEAD
    
=======

>>>>>>> 59200bb (Initial commit with expense manager code)
        // Filter by Date Range
        if ($request->start_date && $request->end_date) {
            // If both start_date and end_date are provided
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('transfer_date', [$startDate, $endDate]);
        } elseif ($request->start_date) {
            // If only start_date is provided
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $query->whereDate('transfer_date', $startDate);
        } elseif ($request->end_date) {
            // If only end_date is provided
            $endDate = Carbon::parse($request->end_date)->startOfDay();
            $query->whereDate('transfer_date', $endDate);
        }
        $query->where('user_id', auth()->id());
<<<<<<< HEAD
    
        return $query;
    }
    
    
    
=======

        return $query;
    }



>>>>>>> 59200bb (Initial commit with expense manager code)
    public function create()
    {
        $fromAccounts = Account::where('user_id', auth()->id())->select('id', 'account_title')->orderBy('id', 'desc')->get();
        $toAccounts = Account::where('user_id', auth()->id())->select('id', 'account_title')->orderBy('id', 'desc')->get();
<<<<<<< HEAD
        return view('transfer.create', compact('fromAccounts','toAccounts'));
=======
        return view('transfer.create', compact('fromAccounts', 'toAccounts'));
>>>>>>> 59200bb (Initial commit with expense manager code)
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */




<<<<<<< HEAD
  public function store(Request $request)
{
    $this->validation($request);
    $data = $request->only(['to_account_id','from_account_id', 'transfer_amount', 'notes', 'description','transfer_date']);

    $fromAccount = Account::findOrFail($data['from_account_id']);
    $toAccount = Account::findOrFail($data['to_account_id']);

    DB::transaction(function () use ($data, $fromAccount, $toAccount, &$transfer) {
        $data['user_id'] = auth()->id(); 
        $data['created_by'] = auth()->id(); 
        $transfer = Transfer::create($data);

        $fromAccount->increment('withdrawal', $data['transfer_amount']);
        $fromAccount->decrement('balance', $data['transfer_amount']);
        $fromAccount->decrement('total', $data['transfer_amount']);

        $toAccount->increment('deposit', $data['transfer_amount']);
        $toAccount->increment('balance', $data['transfer_amount']);
        $toAccount->increment('total', $data['transfer_amount']);

        Transaction::create([
            'account_id' => $fromAccount->id,
            'transaction_type' => 'transfer',
            'amount' => $data['transfer_amount'],
            'description' => 'Transfer to account: ' . $toAccount->account_title,
            'reference_id' => $transfer->id,
            'reference_type' => Transfer::class,
            'created_by' => auth()->id(),
        ]);

        Transaction::create([
            'account_id' => $toAccount->id,
            'transaction_type' => 'transfer',
            'amount' => $data['transfer_amount'],
            'description' => 'Transfer from account: ' . $fromAccount->account_title,
            'reference_id' => $transfer->id,
            'reference_type' => Transfer::class,
            'created_by' => auth()->id(),
        ]);

    });
=======
    public function store(Request $request)
    {
        $this->validation($request);
        $data = $request->only(['to_account_id', 'from_account_id', 'transfer_amount', 'notes', 'description', 'transfer_date']);

        $fromAccount = Account::findOrFail($data['from_account_id']);
        $toAccount = Account::findOrFail($data['to_account_id']);

        DB::transaction(function () use ($data, $fromAccount, $toAccount, &$transfer) {
            $data['user_id'] = auth()->id();
            $data['created_by'] = auth()->id();
            $transfer = Transfer::create($data);
            $fromAccount->increment('withdrawal', $data['transfer_amount']);
            $fromAccount->decrement('balance', $data['transfer_amount']);
            $fromAccount->decrement('total', $data['transfer_amount']);
            $toAccount->increment('deposit', $data['transfer_amount']);
            $toAccount->increment('balance', $data['transfer_amount']);
            $toAccount->increment('total', $data['transfer_amount']);
            Transaction::create([
                'account_id' => $fromAccount->id,
                'transaction_type' => 'transfer',
                'amount' => $data['transfer_amount'],
                'description' => 'Transfer to account: ' . $toAccount->account_title,
                'reference_id' => $transfer->id,
                'reference_type' => Transfer::class,
                'created_by' => auth()->id(),
            ]);
            Transaction::create([
                'account_id' => $toAccount->id,
                'transaction_type' => 'transfer',
                'amount' => $data['transfer_amount'],
                'description' => 'Transfer from account: ' . $fromAccount->account_title,
                'reference_id' => $transfer->id,
                'reference_type' => Transfer::class,
                'created_by' => auth()->id(),
            ]);
        });
        return redirect()->route('transfers.index', $transfer->id)
            ->with('success', trans('Transfer Added Successfully'));
    }
>>>>>>> 59200bb (Initial commit with expense manager code)




<<<<<<< HEAD

    return redirect()->route('transfers.edit', $transfer->id)
        ->with('success', trans('Transfer Added Successfully'));
}

     


=======
>>>>>>> 59200bb (Initial commit with expense manager code)
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\income  $income
     * @return \Illuminate\Http\Response
     */
    public function show(Transfer $transfer)
    {
<<<<<<< HEAD
        return view('transfer.show', compact('transfer'));    
=======
        return view('transfer.show', compact('transfer'));
>>>>>>> 59200bb (Initial commit with expense manager code)
    }

    public function edit(Transfer $transfer)
    {
        $fromAccounts = Account::where('user_id', auth()->id())->select('id', 'account_title')->orderBy('id', 'desc')->get();
        $toAccounts = Account::where('user_id', auth()->id())->select('id', 'account_title')->orderBy('id', 'desc')->get();

<<<<<<< HEAD
        return view('transfer.edit', compact('transfer','toAccounts','fromAccounts'));
=======
        return view('transfer.edit', compact('transfer', 'toAccounts', 'fromAccounts'));
>>>>>>> 59200bb (Initial commit with expense manager code)
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\income  $income
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transfer $transfer)
    {
        $this->validation($request, $transfer->user_id);
<<<<<<< HEAD
        $data = $request->only(['to_account_id','from_account_id', 'transfer_amount', 'notes', 'description','transfer_date']);
    
=======
        $data = $request->only(['to_account_id', 'from_account_id', 'transfer_amount', 'notes', 'description', 'transfer_date']);

>>>>>>> 59200bb (Initial commit with expense manager code)
        $fromAccount = Account::findOrFail($data['from_account_id']);
        $toAccount = Account::findOrFail($data['to_account_id']);

        $oldAmount = $transfer->transfer_amount;
        $difference = $data['transfer_amount'] - $oldAmount;

        DB::transaction(function () use ($data, $transfer, $fromAccount, $toAccount, $difference) {

            $fromAccount->increment('withdrawal', $difference);
            $fromAccount->decrement('balance', $difference);
            $fromAccount->decrement('total', $difference);

            // Update the 'toAccount' balance, deposit, and total
            $toAccount->increment('deposit', $difference);
            $toAccount->increment('balance', $difference);
            $toAccount->increment('total', $difference);

<<<<<<< HEAD
            $transfer->updated_by = auth()->id(); 
            $transfer->update($data);

            $fromTransaction = Transaction::where('account_id', $fromAccount->id)
            ->where('reference_id', $transfer->id)
            ->where('reference_type', Transfer::class)
            ->firstOrFail();
        
=======
            $transfer->updated_by = auth()->id();
            $transfer->update($data);

            $fromTransaction = Transaction::where('account_id', $fromAccount->id)
                ->where('reference_id', $transfer->id)
                ->where('reference_type', Transfer::class)
                ->firstOrFail();

>>>>>>> 59200bb (Initial commit with expense manager code)
            $fromTransaction->update([
                'amount' => $data['transfer_amount'],
                'description' => 'Transfer to account: ' . $toAccount->account_title,
                'updated_by' => auth()->id(),
            ]);

            // Update the transaction for the 'toAccount'
            $toTransaction = Transaction::where('account_id', $toAccount->id)
<<<<<<< HEAD
            ->where('reference_id', $transfer->id)
            ->where('reference_type', Transfer::class)
            ->firstOrFail();
        
=======
                ->where('reference_id', $transfer->id)
                ->where('reference_type', Transfer::class)
                ->firstOrFail();

>>>>>>> 59200bb (Initial commit with expense manager code)
            $toTransaction->update([
                'amount' => $data['transfer_amount'],
                'description' => 'Transfer from account: ' . $fromAccount->account_title,
                'updated_by' => auth()->id(),
            ]);
        });

        return redirect()->route('transfers.edit', $transfer->id)
            ->with('success', trans('Transfer Updated Successfully'));
    }
<<<<<<< HEAD
    
=======

>>>>>>> 59200bb (Initial commit with expense manager code)

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\income  $income
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transfer $transfer)
    {
        DB::transaction(function () use ($transfer) {
            // Fetch accounts
            $fromAccount = Account::findOrFail($transfer->from_account_id);
            $toAccount = Account::findOrFail($transfer->to_account_id);
<<<<<<< HEAD
    
=======

>>>>>>> 59200bb (Initial commit with expense manager code)
            // Revert changes to Account A (from_account)
            $fromAccount->decrement('withdrawal', $transfer->transfer_amount);
            $fromAccount->increment('balance', $transfer->transfer_amount);
            $fromAccount->increment('total', $transfer->transfer_amount);
<<<<<<< HEAD
    
=======

>>>>>>> 59200bb (Initial commit with expense manager code)
            // Revert changes to Account B (to_account)
            $toAccount->decrement('deposit', $transfer->transfer_amount);
            $toAccount->decrement('balance', $transfer->transfer_amount);
            $toAccount->decrement('total', $transfer->transfer_amount);
<<<<<<< HEAD
    
=======

>>>>>>> 59200bb (Initial commit with expense manager code)
            // Delete related transactions
            Transaction::where('reference_id', $transfer->id)
                ->where('reference_type', Transfer::class)
                ->delete();
<<<<<<< HEAD
    
            // Delete the transfer record
            $transfer->delete();
        });
    
        return redirect()->route('transfers.index')
            ->with('success', trans('Transfer Deleted Successfully'));
    }
    

    private function validation(Request $request, $id = 0)
    {
    
        $request->validate([
            'from_account_id' => 'required|exists:accounts,id',
            'to_account_id' => 'required|exists:accounts,id',
            'transfer_amount' => 'required','integar',
            'notes' => 'nullable', 'string',
            'description' => 'nullable', 'string',
=======

            // Delete the transfer record
            $transfer->delete();
        });

        return redirect()->route('transfers.index')
            ->with('success', trans('Transfer Deleted Successfully'));
    }


    private function validation(Request $request, $id = 0)
    {

        $request->validate([
            'from_account_id' => 'required|exists:accounts,id',
            'to_account_id' => 'required|exists:accounts,id',
            'transfer_amount' => 'required',
            'integar',
            'notes' => 'nullable',
            'string',
            'description' => 'nullable',
            'string',
>>>>>>> 59200bb (Initial commit with expense manager code)
            'transfer_date' => 'required|date'

        ]);
    }
<<<<<<< HEAD

=======
>>>>>>> 59200bb (Initial commit with expense manager code)
}
