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
    public bool $replying = false;
    public int $totalComments = 0;

    protected $listeners = [
        'commentAdded' => 'addComment',
        'commentDeleted' => 'deleteComment',
        'replyAdded' => 'addReply',
    ];

    public function mount(Post $post) {
        $this->post = $post;
        $this->comments = Comment::where('post_id', $this->post->id)->whereNull('parent_id')->with(['replies'])->latest()->get();
        $this->commentsCount();
    }
    public function render()
    {
        return view('livewire.comments');
    }

    public function addComment(int $commentId) {
        $comment = Comment::find($commentId);
        $this->comments = $this->comments->prepend($comment);
        $this->totalComments++;
    }

    public function deleteComment(int $commentId) {
        $this->comments = $this->comments->reject(fn($comment) => $comment->id == $commentId);
        $this->totalComments--;
    }

    public function addReply(int $commentId) {
        $this->totalComments++;
    }

    public function commentsCount() {
        foreach($this->comments as $comment) {
            $this->totalComments += $comment->replies->count() + 1;
        }
    }
}
