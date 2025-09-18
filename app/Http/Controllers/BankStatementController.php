<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\BankStatement;
use App\Models\DdExpenseCategory;
use App\Models\DdIncomeCategory;
use App\Models\Expense;
use App\Models\Income;
use App\Models\IncomeItem;
use App\Models\ExpenseItem;
use App\Models\Keyword;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Exports\GenericExport;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class BankStatementController extends Controller
{
    public function index(Request $request)
    {
        if ($request->export) {
            return $this->doExport($request);
        }
    
        $bankStatements = $this->filter($request)
            ->with('account') // eager load the account relationship
            ->latest()
            ->paginate(10);
        
        $accounts = Account::where('user_id', auth()->id())->get();

    
        return view('bank-statement.index', compact('bankStatements','accounts'));
    }
    
    private function filter(Request $request)
    {
        $query = BankStatement::query();
    
        // Filter by account
        if ($request->account_id) {
            $query->where('account_id', $request->account_id);
        }
    
        // Filter by Date Range
        if ($request->start_date && $request->end_date) {
            // If both start_date and end_date are provided
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('uploaded_at', [$startDate, $endDate]);
        } elseif ($request->start_date) {
            // If only start_date is provided
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $query->whereDate('uploaded_at', $startDate);
        } elseif ($request->end_date) {
            // If only end_date is provided
            $endDate = Carbon::parse($request->end_date)->startOfDay();
            $query->whereDate('uploaded_at', $endDate);
        }

        $query->where('user_id', auth()->id());

    
        return $query;
    }
    
    public function doExport(Request $request)
    {
        // Retrieve filtered data
        $bankStatements = $this->filter($request)
            ->with('account') // eager load relationships
            ->get();
    
        // Prepare data for export
        $data = $bankStatements->map(function ($bankStatement) {
            return [
                'ID' => $bankStatement->id,
                'Account' => $bankStatement->account->account_title ?? 'N/A',
                'Total Credits' => $bankStatement->total_credits ?? '-',
                'Total Debits' => $bankStatement->total_debits ?? '-',
                'Credit Amount' => $bankStatement->credit_amount ?? '-',
                'Debit Amount' => $bankStatement->debit_amount ?? '-',
                'Uploaded At' => $bankStatement->uploaded_at,
            ];
        })->toArray();
    
        // Define headers for the export
        $headers = ['ID', 'Account', 'Total Credits', 'Total Debits', 'Credit Amount', 'Debit Amount', 'Uploaded At'];
    
        return Excel::download(new GenericExport($data, $headers), 'bank-statements.xlsx');
    }
    

    public function create()
    {
        $accounts = Account::where('user_id', auth()->id())->select('id', 'account_title')->orderBy('id', 'desc')->get();
        return view('bank-statement.create', compact('accounts'));
    }



    public function show(BankStatement $bankStatement)
    {
        return view('bank-statement.show', compact('bankStatement'));
    }

    public function destroy(BankStatement $bankStatement)
    {
        Storage::delete($bankStatement->file_path);
        $bankStatement->delete();

        return redirect()->route('bank-statements.index')->with('success', 'Bank statement deleted successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $fileName = $request->file('file')->getClientOriginalName();
        $storedPath = $request->file('file')->storeAs('uploads/users/' . Auth::id() . '/bank-statements', $fileName, 'public');

        // Create record
        $bankStatement = BankStatement::create([
            'user_id' => Auth::id(),
            'account_id' => $request->account_id,
            'file_path' => $storedPath,
            'uploaded_at' => now(),
            'created_by' => Auth::id(),
        ]);


        // Process CSV
        if ($request->bank_type === 'meezan') {
            $this->processMeezanCsv($bankStatement->file_path, $bankStatement);
        } elseif ($request->bank_type === 'hbl') {
            $this->processHblCsv($bankStatement->file_path, $bankStatement);
        } elseif ($request->bank_type === 'habib_metro') {
            $this->processHabibMetroCsv($bankStatement->file_path, $bankStatement);
        }elseif ($request->bank_type === 'allied') {
            $this->processAlliedBankCsv($bankStatement->file_path, $bankStatement);
        }


        return redirect()->route('bank-statements.index')->with('success', 'Bank statement uploaded and processed successfully.');
    }

    private function processMeezanCsv($filePath, BankStatement $bankStatement)
    {
        $handle = fopen(Storage::path($filePath), 'r');
        if (!$handle) {
            throw new \Exception('Unable to open the CSV file.');
        }
    
        $rows = [];
        $rowCount = 0;
    
        while (($data = fgetcsv($handle)) !== false) {
            $rowCount++;
            // Skip the first 8 rows (headers or irrelevant data)
            if ($rowCount <= 8) {
                continue;
            }
            $rows[] = $data;
        }
    
        fclose($handle);
    
        if (empty($rows)) {
            throw new \Exception('CSV file contains no valid rows.');
        }
    
        $header = array_shift($rows); // Remove the header row
    
        $requiredHeaders = ['Transaction Date', 'Description', 'Debit', 'Credit', 'Available Balance'];
        if ($header !== $requiredHeaders) {
            throw new \Exception('CSV headers do not match the expected format.');
        }
    
        // Remove the last column (Available Balance) from each row
        $rows = array_map(function ($row) {
            return array_slice($row, 0, 4); // Keep only the first 4 columns
        }, $rows);
    
        // Handle multi-line descriptions
        $transactions = [];
        $currentTransaction = null;
    
        foreach ($rows as $row) {
            if (count(array_filter($row)) === 1 && !empty($row[1])) {
                // If the row has only one non-empty column (Description), append it to the current transaction
                if ($currentTransaction) {
                    $currentTransaction[1] .= ' ' . trim($row[1]);
                }
            } else {
                // Otherwise, it's a new transaction row
                if ($currentTransaction) {
                    $transactions[] = $currentTransaction; // Save the previous transaction
                }
                $currentTransaction = $row;
            }
        }
    
        // Add the last transaction
        if ($currentTransaction) {
            $transactions[] = $currentTransaction;
        }
    
        $totalDebits = 0;
        $totalCredits = 0;
        $debitCount = 0;
        $creditCount = 0;
    
        foreach ($transactions as $transaction) {
            if (count($transaction) < 4) {
                continue; // Skip incomplete rows
            }
    
            [$transactionDate, $description, $debit, $credit] = $transaction;
    
            // Parse transaction data
            $date = date('Y-m-d', strtotime($transactionDate));
            $debit = $debit ? floatval($debit) : null;
            $credit = $credit ? floatval($credit) : null;
    
            // Record debit/credit logic
            if ($debit) {
                $totalDebits += $debit;
                $debitCount++;
                $this->processExpense($bankStatement, $date, $debit, $description);
            } elseif ($credit) {
                $totalCredits += $credit;
                $creditCount++;
                $this->processIncome($bankStatement, $date, $credit, $description);
            }
        }
    
        // Update Bank Statement totals
        $bankStatement->update([
            'total_debits' => $debitCount,
            'total_credits' => $creditCount,
            'debit_amount' => $totalDebits,
            'credit_amount' => $totalCredits,
        ]);
    }

    private function processHblCsv($filePath, BankStatement $bankStatement)
    {
        $handle = fopen(Storage::path($filePath), 'r');
        if (!$handle) {
            throw new \Exception('Unable to open the CSV file.');
        }
    
        $rows = [];
        $rowCount = 0;
    
        // Read CSV data
        while (($data = fgetcsv($handle)) !== false) {
            $rowCount++;
            // Skip the first row if it's headers
            if ($rowCount === 1) {
                continue;
            }
    
            // Skip rows where the first three columns are null (irrelevant closing balances)
            if (empty(array_filter(array_slice($data, 0, 3)))) {
                continue;
            }
    
            $rows[] = $data;
        }
    
        fclose($handle);
    
        if (empty($rows)) {
            throw new \Exception('CSV file contains no valid rows.');
        }
    
        // Reverse the rows to process the most recent transaction first (like Meezan CSV)
        $rows = array_reverse($rows);

        // Remove the last column (Available Balance) from each row
        $rows = array_map(function ($row) {
            return array_slice($row, 0, 4); // Keep only the first 4 columns
        }, $rows);
    
        $totalDebits = 0;
        $totalCredits = 0;
        $debitCount = 0;
        $creditCount = 0;
    
        foreach ($rows as $row) {
            if (count($row) < 4) {
                continue; // Skip incomplete rows
            }
    
            [$transactionDate, $description, $debit, $credit] = $row;
    
            // Parse transaction data
            // Convert the date from MM/DD/YYYY to Y-m-d format
            $date = date('Y-m-d', strtotime(str_replace('/', '-', $transactionDate)));
            
            // Handle the debit and credit values (strip commas if necessary)
            $debit = $debit ? floatval(str_replace(',', '', $debit)) : null;
            $credit = $credit ? floatval(str_replace(',', '', $credit)) : null;
    
            // Record debit/credit logic
            if ($debit) {
                $totalDebits += $debit;
                $debitCount++;
                $this->processExpense($bankStatement, $date, $debit, $description);
            } elseif ($credit) {
                $totalCredits += $credit;
                $creditCount++;
                $this->processIncome($bankStatement, $date, $credit, $description);
            }
        }
    
        // Update Bank Statement totals
        $bankStatement->update([
            'total_debits' => $debitCount,
            'total_credits' => $creditCount,
            'debit_amount' => $totalDebits,
            'credit_amount' => $totalCredits,
        ]);
    }

    private function processHabibMetroCsv($filePath, BankStatement $bankStatement){
        $handle = fopen(Storage::path($filePath), 'r');
        if (!$handle) {
            throw new \Exception('Unable to open the CSV file.');
        }

        $rows = [];
        $rowCount = 0;

        // Read CSV data
        while (($data = fgetcsv($handle)) !== false) {
            $rowCount++;
            // Skip the first row (header)
            if ($rowCount === 1) {
                continue;
            }

            // Skip the row with "Opening Balance" and rows that have incomplete data
            if (empty(array_filter(array_slice($data, 0, 3))) || strpos($data[1], 'Opening Balance') !== false) {
                continue;
            }

            // Skip the last row with "Closing Balance"
            if (strpos($data[1], 'Closing Balance') !== false) {
                continue;
            }

            $rows[] = $data;
        }

        fclose($handle);


        if (empty($rows)) {
            throw new \Exception('CSV file contains no valid rows.');
        }

        // Reverse the rows to process the most recent transaction first (like Meezan CSV)

        // Remove the last column (Balance) from each row
        $rows = array_map(function ($row) {
            return array_slice($row, 0, 4); // Keep only the first 4 columns
        }, $rows);

        $totalDebits = 0;
        $totalCredits = 0;
        $debitCount = 0;
        $creditCount = 0;

        foreach ($rows as $row) {
            if (count($row) < 4) {
                continue; // Skip incomplete rows
            }

            [$transactionDate, $description, $debit, $credit] = $row;

            // Parse transaction data
            $date = date('Y-m-d', strtotime($transactionDate));
            $debit = $debit ? floatval(str_replace(',', '', $debit)) : null;
            $credit = $credit ? floatval(str_replace(',', '', $credit)) : null;

            // Record debit/credit logic
            if ($debit) {
                $totalDebits += $debit;
                $debitCount++;
                $this->processExpense($bankStatement, $date, $debit, $description);
            } elseif ($credit) {
                $totalCredits += $credit;
                $creditCount++;
                $this->processIncome($bankStatement, $date, $credit, $description);
            }
        }

        // Update Bank Statement totals
        $bankStatement->update([
            'total_debits' => $debitCount,
            'total_credits' => $creditCount,
            'debit_amount' => $totalDebits,
            'credit_amount' => $totalCredits,
        ]);
    }

    private function processAlliedBankCsv($filePath, BankStatement $bankStatement)
    {
        $handle = fopen(Storage::path($filePath), 'r');
        if (!$handle) {
            throw new \Exception('Unable to open the CSV file.');
        }
    
        $rows = [];
        $rowCount = 0;
    
        // Read CSV data
        while (($data = fgetcsv($handle)) !== false) {
            $rowCount++;
            // Skip the header row
            if ($rowCount === 1) {
                continue;
            }
    
            // Extract only the relevant columns: Date, Particulars, Debit, Credit
            $rows[] = [
                $data[0], // Date
                $data[1], // Particulars (Description)
                $data[7], // Debit
                $data[8], // Credit
            ];
        }
    
        fclose($handle);
    
        if (empty($rows)) {
            throw new \Exception('CSV file contains no valid rows.');
        }
    
        $totalDebits = 0;
        $totalCredits = 0;
        $debitCount = 0;
        $creditCount = 0;
    
        foreach ($rows as $row) {
            if (count($row) < 4) {
                continue; // Skip incomplete rows
            }
    
            [$transactionDate, $description, $debit, $credit] = $row;
    
            // Parse transaction data
            $date = date('Y-m-d', strtotime($transactionDate));
            $debit = $debit ? floatval(str_replace(',', '', $debit)) : null;
            $credit = $credit ? floatval(str_replace(',', '', $credit)) : null;
    
            // Record debit/credit logic
            if ($debit) {
                $totalDebits += $debit;
                $debitCount++;
                $this->processExpense($bankStatement, $date, $debit, $description);
            } elseif ($credit) {
                $totalCredits += $credit;
                $creditCount++;
                $this->processIncome($bankStatement, $date, $credit, $description);
            }
        }
    
        // Update Bank Statement totals
        $bankStatement->update([
            'total_debits' => $debitCount,
            'total_credits' => $creditCount,
            'debit_amount' => $totalDebits,
            'credit_amount' => $totalCredits,
        ]);
    }
    private function processExpense(BankStatement $bankStatement, $date, $amount, $description)
    {
        $userId = Auth::id();
    
        // Get or create the category based on the description
        $expenseCategoryId = $this->getOrCreateCategory($description, 'expense');
    
        // Check for existing expense on the same date
        $expense = Expense::create([
            'account_id' => $bankStatement->account_id,
            'user_id' => $userId,
            'expense_category_id' => $expenseCategoryId, // Dynamically assigned
            'expense_date' => $date,
            'amount' => $amount,
            'description' => $description,
            'created_by' => $userId,
            'creation_mode' => 'statement', // Dynamically assigned
        ]);

        // Create a transaction record
        Transaction::create([
            'account_id' => $bankStatement->account_id,
            'reference_id' => $expense->id,
            'reference_type' => Expense::class,
            'transaction_type' => 'expense',
            'amount' => $amount,
            'description' => $description,
            'created_by' => $userId,
        ]);

        $eventData = [
            'user_id' => $userId,
            'title' => 'Expense: ' . $expense->expenseCategory->title ?? '-',
            'description' => $description ?? '-',
            'amount' => $amount,
            'type' => 'expense',
            'category_id' => $expense->expenseCategory->id,
            'start_date' =>  $date,
            'end_date' =>  $date,
            'start_time' => '00:00:00', // Fixed start time
            'end_time' => '23:59:59',   // Fixed end time
            'eventtype' => 'manual expense',
            'created_by' => $userId,
        ];
        Event::Create($eventData);

    
        $account = $bankStatement->account;
        $account->increment('withdrawal', $amount); // Increase withdrawal
        $account->decrement('balance', $amount);    // Decrease balance
        $account->decrement('total', $amount);
    }

    private function processIncome(BankStatement $bankStatement, $date, $amount, $description)
    {
        $userId = Auth::id();
    
        // Get or create the category based on the description
        $incomeCategoryId = $this->getOrCreateCategory($description, 'income');
    
        // Create income record
        $income = Income::create([
            'account_id' => $bankStatement->account_id,
            'user_id' => $userId,
            'income_category_id' => $incomeCategoryId, // Dynamically assigned
            'income_date' => $date,
            'amount' => $amount,
            'description' => $description,
            'created_by' => $userId,
            'creation_mode' => 'statement', // Dynamically assigned
        ]);

        // Create a transaction record
        Transaction::create([
            'account_id' => $bankStatement->account_id,
            'reference_id' => $income->id,
            'reference_type' => Income::class,
            'transaction_type' => 'income',
            'amount' => $amount,
            'description' => $description,
            'created_by' => $userId,
        ]);

        $eventData = [
            'user_id' => $userId,
            'title' => 'Income: ' . $income->incomeCategory->title ?? '-',
            'description' => $description ?? '-',
            'amount' => $amount,
            'type' => 'income',
            'category_id' => $income->incomeCategory->id,
            'start_date' =>  $date,
            'end_date' =>  $date,
            'start_time' => '00:00:00', // Fixed start time
            'end_time' => '23:59:59',   // Fixed end time
            'eventtype' => 'manual income',
            'created_by' => $userId,
        ];
        Event::Create($eventData);
    
        // Update account fields
        $account = $bankStatement->account;
        $account->increment('deposit', $amount);    // Increase deposit
        $account->increment('balance', $amount);    // Increase balance
        $account->increment('total', $amount);      // Increase total
    }
    private function getOrCreateCategory($description, $type)
    {
        // Fetch all keywords for the given type with their associated categories
        $keywords = Keyword::where('type', $type)
            ->where('status', 1) // Active keywords only
            ->with(['category']) // Eager load categories
            ->get();
    
        // Search for a keyword match in the description
        foreach ($keywords as $keyword) {
            if (stripos($description, $keyword->title) !== false) {
                // Return the category ID of the matched keyword
                return $keyword->category_id;
            }
        }
    
        // Default fallback: "Miscellaneous" category
        return 1;
    } 
}
