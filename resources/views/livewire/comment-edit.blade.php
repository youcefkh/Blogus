<form wire:submit.prevent="editComment" class="mb-6 px-4" x-data="{
    init() {
        this.$refs.textArea.focus();
    }
}">
    <div
        class="py-2 px-4 mb-4 bg-white rounded-lg rounded-t-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <label for="comment" class="sr-only">Your comment</label>
        <textarea x-ref="textArea" id="comment"
            class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 dark:bg-gray-800"
            placeholder="Edit your comment" wire:model="commentValue" required></textarea>
    </div>
    <button type="submit"
        class="inline-flex items-center mr-2 py-2.5 px-4 text-xs font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
        Edit comment
    </button>
    <button type="button"
        @click="$wire.emitUp('cancelEdit')"
        class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-black bg-slate-100 rounded-lg focus:ring-4">
        Cancel
    </button>
</form>
