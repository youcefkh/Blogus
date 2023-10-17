<x-app-layout meta-description="Blogus is a home for human stories and ideas">

    <section class="container max-w-5xl mx-auto py-6 px-3">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- 3 popular posts -->
            <div class="col-span-2">
                <h2 class="text-lg sm:text-xl font-bold text-blue-500 uppercase pb-1 border-b-2  border-blue-500 mb-3">
                    Popular posts
                </h2>
                @foreach ($popularPosts as $post)
                    <x-post-item :post="$post" />
                @endforeach
            </div>
            <!-- latest posts -->
            <div class="col-span-1">
                <h2 class="text-lg sm:text-xl font-bold text-blue-500 uppercase pb-1 border-b-2  border-blue-500 mb-3">
                    Latest posts
                </h2>
                @foreach ($latestPosts as $post)
                    <div class="grid grid-cols-4 gap-2 mb-6">
                        <a href="{{ route('post.show', $post) }}" class="overflow-hidden">
                            <img class="rounded h-20 w-full object-cover" src="{{ $post->getThumbnail() }}">
                        </a>
                        <div class="col-span-3">
                            <a href="{{ route('post.show', $post) }}">
                                <h3 class="font-semibold leading-5">{{ mb_strimwidth($post->title, 0, 50, '...') }}</h3>
                            </a>
                            <a href="{{ route('post.show', $post) }}">
                                <p class="text-sm leading-4">{{ mb_strimwidth(strip_tags($post->body), 0, 100, '...') }}
                                </p>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- recommended posts -->
        <div class="mb-8">
            <h2 class="text-lg sm:text-xl font-bold text-blue-500 uppercase pb-1 border-b-2  border-blue-500 mb-3">
                Recommended posts
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($recommendedPosts as $post)
                    <div class="col-pan-1 grid grid-cols-4 gap-2 mb-6">
                        <a href="{{ route('post.show', $post) }}" class="overflow-hidden">
                            <img class="rounded h-20 w-full object-cover" src="{{ $post->getThumbnail() }}">
                        </a>
                        <div class="col-span-3">
                            <a href="{{ route('post.show', $post) }}">
                                <h3 class="font-semibold leading-5">{{ mb_strimwidth($post->title, 0, 50, '...') }}</h3>
                            </a>
                            <a href="{{ route('post.show', $post) }}">
                                <p class="text-sm leading-4">
                                    {{ mb_strimwidth(strip_tags($post->body), 0, 100, '...') }}
                                </p>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- recent categories -->
        <div class="mb-8">
            <div class="col-span-2">
                <h2 class="text-lg sm:text-xl font-bold text-blue-500 uppercase pb-1 border-b-2  border-blue-500 mb-3">
                    Recent categories
                </h2>
            </div>
        </div>
    </section>

</x-app-layout>
