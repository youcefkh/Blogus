<section class="w-full bg-white dark:bg-gray-900 py-8 lg:py-16 mt-6 antialiased shadow">
    <div class="max-w-2xl px-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg lg:text-2xl font-bold text-gray-900 dark:text-white">Comments (20)</h2>
        </div>
    </div>
    <livewire:comment-create :post="$post" />
    @foreach ($comments as $comment)
        <livewire:comment-item :comment="$comment" wire:key="comment-{{$comment->id}}"/>
    @endforeach
    {{-- <div>
        {{ $comments->links() }}
    </div> --}}
</section>
