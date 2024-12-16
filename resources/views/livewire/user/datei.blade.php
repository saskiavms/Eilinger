<div class="py-6">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-ubuntu text-primary font-semibold">
                {{ __('file.title') }}
            </h2>
            <button wire:click="addEnclosure"
                class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-600 transition-colors">
                {{ __('file.newFile') }}
            </button>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <x-notification />

                <!-- Filters -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            {{ __('file.filter_by_application') }}
                        </label>
                        <select wire:model.live="applicationFilter"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                            <option value="">{{ __('file.all_applications') }}</option>
                            @foreach ($applications as $application)
                                <option value="{{ $application->id }}">{{ $application->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            {{ __('file.filter_by_content') }}
                        </label>
                        <select wire:model.live="contentFilter"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                            <option value="">{{ __('file.all_content_types') }}</option>
                            @foreach ($this->contentTypes as $contentType)
                                <option value="{{ $contentType }}">{{ __('enclosure.' . $contentType) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('file.content') }}</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('file.file') }}</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('file.application') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($enclosuresList as $enclosure)
                                @if ($selectedContentType)
                                    @if (isset($enclosure->{$selectedContentType}))
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ __('enclosure.' . $selectedContentType) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <a href="{{ Storage::disk('s3')->url($enclosure->{$selectedContentType}) }}"
                                                    class="text-indigo-600 hover:text-indigo-900"
                                                    target="_blank">{{ basename($enclosure->{$selectedContentType}) }}</a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $enclosure->application->name }}</td>
                                        </tr>
                                    @endif
                                @else
                                    @foreach ($enclosure->getAttributes() as $column => $value)
                                        @if (in_array($column, ['id', 'created_at', 'updated_at', 'application_id', 'remark', 'is_draft', 'deleted_at']))
                                            @continue
                                        @endif
                                        @if ($value && !\Illuminate\Support\Str::endsWith($column, 'SendLater'))
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ __('enclosure.' . $column) }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <a href="{{ Storage::disk('s3')->url($value) }}"
                                                        class="text-indigo-600 hover:text-indigo-900"
                                                        target="_blank">{{ basename($value) }}</a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $enclosure->application->name }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        {{ __('file.noFiles') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if ($showModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 z-40"></div>
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                    <div class="flex items-center justify-between px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold text-primary">
                            {{ __('file.newFile') }}
                        </h3>
                        <button wire:click="close" type="button" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form wire:submit="saveEnclosure">
                        <div class="px-6 py-4 space-y-4">
                            <!-- Application Select -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    {{ __('file.application') }}
                                </label>
                                <select wire:model.blur="application_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                                    <option hidden>{{ __('attributes.please_select') }}</option>
                                    @foreach ($applications as $application)
                                        <option value="{{ $application->id }}">{{ $application->name }}</option>
                                    @endforeach
                                </select>
                                @error('application_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Content Select -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    {{ __('file.content') }}
                                </label>
                                <select wire:model.blur="column"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                                    <option hidden>{{ __('attributes.please_select') }}</option>
                                    @foreach ($this->columns as $column => $value)
                                        @if (in_array($value, ['id', 'created_at', 'updated_at', 'application_id', 'remark', 'is_draft', 'deleted_at']))
                                            @continue
                                        @endif
                                        @if (!\Illuminate\Support\Str::endsWith($value, 'SendLater'))
                                            <option value="{{ $value }}">{{ __('enclosure.' . $value) }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('column')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- File Input -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    {{ __('file.file') }}
                                </label>
                                <div class="mt-1 flex items-center">
                                    <label
                                        class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-600 transition-colors cursor-pointer">
                                        <span>{{ __('attributes.choose_file') }}</span>
                                        <input wire:model="file" type="file" class="hidden">
                                    </label>
                                    <span class="ml-3 text-sm text-gray-600">
                                        @if ($file)
                                            {{ $file->getClientOriginalName() }}
                                        @else
                                            {{ __('attributes.no_file_chosen') }}
                                        @endif
                                    </span>
                                </div>
                                @error('file')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
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
</div>
