<?php

namespace App\Http\Livewire;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Livewire\Component;
use Barryvdh\Debugbar\Facades\Debugbar;
use Filament\Notifications\Notification;

class NotificationsList extends Component
{
    public $notifications;
    public $unreadNotifications;

    public function mount()
    {
        $this->notifications = collect();
        $this->unreadNotifications = auth()->user()->unreadNotifications;
    }
    public function render()
    {
        return view('livewire.notifications-list');
    }

    public function getListeners()
    {
        $user_id = auth()->user()->id;
        return [
            "echo-private:App.Models.User.{$user_id},.Illuminate\Notifications\Events\BroadcastNotificationCreated" => 'addNotification'
        ];
    }

    public function getNotifications () {
        $notifications = auth()->user()->notifications;
        foreach ($notifications as $notification) {
            $this->setCustomAttributes($notification);
        }
        $this->notifications = $notifications;
    }

    public function addNotification($data) {
        $this->getNotifications();
        $this->unreadNotifications = auth()->user()->unreadNotifications;
    }

    public function markAsRead()
    {
        if ($this->unreadNotifications->isNotEmpty()) {
            $this->unreadNotifications->markAsRead();
            $this->unreadNotifications = auth()->user()->unreadNotifications;
        }
        $this->getNotifications();
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

            case 'App\Notifications\PostCommented':
                $post = Post::find($notification->data['post_id']);
                $user = User::find($notification->data['user_id']);
                $comment = Comment::find($notification->data['comment_id']);
                $notification->setAttribute('user_name', $user->name);
                $notification->setAttribute('user_picture', $user->picture);
                $notification->setAttribute('post_slug', $post->slug);
                if ($notification->data['type'] == 'reply') {
                    $notification->setAttribute('comment', $comment->comment);
                    $notification->setAttribute('parent_id', $comment->parent_id);
                } else {
                    $notification->setAttribute('post_title', $post->title);
                }
                break;
        }
    }
}
