@php use App\Enums\ApplStatus; @endphp
<div class="bg-white rounded-lg p-6">
    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    <h3 class="text-2xl font-ubuntu text-primary font-semibold mb-6">Status des Antrags</h3>

    <form wire:submit="setStatus">
        <div class="space-y-6">
            <!-- Current Status -->
            <div>
                <p class="text-md text-gray-700">
                    <span class="font-medium">Aktueller Status:</span>
                    <span class="ml-2">{{ __('application.status_name.' . $application->appl_status->name) }}</span>
                </p>
            </div>

            <!-- New Status Selection -->
            <div>
                <p class="text-md font-medium text-gray-700 mb-2">Neuer Status:</p>
                <div class="space-y-2">
                    @foreach (ApplStatus::cases() as $applStatus)
                        <label class="flex items-center">
                            <input type="radio" wire:model="status" value="{{ $applStatus->value }}"
                                class="form-radio h-4 w-4 text-primary-600 border-gray-300 focus:ring-primary-500">
                            <span class="ml-2 text-gray-700">{{ __('application.status_name.' . $applStatus->name) }}</span>
                        </label>
                    @endforeach
                </div>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Approval Date (shown when status is approved) -->
            <div x-show="$wire.status === '{{ ApplStatus::APPROVED->value }}'">
                <label for="approval_appl" class="block text-md font-medium text-gray-700">
                    Genehmigungsdatum
                </label>
                <input wire:model="approval_appl" type="date" id="approval_appl"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                @error('approval_appl')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Rejection Reason (shown when status is rejected) -->
            <div x-show="$wire.status === '{{ ApplStatus::BLOCKED->value }}'">
                <label for="reason_rejected" class="block text-md font-medium text-gray-700">
                    {{ __('application.reason_rejected') }}
                </label>
                <input wire:model="reason_rejected" type="text" id="reason_rejected"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                @error('reason_rejected')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex justify-center rounded-md border border-transparent bg-primary-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                    Status ändern
                </button>
            </div>
        </div>
    </form>
</div>
