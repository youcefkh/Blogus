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

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->comments = Comment::orderByRaw($this->getOrederRaw())->orderByDesc('created_at')->where('post_id', $this->post->id)->whereNull('parent_id')->with(['replies'])->get();
        $this->commentsCount();
    }
    public function render()
    {
        return view('livewire.comments');
    }

    public function addComment(int $commentId)
    {
        $comment = Comment::find($commentId);
        $this->comments = $this->comments->prepend($comment);
        $this->totalComments++;
    }

    public function deleteComment(int $commentId)
    {
        $this->comments = $this->comments->reject(fn ($comment) => $comment->id == $commentId);
        $this->totalComments--;
    }

    public function addReply(int $commentId)
    {
        $this->totalComments++;
    }

    public function commentsCount()
    {
        foreach ($this->comments as $comment) {
            $this->totalComments += $comment->replies->count() + 1;
        }
    }

    //if redirected from comment notification put the comment at the top
    private function getOrederRaw()
    {
        if (request()->parentCommentId) {
            //if it's a reply order by the parent
            return "IF(id = " . request()->parentCommentId . ", 0,1)";
        } elseif (request()->commentId) {
            return "IF(id = " . request()->commentId . ", 0,1)";
        }
        return "created_at DESC";
    }
}
