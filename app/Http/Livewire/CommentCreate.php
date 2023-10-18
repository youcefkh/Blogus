<?php

namespace App\Http\Livewire;

use App\Models\Post;
use App\Models\Comment;
use Livewire\Component;

class CommentCreate extends Component
{
    public string $comment = "";
    public Post $post;

    public function mount(Post $post) {
        $this->post = $post;
    }

    public function render()
    {
        return view('livewire.comment-create');
    }

    public function addComment() {
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
            'post_id' => $this->post->id,
            'user_id' => $user->id
        ]);

        $this->emitUp('commentAdded', $newComment->id);

        $this->comment = '';
    }
}
