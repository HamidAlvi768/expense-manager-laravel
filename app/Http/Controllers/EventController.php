<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Expense;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Show the calendar view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('calendar'); // Ensure you have a calendar.blade.php view
    }

    /**
     * Load events within a specified date range.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadEvents(Request $request): JsonResponse
    {
        $start = $request->start; // Start date range
        $end = $request->end;     // End date range
        $userId = auth()->id();   // Fetch the authenticated user's ID
    
        // Fetch expenses within the date range
        $expenses = Expense::where('user_id', $userId)
            ->whereBetween('expense_date', [$start, $end])
            ->with('expenseCategory') // Eager load expense category relationship
            ->get()
            ->map(function ($expense) {
                return [
                    'id' => $expense->id,
                    'title' => 'Expense: ' . $expense->expenseCategory->title,
                    'amount' => $expense->amount,
                    'description' => $expense->description,
                    'category_id' => $expense->expense_category_id,
                    'type' => 'expense',
                    'date' => $expense->expense_date,
                ];
            });
    
        // Fetch incomes within the date range
        $incomes = Income::where('user_id', $userId)
            ->whereBetween('income_date', [$start, $end])
            ->with('incomeCategory') // Eager load income category relationship
            ->get()
            ->map(function ($income) {
                return [
                    'id' => $income->id,
                    'title' => 'Income: ' . $income->incomeCategory->title,
                    'amount' => $income->amount,
                    'description' => $income->description,
                    'category_id' => $income->income_category_id,
                    'type' => 'income',
                    'date' => $income->income_date,
                ];
            });
    
        // Combine expenses and incomes
        $financialData = $expenses->concat($incomes);
    
        // Group by date
        $groupedData = $financialData->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item['date'])->toDateString(); // Group by date
        })->map(function ($itemsByDate) {
            // Group by category and type within each day
            return $itemsByDate->groupBy(function ($item) {
                return $item['category_id'] . '-' . $item['type']; // Group by category_id and type
            })->map(function ($itemsByCategoryAndType) {
                $category = $itemsByCategoryAndType->first();
                return [
                    'category_id' => $category['category_id'],
                    'category_title' => $category['type'] === 'expense' 
                        ? $itemsByCategoryAndType->first()['title'] // Use the title from the first item
                        : $itemsByCategoryAndType->first()['title'], // Use the title from the first item
                    'type' => $category['type'],
                    'total_amount' => $itemsByCategoryAndType->sum('amount'),
                    'events' => $itemsByCategoryAndType->map(function ($item) {
                        return [
                            'id' => $item['id'],
                            'title' => $item['title'],
                            'amount' => $item['amount'],
                            'description' => $item['description'],
                        ];
                    }),
                ];
            })->values(); // Reset keys
        });
    
        return response()->json($groupedData);
    }
    public function fetchEventDetails(Request $request): JsonResponse{
        $categoryId = $request->category_id;
        $date = $request->date;
        $type = $request->type;
        $userId = auth()->id();

        // Initialize an empty collection for transactions
        $transactions = collect();

        // Check the type and fetch transactions accordingly
        if ($type === 'expense') {
            $transactions = Expense::where('user_id', $userId)
                ->where('expense_category_id', $categoryId)
                ->whereDate('expense_date', $date)
                ->with('expenseCategory') // Eager load the expense category
                ->get([ 'amount', 'description', 'expense_category_id'])
                ->map(function ($expense) {
                    return [
                        'title' => $expense->expenseCategory->title ?? 'Unknown', // Get category title
                        'amount' => $expense->amount,
                        'description' => $expense->description,
                    ];
                });
        } elseif ($type === 'income') {
            $transactions = Income::where('user_id', $userId)
                ->where('income_category_id', $categoryId)
                ->whereDate('income_date', $date)
                ->with('incomeCategory') // Eager load the income category
                ->get(['amount', 'description', 'income_category_id'])
                ->map(function ($income) {
                    return [
                        'title' => $income->incomeCategory->title ?? 'Unknown', // Get category title
                        'amount' => $income->amount,
                        'description' => $income->description,
                    ];
                });
        }

        return response()->json($transactions);
    }


    public function fetchEvents(Request $request)
    {
        $start = $request->start;
        $end = $request->end;
        $userId = auth()->user()->id; // Get the authenticated user's ID

        // Fetch events for the user within the specified date range
        $events = Event::where('user_id', $userId) // Filter by user_id
            ->where(function($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end]);
            })
            ->get(['id', 'title', 'description', 'start_date', 'end_date', 'start_time', 'end_time']); // Select required fields

        return response()->json($events);
    }

    
    


    /**
     * Store a newly created event.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
        ]);

        // Determine the event type
        $eventType = 'manual';
        $userId = Auth::id();

        // Add the event type to validated data
        $validatedData['eventtype'] = $eventType;
        $validatedData['user_id'] = $userId;
        $validatedData['created_by'] = $userId;

        Event::create($validatedData);

        return 1;

        // return redirect()->route('events.index')->with('success', 'Event Created Successfully');
    }

    /**
     * Show the details of a specific event.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        $event = Event::where('id', $id)
            ->where('user_id', auth()->user()->id) // Ensure the event belongs to the authenticated user
            ->first();
    
        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }
    
        return response()->json($event);
    }

    /**
     * Update a specific event.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
        ]);

        $event = Event::findOrFail($id);
        $event->update($validatedData);

        return response()->json($event, 200);
    }

    /**
     * Remove a specific event.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json(['success' => 'Event deleted successfully'], 200);
    }
}
