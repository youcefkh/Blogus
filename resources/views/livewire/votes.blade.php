<div class="flex gap-4">
    <button wire:click="vote(true)" @class(['flex items-center gap-2 hover:text-blue-600 transition-all',  'text-blue-500' => $vote==1])>
        <i class="far fa-thumbs-up"></i> <span>{{ $upvotes }}</span>
    </button>
    <button wire:click="vote(false)" @class(['flex items-center gap-2 hover:text-red-600 transition-all',  'text-red-500' => $vote==0])>
        <i class="far fa-thumbs-down"></i> <span>{{ $downvotes }}</span>
    </button>
</div>
