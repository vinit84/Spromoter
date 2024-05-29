<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\SupportTicketDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SupportTickets\ReplySupportTicketRequest;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function index(SupportTicketDataTable $dataTable)
    {
        return $dataTable->render('admin.support-tickets.index');
    }

    public function show(SupportTicket $supportTicket)
    {
        $replies = $supportTicket
            ->replies()
            ->latest()
            ->paginate(10);

        return view('admin.support-tickets.show', [
            'supportTicket' => $supportTicket,
            'replies' => $replies,
        ]);
    }

    public function reply(SupportTicket $supportTicket, ReplySupportTicketRequest $request)
    {
        $reply = $supportTicket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $request->validated('message'),
        ]);

        // Get replies after the last reply
        $replies = $supportTicket
            ->replies()
            ->where('id', '>', $request->validated('last_reply'))
            ->get()
            ->map(function ($reply) {
                return [
                    'id' => $reply->id,
                    'message' => $reply->message,
                    'customer' => $reply->user->name,
                    'avatar' => $reply->user->avatar,
                    'is_customer' => $reply->is_customer,
                    'created_at' => $reply->created_at->diffForHumans(),
                ];
            });

        return success(trans('Reply has been sent successfully.'), data: [
            'reply' => $reply,
            'replies' => $replies,
        ]);
    }

    public function destroy(SupportTicket $supportTicket)
    {
        $supportTicket->delete();

        return success(trans('Support ticket has been deleted successfully.'));
    }

    public function changeStatus(SupportTicket $supportTicket)
    {
        $supportTicket->update([
            'status' => $supportTicket->status === SupportTicket::STATUS_OPEN
                ? SupportTicket::STATUS_CLOSED
                : SupportTicket::STATUS_OPEN,
        ]);

        return success(
            $supportTicket->status === SupportTicket::STATUS_OPEN
                ? trans('Support ticket has been marked as open.')
                : trans('Support ticket has been marked as closed.')
        );
    }

    public function markSelectedAsOpen(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:support_tickets,id',
        ], [
            'ids.required' => trans("Please select at least one support ticket.")
        ]);

        SupportTicket::whereIn('id', $request->ids)->update([
            'status' => SupportTicket::STATUS_OPEN,
        ]);

        return success(trans("Selected support tickets have been marked as open."));
    }

    public function markSelectedAsClosed(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:support_tickets,id',
        ], [
            'ids.required' => trans('Please select at least one support ticket.')
        ]);

        SupportTicket::whereIn('id', $request->ids)->update([
            'status' => SupportTicket::STATUS_CLOSED,
        ]);

        return success(trans("Selected support tickets have been marked as open."));
    }
}
