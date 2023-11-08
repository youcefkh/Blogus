@vite('build.css')
<section class="max-w-2xl px-6 py-8 mx-auto bg-white dark:bg-gray-900">
    <header>
        <a href="#" class="flex items-center font-semibold gap-1">
            <img class="w-auto h-7 sm:h-8"
                src="https://cdn.icon-icons.com/icons2/1945/PNG/512/iconfinder-blog-4661578_122455.png" alt="">
            Blogus
        </a>
    </header>

    <main class="mt-8">
        <div class="flex gap-4 items-center px-4 py-8 shadow rounded mx-auto w-full relative">
            <span
                class="text-xs uppercase m-1 py-1 mr-3 text-gray-500 absolute -bottom-2 right-0">{{ now()->diffForHumans() }}</span>

            <img class="h-12 w-12 rounded-full object-cover flex-shrink-0" style="align-self: flex-start;" alt="{{ $user->name }}'s avatar"
                src="{{ URL::asset('img/' . $user->picture) }}" />

            <div class="flex-grow">
                <h4 class="text-lg font-semibold text-gray-900 mb-1">{{ $user->name }}
                </h4>
                @if (!$comment->parent_id)
                    <p class="text-sm text-gray-600">Has left a comment on your post <span
                            class="font-semibold">{{ $post->title }} </span>
                    </p>
                    <a href="{{ route('post.show', ['post' => $post->slug, 'commentId' => $comment->id]) . '#comment-' . $comment->id }}"
                        class="font-semibold">
                        <button
                            class="w-full bg-blue-800 text-white font-bold text-sm uppercase rounded hover:bg-blue-700 flex items-center justify-center px-2 py-3 mt-4">
                            See comment
                        </button>
                    </a>
                @else
                    <p class="text-sm text-gray-600">Has replied to your comment <span class="font-semibold">
                        "{{ $comment->comment }}"</span class=>
                    </p>
                    <a href="{{ route('post.show', ['post' => $post->slug, 'commentId' => $comment->id, 'parentCommentId' => $comment->parent_id]) . '#comment-' . $comment->id }}"
                        class="font-semibold">
                        <button
                            class="w-full bg-blue-800 text-white font-bold text-sm uppercase rounded hover:bg-blue-700 flex items-center justify-center px-2 py-3 mt-4">
                            See Reply
                        </button>
                    </a>
                @endif
            </div>
        </div>
    </main>


    <footer class="mt-8 text-sm">
        <p class="text-gray-500 dark:text-gray-400">
            This email was sent to <a href="#" class="text-blue-600 hover:underline dark:text-blue-400"
                target="_blank">blogus@gmail.com</a>.
            If you'd rather not receive this kind of email, you can <a href="#"
                class="text-blue-600 hover:underline dark:text-blue-400">unsubscribe</a> or <a href="#"
                class="text-blue-600 hover:underline dark:text-blue-400">manage your email preferences</a>.
        </p>

        <p class="mt-3 text-gray-500 dark:text-gray-400">© 2023 Blogus. All Rights
            Reserved.</p>
    </footer>
</section>
