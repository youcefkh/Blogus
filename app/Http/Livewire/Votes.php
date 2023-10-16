<?php

namespace App\Http\Livewire;

use App\Models\Post;
use App\Models\User;
use Livewire\Component;
use App\Models\PostVote;
use PhpParser\Node\Expr\Cast\Bool_;

class Votes extends Component
{
    public Post $post;

    public function mount(Post $post) {
        $this->post = $post;
    }
    public function render()
    {
        $upvotes = PostVote::where('post_id', $this->post->id)->where('upvote', 1)->count();
        $downvotes = PostVote::where('post_id', $this->post->id)->where('upvote', 0)->count();

        $hasVoted = PostVote::where('post_id', $this->post->id)->where('user_id', request()->user()?->id)->first();
        $vote = $hasVoted ? $hasVoted->upvote : '';

        return view('livewire.votes', compact('upvotes', 'downvotes', 'vote'));
    }

    public function vote(bool $upvote) {
        /** @var User $user */
        $user = request()->user();

        if(!$user) {
            return $this->redirect(route('login'));
        }
        if(!$user->hasVerifiedEmail()) {
            return $this->redirect(route('verification.notice'));
        }

        $postVote = PostVote::where('post_id', $this->post->id)->where('user_id', $user->id)->first();

        if(!$postVote) {
            $this->post->votes()->create([
                'upvote' => $upvote,
                'user_id' => $user->id,
            ]);

            return;
        }

        /** user removes his vote */
        if($upvote && $postVote->upvote || !$upvote && !$postVote->upvote) {
            $postVote->delete();
        }else {
            $postVote->upvote = $upvote;
            $postVote->save();
        }

        
    }
}
