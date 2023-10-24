<x-app-layout :meta-title="request()->get('q')" meta-descripition="Display posts by category">
    <!-- Posts Section -->
    <section class="w-full md:w-2/3 flex flex-col items-center px-3">
        <h1 class="text-4xl font-semibold my-5 mr-auto">
            <i class="fas fa-search"></i> Search results for "{{ request()->get('q') }}"
        </h1>
        <!-- post items -->
        @foreach ($posts as $post)
            <div class="bg-white flex flex-col justify-start p-6 w-full">
                <a href="{{ route('post.show', $post) }}"
                    class="sm:text-3xl text-xl font-bold text-blue-500 hover:text-blue-700 pb-4">{!! str_ireplace(
                        request()->get('q'),
                        "<span class='bg-yellow-400'>" . request()->get('q') . '</span>',
                        $post->title,
                    ) !!}</a>
                <p href="{{ route('post.show', $post) }}" class="text-sm pb-3">
                    By <a href="#" class="font-semibold hover:text-gray-800">{{ $post->user->name }}</a>,
                    Published on
                    {{ \Carbon\Carbon::parse($post->published_at)->format('M jS Y') }} | {{ $post->read_time }}min
                </p>
                <div class="pb-6">
                    {!! Helper::highlightKeyword($post->body, request()->get('q')) !!}
                </div>
                <a href="{{ route('post.show', $post) }}" class="uppercase text-gray-800 hover:text-black d-block">Read all <i class="fas fa-arrow-right"></i></a>
            </div>
            <hr>
        @endforeach

        <!-- Pagination -->
        <!-- views/vendor/pagination/tailwind.blade.php -->
        {{ $posts->appends(request()->query())->links() }}

    </section>

    <!-- Sidebar Section -->
    <x-sidebar />
</x-app-layout>
