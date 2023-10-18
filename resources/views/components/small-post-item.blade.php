<div class="col-span-1 grid grid-cols-4 gap-2 mb-6">
    <a href="{{ route('post.show', $post) }}" class="overflow-hidden mt-1">
        <img class="rounded w-full aspect-[4/3] object-cover" src="{{ $post->getThumbnail() }}">
    </a>
    <div class="col-span-3">
        <a href="{{ route('post.show', $post) }}">
            <h3 class="font-semibold leading-5">
                {{ mb_strimwidth($post->title, 0, 50, '...') }}
            </h3>
        </a>
        <a href="{{ route('post.show', $post) }}">
            <p class="text-sm leading-4">
                {{ mb_strimwidth(strip_tags($post->body), 0, 100, '...') }}
            </p>
        </a>
    </div>
</div>