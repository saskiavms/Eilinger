<div class="p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6">Foundation Settings</h2>

    <form wire:submit="save">
        <div class="space-y-6">
            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Foundation Name</label>
                <input type="text" wire:model="foundation.name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                @error('foundation.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Street -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Street</label>
                <input type="text" wire:model="foundation.strasse"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                @error('foundation.strasse') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- City -->
            <div>
                <label class="block text-sm font-medium text-gray-700">City</label>
                <input type="text" wire:model="foundation.ort"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                @error('foundation.ort') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Country -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Country</label>
                <input type="text" wire:model="foundation.land"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                @error('foundation.land') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Next Council Meeting -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Next Council Meeting</label>
                <input type="date" wire:model="foundation.nextCouncilMeeting"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                @error('foundation.nextCouncilMeeting') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark transition-colors">
                    Save Changes
                </button>
            </div>
        </div>
    </form>

    @if (session()->has('message'))
        <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-md">
            {{ session('message') }}
        </div>
    @endif
</div>
