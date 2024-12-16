<form wire:submit="saveParents">
    @csrf
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-primary mb-2">{{ __('parents.title') }}</h3>
        <p class="text-sm text-gray-600">{{ __('parents.subtitle') }}</p>
    </div>

    <x-notification />

    @foreach ($parentsList as $index => $parent)
        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-medium text-primary">
                    {{ __('parents.parent_type') }} {{ $index + 1 }}
                </h4>
                @if (count($parentsList) > 1)
                    <button type="button" wire:click="removeParent({{ $index }})"
                        class="text-red-600 hover:text-red-800 transition-colors">
                        <i class="bi bi-trash"></i>
                    </button>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Parent Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('parents.parent_type') }}
                    </label>
                    <select wire:model.blur="parentsList.{{ $index }}.parent_type"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                        <option value="">{{ __('attributes.please_select') }}</option>
                        @foreach (App\Enums\ParentType::cases() as $parent_type)
                            <option value="{{ $parent_type }}">
                                {{ __('parents.parent_type_name.' . $parent_type->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('parentsList.' . $index . '.parent_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Last Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('parents.lastname') }}
                    </label>
                    <input wire:model.blur="parentsList.{{ $index }}.lastname" type="text"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                    @error('parentsList.' . $index . '.lastname')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- First Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('parents.firstname') }}
                    </label>
                    <input wire:model.blur="parentsList.{{ $index }}.firstname" type="text"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                    @error('parentsList.' . $index . '.firstname')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Birthday -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('parents.birthday') }}
                    </label>
                    <input wire:model.blur="parentsList.{{ $index }}.birthday" type="date"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                    @error('parentsList.' . $index . '.birthday')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('parents.phone') }}
                    </label>
                    <input wire:model.blur="parentsList.{{ $index }}.phone" type="text"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                    @error('parentsList.' . $index . '.phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('parents.address') }}
                    </label>
                    <input wire:model.blur="parentsList.{{ $index }}.address" type="text"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                    @error('parentsList.' . $index . '.address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- PLZ/Ort -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('parents.plz_ort') }}
                    </label>
                    <input wire:model.blur="parentsList.{{ $index }}.plz_ort" type="text"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                    @error('parentsList.' . $index . '.plz_ort')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Since -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('parents.since') }}
                    </label>
                    <input wire:model.blur="parentsList.{{ $index }}.since" type="date"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                    @error('parentsList.' . $index . '.since')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Job -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('parents.job') }}
                    </label>
                    <input wire:model.blur="parentsList.{{ $index }}.job" type="text"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                    @error('parentsList.' . $index . '.job')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Employer -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('parents.employer') }}
                    </label>
                    <input wire:model.blur="parentsList.{{ $index }}.employer" type="text"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                    @error('parentsList.' . $index . '.employer')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Job Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('parents.job_type') }}
                    </label>
                    <select wire:model.blur="parentsList.{{ $index }}.job_type"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                        <option value="">{{ __('attributes.please_select') }}</option>
                        @foreach (App\Enums\JobType::cases() as $job_type)
                            <option value="{{ $job_type }}">
                                {{ __('parents.job_type_name.' . $job_type->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('parentsList.' . $index . '.job_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="border-b border-gray-200 my-8"></div>
        </div>
    @endforeach

    <!-- Add Parent Button -->
    <div class="flex justify-end mb-6">
        <button type="button" wire:click.prevent="addParent"
            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
            {{ __('parents.addParents') }}
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
