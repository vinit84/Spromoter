<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(10);

        return view('user.notifications.index', compact('notifications'));
    }

    public function visit($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        $notification->markAsRead();

        if (isset($notification->data['url'])) {
            return redirect($notification->data['url']);
        }

        return back();
    }

    public function markAllAsRead()
    {
        $notifications = auth()->user()->unreadNotifications();

        $notifications->update([
            'read_at' => now()
        ]);
        flash(trans('All Notifications marked as read successfully.'));
        return back();
    }

    public function mark($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        $notification->update([
            'read_at' => $notification->read_at ? null : now()
        ]);

        return success(trans('Notification marked as :status successfully.', ['status' => $notification->read_at ? 'read' : 'unread']));
    }

    public function delete($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        $notification->delete();

        return success(trans('Notification Deleted Successfully.'));
    }
}
