<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Please confirm your password before continuing.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">
                Password
            </label>

            <input id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" type="password"
                name="password" required autocomplete="current-password" autofocus>

            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-4 flex justify-end">
            <button type="submit" class="rounded-md bg-gray-800 px-4 py-2 text-white hover:bg-gray-700">
                Confirm Password
            </button>
        </div>
    </form>
</x-guest-layout>