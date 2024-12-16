<div class="py-6">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-ubuntu text-primary font-semibold">
                {{ __('application.applications') }}
            </h2>
            <button wire:click="addApplication()"
                class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-600 transition-colors">
                {{ __('application.newApplication') }}
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <x-notification />

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('application.application') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('application.bereich') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('application.form') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('application.createdAt') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('application.updatedAt') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($applications as $application)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $application->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ __('application.bereichs_name.' . $application->bereich->name) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ __('application.form_name.' . $application->form->name) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $application->created_at ? $application->created_at->format('d.m.Y H:i') : null }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $application->updated_at ? $application->updated_at->format('d.m.Y H:i') : null }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 space-x-2">
                                    <a href="{{ route('user_antrag', ['application_id' => $application->id, 'locale' => app()->getLocale()]) }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-primary text-white text-sm rounded-md hover:bg-primary-600 transition-colors">
                                        {{ __('attributes.edit') }}
                                    </a>
                                    <button wire:click="deleteApplication({{ $application->id }})"
                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition-colors">
                                        {{ __('attributes.delete') }}
                                    </button>
                                    <a href="{{ route('user_nachricht', ['application_id' => $application->id, 'locale' => app()->getLocale()]) }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-success text-white text-sm rounded-md hover:bg-green-700 transition-colors">
                                        {{ __('attributes.showMessages') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    {{ __('application.no_applications') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if ($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-40"></div>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-primary">
                        {{ __('application.newApplication') }}
                    </h3>
                    <button wire:click="close" type="button" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="px-6 py-4 space-y-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('application.name') }}*
                            </label>
                            <input wire:model.live="name" type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bereich -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('application.bereich') }}*
                            </label>
                            <select wire:model.blur="bereich" wire:change="updateBereich($event.target.value)"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                                <option value="">{{ __('attributes.please_select') }}</option>
                                @foreach (App\Enums\Bereich::cases() as $bereich)
                                    <option value="{{ $bereich }}">
                                        {{ __('application.bereichs_name.' . $bereich->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bereich')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Form -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('application.desiredForm') }}*
                            </label>
                            <select wire:model.blur="form"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                                <option value="">{{ __('attributes.please_select') }}</option>
                                @foreach ($this->formOptions as $form)
                                    <option value="{{ $form }}">
                                        {{ __('application.form_name.' . $form) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('form')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Currency -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('application.desiredCurrency') }}*
                            </label>
                            <select wire:model.blur="currency_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                                <option value="">{{ __('attributes.please_select') }}</option>
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->currency }}</option>
                                @endforeach
                            </select>
                            @error('currency_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    {{ __('application.startDate') }}*
                                </label>
                                <input wire:model.live="start_appl" type="date"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                                @error('start_appl')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    {{ __('application.endDate') }}
                                </label>
                                <input wire:model.live="end_appl" type="date"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                                @error('end_appl')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Radio Buttons -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('application.followAppl') }}*
                            </label>
                            <div class="flex space-x-4">
                                <label class="inline-flex items-center">
                                    <input wire:model.blur="is_first" type="radio" value="1"
                                        class="form-radio text-accent border-gray-300 focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">{{ __('application.firstAppl') }}</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input wire:model.blur="is_first" type="radio" value="0"
                                        class="form-radio text-accent border-gray-300 focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">{{ __('application.followAppl') }}</span>
                                </label>
                            </div>
                            @error('is_first')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- First Application Select (Conditional) -->
                        @if ($this->visible)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    {{ __('application.firstAppl') }}
                                </label>
                                <select wire:model.blur="main_appl_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                                    <option value="">{{ __('attributes.please_select') }}</option>
                                    @foreach ($first_applications as $first_application)
                                        <option value="{{ $first_application->id }}">{{ $first_application->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('first_applications')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>

                    <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-2 rounded-b-lg">
                        <button wire:click="close" type="button"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                            {{ __('attributes.close') }}
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-600 transition-colors">
                            {{ __('attributes.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
