<form wire:submit="saveSiblings">
    @csrf
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-primary mb-2">{{ __('sibling.title') }}</h3>
        <p class="text-sm text-gray-600">{{ __('sibling.subtitle') }}</p>
    </div>

    <x-notification />

    @foreach ($siblingsList as $index => $sibling)
        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-medium text-primary">
                    {{ __('sibling.title') }} {{ $index + 1 }}
                </h4>
                @if (count($siblingsList) > 1)
                    <button type="button" wire:click="removeSibling({{ $index }})"
                        class="text-red-600 hover:text-red-800 transition-colors">
                        <i class="bi bi-trash"></i>
                    </button>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Last Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('sibling.lastname') }}
                    </label>
                    <input wire:model.blur="siblingsList.{{ $index }}.lastname" type="text"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                    @error('siblingsList.' . $index . '.lastname')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- First Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('sibling.firstname') }}
                    </label>
                    <input wire:model.blur="siblingsList.{{ $index }}.firstname" type="text"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                    @error('siblingsList.' . $index . '.firstname')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Birth Year -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('sibling.birthyear') }}
                    </label>
                    <input wire:model.blur="siblingsList.{{ $index }}.birth_year" type="number"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                    @error('siblingsList.' . $index . '.birth_year')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Place of Residence -->
                <div class="lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('sibling.place_of_residence') }}
                    </label>
                    <input wire:model.blur="siblingsList.{{ $index }}.place_of_residence" type="text"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                    @error('siblingsList.' . $index . '.place_of_residence')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- First Row: Education and Graduation Year -->
                <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Education -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('sibling.education') }}
                        </label>
                        <input wire:model.blur="siblingsList.{{ $index }}.education" type="text"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                        @error('siblingsList.' . $index . '.education')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Graduation Year -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('sibling.graduation_year') }}
                        </label>
                        <input wire:model.blur="siblingsList.{{ $index }}.graduation_year" type="number"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                        @error('siblingsList.' . $index . '.graduation_year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Second Row: Get Amount and Support Site -->
                <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Get Amount -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('sibling.get_amount') }}
                        </label>
                        <select wire:model.blur="siblingsList.{{ $index }}.get_amount"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                            <option value="">{{ __('attributes.please_select') }}</option>
                            @foreach (App\Enums\GetAmount::cases() as $getAmount)
                                <option value="{{ $getAmount }}">
                                    {{ __('sibling.get_amount_name.' . $getAmount->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('siblingsList.' . $index . '.get_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Support Site -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('sibling.support_site') }}
                        </label>
                        <input wire:model.blur="siblingsList.{{ $index }}.support_site" type="text"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                        @error('siblingsList.' . $index . '.support_site')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="border-b border-gray-200 my-8"></div>
        </div>
    @endforeach

    <!-- Add Sibling Button -->
    <div class="flex justify-end mb-6">
        <button type="button" wire:click.prevent="addSibling"
            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
            {{ __('sibling.addSibling') }}
        </button>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-center">
        <button type="submit"
            class="px-6 py-2 bg-success text-white rounded-md hover:bg-successHover transition-colors">
            {{ __('attributes.save') }}
        </button>
    </div>
</form>
