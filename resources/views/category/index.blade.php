<x-app-layout>
    <!-- Posts Section -->
    <section class="w-full md:w-2/3 flex flex-col items-center px-3">
        <h1 class="text-4xl font-semibold my-5 mr-auto">
            <i class="fas fa-th-large"></i> {{ $category->title }}</h1>
        <!-- post items -->
        @foreach ($posts as $post)
            <x-post-item :post="$post" />
        @endforeach

        <!-- Pagination -->
        <!-- views/vendor/pagination/tailwind.blade.php -->
        {{ $posts->links() }}

    </section>

    <!-- Sidebar Section -->
    <x-sidebar />
</x-app-layout>
