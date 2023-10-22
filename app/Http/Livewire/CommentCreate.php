<?php

namespace App\Http\Livewire;

use App\Models\Post;
use App\Models\Comment;
use Livewire\Component;

class CommentCreate extends Component
{
    public string $comment = "";
    public ?Post $post; //only for create mode
    public ?Comment $parent; //only for edit mode

    public function mount(Post $post = null, Comment $parent = null) {
        $this->post = $post;
        $this->parent = $parent;
    }

    public function render()
    {
        return view('livewire.comment-create');
    }

    public function addComment() {
        $postId = $this->parent->id ? $this->parent->post_id : $this->post->id; //if parent->id exists means we are in editing mode
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

        $this->comment = '';
    }
}
