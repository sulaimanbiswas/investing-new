<form method="post" action="{{ route('profile.update') }}" class="space-y-5" enctype="multipart/form-data">
    @csrf
    @method('patch')

    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
        <input type="text" value="{{ '@' . $user->username }}" disabled
            class="w-full px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-gray-600 cursor-not-allowed">
        <p class="mt-1 text-xs text-gray-500">Username cannot be changed.</p>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
        <input type="text" value="{{ $user->email }}" disabled
            class="w-full px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-gray-600 cursor-not-allowed">
        <p class="mt-1 text-xs text-gray-500">Email cannot be changed.</p>
    </div>

    <div>
        <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">Profile Avatar</label>
        <div class="flex items-center gap-5">
            <div id="avatar-container" class="relative">
                @if($user->avatar_path)
                    <img id="avatar-preview" src="{{ asset('uploads/avatar/' . $user->avatar_path) }}" alt="avatar"
                        class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 shadow-sm">
                @else
                    <div id="avatar-placeholder"
                        class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center border-2 border-gray-200 shadow-sm">
                        <i class="fas fa-user text-3xl text-indigo-400"></i>
                    </div>
                    <img id="avatar-preview" src="" alt="avatar"
                        class="hidden w-20 h-20 rounded-full object-cover border-2 border-gray-200 shadow-sm">
                @endif
            </div>
            <div class="flex-1">
                <input id="avatar" name="avatar" type="file" accept="image/*"
                    class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 file:cursor-pointer cursor-pointer"
                    onchange="previewAvatar(event)">
                <p class="mt-1 text-xs text-gray-500">JPEG, PNG, WEBP up to 2MB.</p>
            </div>
        </div>
        @error('avatar')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-4 pt-2">
        <button type="submit"
            class="px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition shadow-sm">
            Save Changes
        </button>
        @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                class="text-sm text-green-600 font-medium">✓ Profile updated successfully!</p>
        @endif
    </div>
</form>

@push('scripts')
    <script>
        function previewAvatar(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const preview = document.getElementById('avatar-preview');
                    const placeholder = document.getElementById('avatar-placeholder');

                    preview.src = e.target.result;
                    preview.classList.remove('hidden');

                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endpush