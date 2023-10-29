<?php

namespace App\Http\Livewire;

use App\Models\Post;
use App\Models\User;
use Livewire\Component;

class NotificationsList extends Component
{
    public $notifications;
    public $unreadNotifications;
    public function mount()
    {
        $this->notifications = auth()->user()->notifications;
        $this->unreadNotifications = auth()->user()->unreadNotifications;
    }
    public function render()
    {
        foreach ($this->notifications as $notification) {
            $this->setCustomAttributes($notification);
        }
        return view('livewire.notifications-list', ['notifications' => $this->notifications]);
    }

    public function markAsRead()
    {
        if ($this->notifications->isNotEmpty()) {
            $this->notifications?->markAsRead();
            $this->unreadNotifications = auth()->user()->unreadNotifications;
        }
    }

    private function setCustomAttributes($notification)
    {
        switch ($notification->type) {
            case 'App\Notifications\PostVoted':
                $post = Post::find($notification->data['post_id']);
                $user = User::find($notification->data['user_id']);
                $notification->setAttribute('post_title', $post->title);
                $notification->setAttribute('post_slug', $post->slug);
                $notification->setAttribute('user_name', $user->name);
                $notification->setAttribute('user_picture', $user->picture);
                break;
        }
    }
}
