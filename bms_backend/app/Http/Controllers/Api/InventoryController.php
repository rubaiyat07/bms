<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of inventory items
     */
    public function index(Request $request)
    {
        $query = InventoryItem::with(['branch', 'project']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by branch
        if ($request->has('branch_id') && $request->branch_id !== '') {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter by category
        if ($request->has('category') && $request->category !== '') {
            $query->where('category', $request->category);
        }

        // Search by item name or SKU
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $inventory = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $inventory
        ]);
    }

    /**
     * Store a newly created inventory item
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|exists:branches,id',
            'item_name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:inventory_items',
            'quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'status' => 'required|in:in_stock,low_stock,out_of_stock',
            'reorder_level' => 'required|integer|min:0',
            'project_id' => 'nullable|exists:projects,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $inventoryItem = InventoryItem::create($request->all());

            // Log the creation
            $this->logAudit($request->user(), 'create', 'inventory_items', $inventoryItem->id, 'Inventory item created');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Inventory item created successfully',
                'data' => $inventoryItem->load('branch', 'project')
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create inventory item'
            ], 500);
        }
    }

    /**
     * Display the specified inventory item
     */
    public function show(InventoryItem $item)
    {
        return response()->json([
            'success' => true,
            'data' => $item->load(['branch', 'project', 'transactions'])
        ]);
    }

    /**
     * Update the specified inventory item
     */
    public function update(Request $request, InventoryItem $item)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'sometimes|required|exists:branches,id',
            'item_name' => 'sometimes|required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:inventory_items,sku,' . $item->id,
            'quantity' => 'sometimes|required|integer|min:0',
            'unit_price' => 'sometimes|required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|in:in_stock,low_stock,out_of_stock',
            'reorder_level' => 'sometimes|required|integer|min:0',
            'project_id' => 'nullable|exists:projects,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $oldValues = $item->only(['branch_id', 'item_name', 'sku', 'quantity', 'unit_price', 'category', 'description', 'status', 'reorder_level', 'project_id']);

        DB::beginTransaction();
        try {
            $item->update($request->all());

            // Log the update
            $this->logAudit(
                $request->user(),
                'update',
                'inventory_items',
                $item->id,
                'Inventory item updated',
                $oldValues,
                $item->only(['branch_id', 'item_name', 'sku', 'quantity', 'unit_price', 'category', 'description', 'status', 'reorder_level', 'project_id'])
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Inventory item updated successfully',
                'data' => $item->load('branch', 'project')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update inventory item'
            ], 500);
        }
    }

    /**
     * Remove the specified inventory item
     */
    public function destroy(Request $request, InventoryItem $item)
    {
        // Check if item has transactions
        if ($item->transactions()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete inventory item with associated transactions'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $item->delete();

            // Log the deletion
            $this->logAudit($request->user(), 'delete', 'inventory_items', $item->id, 'Inventory item deleted');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Inventory item deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete inventory item'
            ], 500);
        }
    }

    /**
     * Adjust quantity of inventory item
     */
    public function adjustQuantity(Request $request, InventoryItem $item)
    {
        $validator = Validator::make($request->all(), [
            'adjustment' => 'required|integer',
            'reason' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $oldQuantity = $item->quantity;
        $newQuantity = $oldQuantity + $request->adjustment;

        if ($newQuantity < 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot reduce quantity below zero'
            ], 422);
        }

        // Determine status based on new quantity
        $status = 'in_stock';
        if ($newQuantity <= $item->reorder_level && $newQuantity > 0) {
            $status = 'low_stock';
        } elseif ($newQuantity == 0) {
            $status = 'out_of_stock';
        }

        $item->update([
            'quantity' => $newQuantity,
            'status' => $status
        ]);

        // Log the adjustment
        $this->logAudit(
            $request->user(),
            'adjust_quantity',
            'inventory_items',
            $item->id,
            "Quantity adjusted: {$request->adjustment} ({$request->reason})",
            ['quantity' => $oldQuantity, 'status' => $item->status],
            ['quantity' => $newQuantity, 'status' => $status]
        );

        return response()->json([
            'success' => true,
            'message' => 'Quantity adjusted successfully',
            'data' => $item
        ]);
    }

    /**
     * Get low stock items
     */
    public function lowStock(Request $request)
    {
        $query = InventoryItem::with(['branch', 'project'])
            ->where('status', 'low_stock')
            ->orWhere('status', 'out_of_stock');

        // Filter by branch if specified
        if ($request->has('branch_id') && $request->branch_id !== '') {
            $query->where('branch_id', $request->branch_id);
        }

        $items = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    /**
     * Helper method to log audit
     */
    private function logAudit($user, $action, $table, $recordId, $description, $oldValues = null, $newValues = null)
    {
        AuditLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'table_name' => $table,
            'record_id' => $recordId,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
