<?php

namespace App\Http\Livewire;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class Comments extends Component
{
    use WithPagination;
    
    public Post $post;
    public $comments;

    protected $listeners = [
        'commentAdded' => 'addComment',
        'commentDeleted' => 'deleteComment',
    ];

    public function mount(Post $post) {
        $this->post = $post;
        $this->comments = Comment::where('post_id', $this->post->id)->latest()->get();
    }
    public function render()
    {
        return view('livewire.comments');
    }

    public function addComment(int $commentId) {
        $comment = Comment::find($commentId);
        $this->comments = $this->comments->prepend($comment);
    }

    public function deleteComment(int $commentId) {
        $this->comments = $this->comments->reject(fn($comment) => $comment->id == $commentId);
    }
}
