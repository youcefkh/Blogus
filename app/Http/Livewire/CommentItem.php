<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use Livewire\Component;

class CommentItem extends Component
{
    public Comment $comment;
    public bool $editing = false;

    protected $listeners = [
        'commentEdited' => 'editComment',
        'cancelEdit' => 'cancelEdit',
    ];

    public function mount(Comment $comment) {
        $this->comment = $comment;
    }
    public function render()
    {
        return view('livewire.comment-item');
    }

    public function delete() {
        if(!auth()->check() || auth()->user()->id != $this->comment->user_id) {
            abort(403);
        }
        $this->comment->delete();
        $this->emitUp('commentDeleted', $this->comment->id);
    }

    public function startEdit() {
        $this->editing = true;
    }

    public function editComment() {
        $this->editing = false;
    }

    public function cancelEdit() {
        $this->editing = false;
    }

    public function report() {

    }
}
