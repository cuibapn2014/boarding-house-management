<?php

namespace App\Http\Controllers;

use App\Models\WebhookLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Display webhook logs (for admin/debugging)
     */
    public function index(Request $request)
    {
        // Only admin can view webhook logs
        if (!auth()->user()->is_admin) {
            abort(403, 'Chỉ admin mới có quyền xem webhook logs');
        }
        $query = WebhookLog::query()
            ->with('payment')
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment code
        if ($request->has('payment_code')) {
            $query->where('payment_code', $request->payment_code);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $logs = $query->paginate(50);

        return view('apps.webhook.index', compact('logs'));
    }

    /**
     * Show webhook log details
     */
    public function show(int $id)
    {
        // Only admin can view webhook logs
        if (!auth()->user()->is_admin) {
            abort(403, 'Chỉ admin mới có quyền xem webhook logs');
        }

        $log = WebhookLog::with('payment')->findOrFail($id);

        return view('apps.webhook.show', compact('log'));
    }

    /**
     * Retry failed webhook (manual retry for admin)
     */
    public function retry(int $id)
    {
        // Only admin can retry webhooks
        if (!auth()->user()->is_admin) {
            abort(403, 'Chỉ admin mới có quyền retry webhook');
        }

        $log = WebhookLog::findOrFail($id);

        if ($log->status !== WebhookLog::STATUS_FAILED) {
            return back()->with('error', 'Chỉ có thể retry webhook đã failed');
        }

        if (!$log->payment_code) {
            return back()->with('error', 'Không có payment code để retry');
        }

        // This would trigger a manual retry
        // In production, you might want to queue this
        Log::info('Manual webhook retry requested', [
            'webhook_log_id' => $log->id,
            'payment_code' => $log->payment_code,
        ]);

        return back()->with('success', 'Đã gửi yêu cầu retry webhook');
    }
}
