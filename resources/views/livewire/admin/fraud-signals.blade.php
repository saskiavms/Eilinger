<div>
    <!-- Header -->
    <div class="mb-6 flex items-start justify-between">
        <div>
            <h1 class="text-3xl font-ubuntu text-primary font-semibold">Betrugssignale</h1>
            <p class="mt-2 text-sm text-gray-700">Automatisch erkannte Auffälligkeiten zur manuellen Prüfung</p>
        </div>
        <button wire:click="exportCsv"
            class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-600 shadow-sm">
            <i class="bi bi-download mr-2"></i>
            CSV exportieren
        </button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Offene Signale</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $openCount }}</p>
            </div>
            <div class="p-3 bg-yellow-50 rounded-full">
                <i class="bi bi-exclamation-triangle text-xl text-yellow-500"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Davon kritisch</p>
                <p class="text-2xl font-semibold text-red-600">{{ $highCount }}</p>
            </div>
            <div class="p-3 bg-red-50 rounded-full">
                <i class="bi bi-shield-exclamation text-xl text-red-500"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Erkannte Typen</p>
                <p class="text-2xl font-semibold text-gray-900">{{ count($signalTypes) }}</p>
            </div>
            <div class="p-3 bg-blue-50 rounded-full">
                <i class="bi bi-search text-xl text-blue-500"></i>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Typ</label>
                <select wire:model.live="filterType"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <option value="">Alle Typen</option>
                    @foreach($signalTypes as $type)
                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Schweregrad</label>
                <select wire:model.live="filterSeverity"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <option value="">Alle</option>
                    <option value="high">Kritisch</option>
                    <option value="medium">Mittel</option>
                    <option value="low">Niedrig</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model.live="filterStatus"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <option value="open">Offen</option>
                    <option value="reviewed">Geprüft</option>
                    <option value="false_positive">Falsch positiv</option>
                    <option value="">Alle</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Signals Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Signal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Benutzer A</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Benutzer B</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anträge</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Erkannt</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktionen</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($signals as $signal)
                        <tr class="hover:bg-gray-50 {{ $signal->is_false_positive ? 'opacity-50' : '' }}">

                            <!-- Signal type + severity -->
                            <td class="px-4 py-4">
                                <span @class([
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                    'bg-red-100 text-red-800'    => $signal->severity === 'high',
                                    'bg-yellow-100 text-yellow-800' => $signal->severity === 'medium',
                                    'bg-blue-100 text-blue-800'  => $signal->severity === 'low',
                                ])>
                                    {{ $signal->type->label() }}
                                </span>
                                @if($signal->details)
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $signal->details['field_name'] ?? ($signal->details['field'] ?? '') }}
                                    </p>
                                @endif
                            </td>

                            <!-- User A -->
                            <td class="px-4 py-4">
                                @if($signal->user)
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $signal->user->firstname }} {{ $signal->user->lastname }}
                                        {{ $signal->user->name_inst }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $signal->user->email }}</p>
                                    @if($signal->user->trashed())
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs bg-gray-100 text-gray-500">gelöscht</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-sm">—</span>
                                @endif
                            </td>

                            <!-- User B -->
                            <td class="px-4 py-4">
                                @if($signal->relatedUser)
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $signal->relatedUser->firstname }} {{ $signal->relatedUser->lastname }}
                                        {{ $signal->relatedUser->name_inst }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $signal->relatedUser->email }}</p>
                                    @if($signal->relatedUser->trashed())
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs bg-gray-100 text-gray-500">gelöscht</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-sm">—</span>
                                @endif
                            </td>

                            <!-- Applications -->
                            <td class="px-4 py-4 text-sm text-gray-500">
                                @if($signal->application)
                                    <a href="{{ route('admin_antrag', ['application_id' => $signal->application->id, 'locale' => app()->getLocale()]) }}"
                                        class="text-primary hover:underline block">
                                        {{ $signal->application->name ?? 'Antrag #'.$signal->application->id }}
                                    </a>
                                @endif
                                @if($signal->relatedApplication)
                                    <a href="{{ route('admin_antrag', ['application_id' => $signal->relatedApplication->id, 'locale' => app()->getLocale()]) }}"
                                        class="text-primary hover:underline block">
                                        {{ $signal->relatedApplication->name ?? 'Antrag #'.$signal->relatedApplication->id }}
                                    </a>
                                @endif
                                @if(!$signal->application && !$signal->relatedApplication)
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>

                            <!-- Detected at -->
                            <td class="px-4 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $signal->created_at->format('d.m.Y H:i') }}
                                @if($signal->reviewed_at)
                                    <p class="text-xs text-gray-400 mt-1">
                                        Geprüft {{ $signal->reviewed_at->format('d.m.Y') }}
                                        @if($signal->reviewedBy) von {{ $signal->reviewedBy->firstname }} @endif
                                    </p>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if(!$signal->reviewed_at)
                                    <div class="flex gap-3">
                                        <button wire:click="markReviewed({{ $signal->id }})"
                                            wire:confirm="Signal als geprüft markieren?"
                                            class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded text-white bg-primary hover:bg-primary-600">
                                            <i class="bi bi-check mr-1"></i> Geprüft
                                        </button>
                                        <button wire:click="markFalsePositive({{ $signal->id }})"
                                            wire:confirm="Als falsch positiv markieren?"
                                            class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded text-gray-700 bg-gray-100 hover:bg-gray-200">
                                            <i class="bi bi-x mr-1"></i> Falsch positiv
                                        </button>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">
                                        {{ $signal->is_false_positive ? 'Falsch positiv' : 'Geprüft' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                                <i class="bi bi-shield-check text-2xl text-gray-300 block mb-2"></i>
                                Keine Signale gefunden
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white border-t border-gray-200 px-4 py-3">
            {{ $signals->links() }}
        </div>
    </div>
</div>
