<div>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Picture') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile picture.") }}
        </p>
    </header>
    <form method="post" action="{{ route('profile.picture') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div x-data="showImage()">
            <label for="pp" class="block mt-6">
                <span class="sr-only">Choose File</span>
                <img id="preview" src="{{ URL::asset('/img/' . Auth::user()->picture) }}"
                class="rounded shadow h-56 w-48 object-cover cursor-pointer">
            </label>
            <input type="file" name="image" id="pp" @change="showPreview(event)" class="hidden" accept="image/*"/>
            <x-input-error class="mt-2" :messages="$errors->get('image')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'picture-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</div>
<script>
    function showImage() {
        return {
            showPreview(event) {
                console.log(event);
                if (event.target.files.length > 0) {
                    const src = URL.createObjectURL(event.target.files[0]);
                    const preview = document.getElementById("preview");
                    preview.src = src;
                    preview.style.display = "block";
                }
            }
        }
    }
</script>
