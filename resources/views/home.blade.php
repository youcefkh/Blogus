<x-app-layout meta-description="Blogus is a home for human stories and ideas">
    <section class="container max-w-5xl mx-auto py-6 px-3">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- 3 popular posts -->
            <div class="col-span-2">
                <h2 class="text-lg sm:text-2xl font-bold text-blue-800 uppercase pb-1 border-b-2  border-blue-800 mb-3">
                    Popular posts
                </h2>
                @foreach ($popularPosts as $post)
                    <x-post-item :post="$post" />
                @endforeach
            </div>
            <!-- latest posts -->
            <div class="col-span-1">
                <h2 class="text-lg sm:text-2xl font-bold text-blue-800 uppercase pb-1 border-b-2  border-blue-800 mb-3">
                    Latest posts
                </h2>
                @foreach ($latestPosts as $post)
                    <x-small-post-item :post="$post" />
                @endforeach
            </div>
        </div>

        <!-- recommended posts -->
        <div class="mb-8">
            <h2 class="text-lg sm:text-2xl font-bold text-blue-800 uppercase pb-1 border-b-2  border-blue-800 mb-3">
                Recommended posts
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($recommendedPosts as $post)
                    <x-small-post-item :post="$post" />
                @endforeach
            </div>
        </div>

        <!-- recent categories -->
        <div class="mb-8">
            <div class="col-span-2">
                <h2 class="text-lg sm:text-2xl font-bold text-blue-800 uppercase pb-1 border-b-2  border-blue-800 mb-3">
                    Recent categories
                </h2>
                @foreach ($categories as $category)
                    <a href="{{ route('category', $category) }}" class="text-2xl font-bold block mb-2 w-fit hover:text-blue-500"><i
                            class="fas fa-th-large"></i> {{ $category->title }}</a>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                        @foreach ($category->activePosts() as $post)
                            <x-small-post-item :post="$post" />
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </section>

</x-app-layout>
