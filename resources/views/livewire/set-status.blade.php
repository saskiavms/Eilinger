@php use App\Enums\ApplStatus; @endphp
<div class="bg-white rounded-lg p-6">
    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    <h3 class="text-2xl font-ubuntu text-primary font-semibold mb-6">Status des Antrags</h3>

    <form wire:submit.prevent="setStatus">
        <div class="space-y-6">
            <!-- Current Status -->
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-md text-gray-700">
                    <span class="font-medium">Aktueller Status:</span>
                    <span class="ml-2">{{ __('application.status_name.' . $application->appl_status->name) }}</span>
                </p>
            </div>

            <!-- Status Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column: Status Selection -->
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

                <!-- Right Column: Status-specific fields -->
                <div>
                    <!-- Approval Date -->
                    <div x-show="$wire.status === '{{ ApplStatus::APPROVED->value }}'" class="space-y-4">
                        <div>
                            <label for="approval_appl" class="block text-md font-medium text-gray-700">
                                Genehmigungsdatum
                            </label>
                            <input wire:model="approval_appl" type="date" id="approval_appl"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            @error('approval_appl')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <!-- Rejection Reason -->
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
                </div>
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

    <!-- Payments Section -->
    <div class="mt-8 bg-white rounded-lg p-6 border-t border-gray-200">
        <!-- Payment Messages -->
        @if (session()->has('payment_message'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('payment_message') }}
            </div>
        @endif
        
        @if (session()->has('payment_error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('payment_error') }}
            </div>
        @endif

        <h3 class="text-xl font-ubuntu text-primary font-semibold mb-6">Zahlungen</h3>

        <!-- Existing Payments -->
        @if($application->payments->count() > 0)
            <div class="mb-6">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Bestehende Zahlungen</h4>
                
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <p class="text-sm text-gray-600 mb-2">
                        <span class="font-medium">Gesamtbetrag bezahlt:</span> 
                        {{ number_format($application->total_paid, 2) }} CHF
                    </p>
                    @if($application->last_payment_date)
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Letzte Zahlung:</span> 
                            {{ $application->last_payment_date->format('d.m.Y') }}
                        </p>
                    @endif
                </div>

                <div class="space-y-3">
                    @foreach($application->payments->sortByDesc('payment_date') as $payment)
                        <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg">
                            <div class="flex-1">
                                <div class="flex items-center space-x-4">
                                    <span class="font-medium text-gray-900">{{ number_format($payment->amount, 2) }} CHF</span>
                                    <span class="text-gray-600">{{ $payment->payment_date->format('d.m.Y') }}</span>
                                    @if($payment->notes)
                                        <span class="text-sm text-gray-500">{{ $payment->notes }}</span>
                                    @endif
                                </div>
                            </div>
                            <button wire:click="deletePayment({{ $payment->id }})" 
                                wire:confirm="Sind Sie sicher, dass Sie diese Zahlung löschen möchten?"
                                class="text-red-600 hover:text-red-800 text-sm font-medium">
                                Löschen
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <p class="text-gray-600 mb-6">Noch keine Zahlungen erfasst.</p>
        @endif

        <!-- Add New Payment Form -->
        <div class="border-t border-gray-200 pt-6">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Neue Zahlung hinzufügen</h4>
            
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="new_payment_amount" class="block text-sm font-medium text-gray-700">
                            Betrag (CHF)
                        </label>
                        <input wire:model="new_payment_amount" type="number" step="0.01" id="new_payment_amount"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        @error('new_payment_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="new_payment_date" class="block text-sm font-medium text-gray-700">
                            Zahlungsdatum
                        </label>
                        <input wire:model="new_payment_date" type="date" id="new_payment_date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        @error('new_payment_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="new_payment_notes" class="block text-sm font-medium text-gray-700">
                            Notizen (optional)
                        </label>
                        <input wire:model="new_payment_notes" type="text" id="new_payment_notes" 
                            placeholder="z.B. 1. Teilzahlung"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        @error('new_payment_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button wire:click="addPayment" type="button" wire:loading.attr="disabled"
                        class="inline-flex justify-center rounded-md border border-transparent bg-primary-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-50">
                        <span wire:loading.remove wire:target="addPayment">Zahlung hinzufügen</span>
                        <span wire:loading wire:target="addPayment">Speichern...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
