<form wire:submit="saveFinancing">
    @csrf
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-primary mb-2">{{ __('financing.title') }}</h3>
        <p class="text-sm text-gray-600">{{ __('financing.subTitle') }}</p>
    </div>

    <x-notification />

    <!-- Main Financing -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Personal Contribution -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('financing.personal_contribution') }}
            </label>
            <input wire:model.blur="personal_contribution" type="number"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
            @error('personal_contribution')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Netto Income -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('financing.netto_income') }}
            </label>
            <input wire:model.blur="netto_income" type="number"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
            @error('netto_income')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Assets -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('financing.assets') }}
            </label>
            <input wire:model.blur="assets" type="number"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
            @error('assets')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Scholarship -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('financing.scholarship') }}
            </label>
            <input wire:model.blur="scholarship" type="number"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
            @error('scholarship')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Other Income Section -->
    <div class="mb-6">
        <h4 class="text-lg font-medium text-primary mb-4">{{ __('financing.other') }}</h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Other Income -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('financing.other_income') }}
                </label>
                <input wire:model.blur="other_income" type="number"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                @error('other_income')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Income Where -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('financing.income_where') }}
                </label>
                <input wire:model.blur="income_where" type="text"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                @error('income_where')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Income Who -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('financing.incomeWho') }}
                </label>
                <input wire:model.blur="income_who" type="text"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                @error('income_who')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Total Amount -->
    <div class="border-t border-gray-200 pt-4 mb-8">
        <p class="text-right text-lg font-medium">
            {{ __('financing.total') }} @convert($this->getAmountFinancing(), $this->myCurrency->abbreviation)
        </p>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-center">
        <button type="submit"
            class="px-6 py-2 bg-success text-white rounded-md hover:bg-successHover transition-colors">
            {{ __('attributes.save') }}
        </button>
    </div>
</form>
