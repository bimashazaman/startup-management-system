<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with(['user', 'project', 'approver']);

        // Check if user can approve expenses
        if (!Gate::allows('approve-expenses')) {
            $query->where('user_id', auth()->id());
        }

        // Apply filters
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('date', [
                $request->start_date,
                $request->end_date,
            ]);
        }

        // Get all results for summary calculations
        $allExpenses = $query->get();

        // Calculate summary
        $summary = [
            'total' => $allExpenses->sum('amount'),
            'pending' => $allExpenses->where('status', 'pending')->sum('amount'),
            'approved' => $allExpenses->where('status', 'approved')->sum('amount'),
            'reimbursed' => $allExpenses->where('status', 'reimbursed')->sum('amount'),
        ];

        // Get paginated results for display
        $expenses = $query->latest()->paginate(15);
        $projects = Project::all();
        $categories = Expense::distinct('category')->pluck('category');

        return view('expenses.index', compact('expenses', 'projects', 'categories', 'summary'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('expenses.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'date' => 'required|date',
            'category' => 'required|string|max:255',
            'is_billable' => 'boolean',
            'receipt' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf',
        ]);

        $expense = Expense::create([
            ...$validated,
            'user_id' => auth()->id(),
            'status' => 'pending',
        ]);

        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('expense-receipts');
            $expense->update(['receipt_path' => $path]);
        }

        return redirect()
            ->route('expenses.show', $expense)
            ->with('success', 'Expense created successfully.');
    }

    public function show(Expense $expense)
    {
        $this->authorize('view', $expense);

        $expense->load(['user', 'project', 'approver']);
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);

        $projects = Project::all();
        return view('expenses.edit', compact('expense', 'projects'));
    }

    public function update(Request $request, Expense $expense)
    {
        $this->authorize('update', $expense);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'date' => 'required|date',
            'category' => 'required|string|max:255',
            'is_billable' => 'boolean',
            'receipt' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf',
        ]);

        if ($request->hasFile('receipt')) {
            if ($expense->receipt_path) {
                Storage::delete($expense->receipt_path);
            }
            $path = $request->file('receipt')->store('expense-receipts');
            $validated['receipt_path'] = $path;
        }

        $expense->update($validated);

        return redirect()
            ->route('expenses.show', $expense)
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);

        if ($expense->receipt_path) {
            Storage::delete($expense->receipt_path);
        }

        $expense->delete();

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }

    public function approve(Request $request, Expense $expense)
    {
        $this->authorize('approve-expenses');

        $expense->approve(auth()->user());

        return back()->with('success', 'Expense approved successfully.');
    }

    public function reject(Request $request, Expense $expense)
    {
        $this->authorize('approve-expenses');

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        $expense->reject($validated['rejection_reason']);

        return back()->with('success', 'Expense rejected successfully.');
    }

    public function reimburse(Expense $expense)
    {
        $this->authorize('approve-expenses');

        $expense->markAsReimbursed();

        return back()->with('success', 'Expense marked as reimbursed successfully.');
    }
}
