<?php

namespace App\Http\Controllers\User;

use App\DataTables\User\SupportTicketDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\SupportTickets\ReplySupportTicketRequest;
use App\Http\Requests\User\SupportTickets\StoreSupportTicketRequest;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function index(SupportTicketDataTable $dataTable)
    {
        return $dataTable->render('user.support-tickets.index');
    }

    public function create()
    {
        return view('user.support-tickets.create');
    }

    public function store(StoreSupportTicketRequest $request)
    {
        $supportTicket = SupportTicket::create($request->validated() + [
                'status' => SupportTicket::STATUS_OPEN,
                'user_id' => auth()->id(),
            ]);

        return success(trans("Support ticket has been created successfully. We'll get back to you as soon as possible."), route('user.support-tickets.show', $supportTicket));
    }

    public function show(SupportTicket $supportTicket)
    {
        abort_if($supportTicket->user_id !== auth()->id(), 404);

        $replies = $supportTicket
            ->replies()
            ->latest()
            ->paginate(10);

        return view('user.support-tickets.show', [
            'supportTicket' => $supportTicket,
            'replies' => $replies,
        ]);
    }

    public function reply(SupportTicket $supportTicket, ReplySupportTicketRequest $request)
    {
        abort_if($supportTicket->user_id !== auth()->id(), 404);

        $supportTicket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $request->validated('message'),
            'is_customer' => true,
        ]);

        return success(trans('Reply has been sent successfully.'), route('user.support-tickets.show', $supportTicket));
    }

    public function destroy(SupportTicket $supportTicket)
    {
        abort_if($supportTicket->user_id !== auth()->id(), 404);

        $supportTicket->delete();

        return success(trans('Support ticket has been deleted successfully.'));
    }

    public function changeStatus(SupportTicket $supportTicket)
    {
        abort_if($supportTicket->user_id !== auth()->id(), 404);

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
}
