<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use Livewire\Component;

class CommentEdit extends Component
{
    public Comment $commentModel;
    public string $commentValue;
    
    public function mount(Comment $commentModel) {
        $this->commentModel = $commentModel;
        $this->commentValue = $commentModel->comment;
    }

    public function render()
    {
        return view('livewire.comment-edit');
    }

    public function editComment() {
        if(!auth()->check() || auth()->user()->id != $this->commentModel->user_id) {
            abort(403);
        }
        $this->commentModel->comment = $this->commentValue;
        $this->commentModel->save();

        $this->emitUp('commentEdited', $this->commentValue);
    }
}
