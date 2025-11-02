<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'action' => 'nullable|string|max:255',
            'table_name' => 'nullable|string|max:255',
            'record_id' => 'nullable|integer',
            'ip_address' => 'nullable|string|max:45',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = AuditLog::with('user:id,name,email');

        // Filter by user
        if ($request->has('user_id') && $request->user_id !== '') {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->has('action') && $request->action !== '') {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        // Filter by table name
        if ($request->has('table_name') && $request->table_name !== '') {
            $query->where('table_name', $request->table_name);
        }

        // Filter by record ID
        if ($request->has('record_id') && $request->record_id !== '') {
            $query->where('record_id', $request->record_id);
        }

        // Filter by IP address
        if ($request->has('ip_address') && $request->ip_address !== '') {
            $query->where('ip_address', $request->ip_address);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Order by latest first
        $query->orderBy('created_at', 'desc');

        $perPage = $request->get('per_page', 15);
        $auditLogs = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $auditLogs
        ]);
    }

    /**
     * Display the specified audit log
     */
    public function show(AuditLog $log)
    {
        return response()->json([
            'success' => true,
            'data' => $log->load('user:id,name,email')
        ]);
    }
}
