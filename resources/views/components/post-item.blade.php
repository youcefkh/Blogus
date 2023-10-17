<article class="flex flex-col shadow my-4">
    <!-- Article Image -->
    <a href="{{ route('post.show', $post) }}" class="hover:opacity-75">
        <img class="h-96 w-full object-cover" src="{{ $post->getThumbnail() }}">
    </a>
    <div class="bg-white flex flex-col justify-start p-6">
        <div class="flex gap-4 text-blue-700 text-sm font-bold uppercase pb-4 d-inline-block">
            @foreach ($post->categories as $category)
                <a href="{{ route('category', $category) }}" class="mr-2">
                    {{ $category->title }}
                </a>
            @endforeach
        </div>
        <a href="{{ route('post.show', $post) }}"
            class="text-3xl font-bold hover:text-gray-700 pb-4">{{ $post->title }}</a>
        <p href="{{ route('post.show', $post) }}" class="text-sm pb-3">
            By <a href="#" class="font-semibold hover:text-gray-800">{{ $post->user->name }}</a>, Published on
            {{ \Carbon\Carbon::parse($post->published_at)->format('M jS Y') }} | {{$post->read_time}}min
        </p>
        <div class="pb-6">
            {{ mb_strimwidth(strip_tags($post->body), 0, 200, '...') }}
        </div>
        <a href="{{ route('post.show', $post) }}" class="uppercase text-gray-800 hover:text-black d-block">Continue
            Reading <i class="fas fa-arrow-right"></i></a>
    </div>
</article>
