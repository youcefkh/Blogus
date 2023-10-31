<div class="relative block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
    x-data="{ show: false }">
    <button class="relative w-full" @click="show=!show">
        <div
            class="hidden absolute left-0 top-0  bg-red-500 min-w-[20px] rounded-full {{ $unreadNotifications->count() == 0 ? 'hidden' : 'md:block' }}">
            <span class="text-sm text-white p-1">{{ $unreadNotifications->count() }}</span>
        </div>
        <div class="flex justify-between">

            <div class="md:px-2">
                <i class="md:text-2xl font-light far fa-bell"></i><span class="md:hidden ml-2">Notifications</span>
            </div>
            <!-- for mobile -->
            <div
                class=" bg-red-500 min-w-[20px] rounded-full md:hidden {{ $unreadNotifications->count() == 0 ? 'hidden' : '' }}">
                <span class="text-sm text-white p-1">{{ $unreadNotifications->count() }}</span>
            </div>
        </div>
    </button>
    <div class="flex-col gap-4 w-full md:w-max min-w-[380px] min-h-[100px] max-h-96 overflow-y-auto bg-white rounded-lg shadow-xl absolute left-0 z-50 py-4"
        x-show="show" @click.stop.outside="show=false; $wire.markAsRead()" style="display:none"
        x-data="myFunction()">
        @if ($notifications->count() == 0)
            <div class="h-[100px] flex justify-center items-center">
                <p class="text-gray-500">No notifications</p>
            </div>
        @endif
        @foreach ($notifications as $notification)
            <div class="flex gap-4 items-center p-4  mx-auto max-w-sm relative cursor-pointer hover:bg-blue-100"
                wire:key="{{ $notification->id }}" @click="redirect(event)" wire:click="markAsRead">
                @if ($notification->read_at == null)
                    <span
                        class="text-xs font-bold uppercase px-2 mt-2 mr-2 text-green-900 bg-green-400 border rounded-full absolute top-0 right-0">New</span>
                @endif
                <span
                    class="text-xs uppercase m-1 py-1 mr-3 text-gray-500 absolute -bottom-2 right-0">{{ $notification->created_at->diffForHumans() }}</span>

                <img class="h-12 w-12 rounded-full object-cover flex-shrink-0"
                    alt="{{ $notification->user_name }}'s avatar"
                    src="{{ URL::asset('img/' . $notification->user_picture) }}" />

                <div>
                    <h4 class="text-lg font-semibold leading-tight text-gray-900 mb-1">{{ $notification->user_name }}
                    </h4>
                    @if ($notification->data['type'] == 'upvote')
                        <p class="text-sm text-gray-600 leading-none">Has upvoted your post <a
                                href="{{ route('post.show', $notification->post_slug) }}"
                                class="font-semibold">{{ $notification->post_title }} </a>
                        </p>
                    @elseif ($notification->data['type'] == 'downvote')
                        <p class="text-sm text-gray-600 leading-none">Has downvoted your post <a
                                href="{{ route('post.show', $notification->post_slug) }}"
                                class="font-semibold">{{ $notification->post_title }} </a>
                        </p>
                    @elseif ($notification->data['type'] == 'comment')
                        <p class="text-sm text-gray-600 leading-none">Has left a comment on your post <a
                                href="{{ route('post.show', ['post' => $notification->post_slug, 'commentId' => $notification->data['comment_id']]) . '#comment-' . $notification->data['comment_id'] }}"
                                class="font-semibold">{{ $notification->post_title }} </a>
                        </p>
                    @elseif ($notification->data['type'] == 'reply')
                        <p class="text-sm text-gray-600 leading-none">Has replied to your comment<a
                                href="{{ route('post.show', ['post' => $notification->post_slug, 'commentId' => $notification->data['comment_id'], 'parentCommentId' => $notification->parent_id]) . '#comment-' . $notification->data['comment_id'] }}"
                                class="font-semibold"> "{{$notification->comment}}"</a>
                        </p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    <script>
        function myFunction() {
            return {
                redirect(event) {
                    const anchor = event.currentTarget.querySelector("a")
                    const href = anchor.href;
                    if (event.target instanceof HTMLAnchorElement) {
                        event.preventDefault();
                    }
                    // to allow the wire:click to trigger
                    setTimeout(() => {
                        window.location.href = href;
                    }, 500);
                }
            }
        }
    </script>
</div>
