<?php

namespace App\Http\Livewire;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Livewire\Component;
use App\Notifications\PostCommented;

class CommentCreate extends Component
{
    public string $comment = "";
    public ?Post $post; //only for comment mode
    public ?Comment $parent; //only for reply mode

    public function mount(Post $post = null, Comment $parent = null) {
        $this->post = $post;
        $this->parent = $parent;
    }

    public function render()
    {
        return view('livewire.comment-create');
    }

    public function addComment() {
        $postId = $this->parent->id ? $this->parent->post_id : $this->post->id; //if parent->id exists means we are in replying mode
        $parentId = $this->parent->parent_id ? $this->parent->parent_id : $this->parent->id; //always set the root comment as parent_id

        /** @var \App\Models\User */
        $user = auth()->user();
        if(!$user) {
            return $this->redirect(route('login'));
        }
        if(!$user->hasVerifiedEmail()) {
            return $this->redirect(route('verification.notice'));
        }
        $newComment = Comment::create([
            'comment' => $this->comment,
            'post_id' => $postId,
            'user_id' => $user->id,
            'parent_id' => $parentId
        ]);

        $event = $parentId ? 'replyAdded' : 'commentAdded';
        $this->emitUp($event, $newComment->id);

        $this->sendNotification($newComment);

        $this->comment = '';
    }

    private function sendNotification (Comment $comment) {
        $userId = $this->parent->id ? $this->parent->user_id : $this->post->user_id;
        /** @var User $user */
        $user = User::find($userId);
        $post = $this->parent->id ? $this->parent->post : $this->post;
        $user->notify(new PostCommented($post, $comment));

    }
}
