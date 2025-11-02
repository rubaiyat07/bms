<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['branch', 'category', 'creator', 'approver']);

        // Filter by branch if specified
        if ($request->has('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $transactions = $query->orderBy('date', 'desc')
                              ->orderBy('created_at', 'desc')
                              ->paginate(15);

        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|exists:branches,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'category_id' => 'required|exists:expense_categories,id',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'reference_no' => 'nullable|string|unique:transactions,reference_no',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $transaction = Transaction::create([
            'branch_id' => $request->branch_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'date' => $request->date,
            'reference_no' => $request->reference_no,
            'created_by' => Auth::id(),
            'status' => 'pending',
        ]);

        return response()->json($transaction->load(['branch', 'category', 'creator']), 201);
    }

    public function show(Transaction $transaction)
    {
        return response()->json($transaction->load(['branch', 'category', 'creator', 'approver']));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'sometimes|exists:branches,id',
            'type' => 'sometimes|in:income,expense',
            'amount' => 'sometimes|numeric|min:0',
            'category_id' => 'sometimes|exists:expense_categories,id',
            'description' => 'nullable|string',
            'date' => 'sometimes|date',
            'reference_no' => 'nullable|string|unique:transactions,reference_no,' . $transaction->id,
            'status' => 'sometimes|in:pending,approved,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $transaction->update($request->only([
            'branch_id', 'type', 'amount', 'category_id', 'description', 'date', 'reference_no', 'status'
        ]));

        return response()->json($transaction->load(['branch', 'category', 'creator', 'approver']));
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return response()->json(['message' => 'Transaction deleted successfully']);
    }

    public function approve(Request $request, Transaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return response()->json(['message' => 'Transaction is not pending approval'], 400);
        }

        $transaction->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        return response()->json($transaction->load(['branch', 'category', 'creator', 'approver']));
    }

    public function reject(Request $request, Transaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return response()->json(['message' => 'Transaction is not pending approval'], 400);
        }

        $transaction->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
        ]);

        return response()->json($transaction->load(['branch', 'category', 'creator', 'approver']));
    }

    public function categories(Request $request)
    {
        $query = ExpenseCategory::query();

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        $categories = $query->orderBy('name')->get();

        return response()->json($categories);
    }

    public function incomeExpenseReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $branchId = $request->get('branch_id');

        $query = Transaction::whereBetween('date', [$startDate, $endDate])
                           ->where('status', 'approved');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $report = $query->select(
            'type',
            DB::raw('SUM(amount) as total_amount'),
            DB::raw('COUNT(*) as transaction_count')
        )
        ->groupBy('type')
        ->get();

        $income = $report->where('type', 'income')->first();
        $expense = $report->where('type', 'expense')->first();

        return response()->json([
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'income' => [
                'total' => $income ? $income->total_amount : 0,
                'count' => $income ? $income->transaction_count : 0,
            ],
            'expense' => [
                'total' => $expense ? $expense->total_amount : 0,
                'count' => $expense ? $expense->transaction_count : 0,
            ],
            'net' => ($income ? $income->total_amount : 0) - ($expense ? $expense->total_amount : 0),
        ]);
    }
}
