<div>
	<div class="mb-6">
		<h1 class="text-3xl font-ubuntu text-primary font-semibold">Report Generator</h1>
		<p class="mt-2 text-gray-600">Erstellt einen Report für das ausgewählte Jahr</p>
	</div>


	<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
		<div class="overflow-x-auto">

			<div class="mb-4 flex items-center space-x-4">
				<select wire:model.live="selectedYear" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
					<option value="">Alle Jahre</option>
					@if($hasNoDateApplications)
						<option value="no_date">Kein Datum</option>
					@endif
					@foreach($years as $year)
						<option value="{{ $year }}">{{ $year }}</option>
					@endforeach
				</select>
			</div>

			@if (session()->has('error'))
				<div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
					{{ session('error') }}
				</div>
			@endif

			<table class="min-w-full divide-y divide-gray-200">
				<thead class="bg-gray-50">
					<tr>
						<th scope="col"
							class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
							{{ __('application.name') }}
						</th>
						<th scope="col"
							class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
							{{ __('application.area') }}
						</th>
						<th scope="col"
							class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
							{{ __('user.lastname') }}
						</th>
						<th scope="col"
							class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
							{{ __('user.firstname') }}
						</th>
						<th scope="col"
							class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
							{{ __('user.email') }}
						</th>
						<th scope="col"
							class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
							Genehmigungsdatum
						</th>
						<th scope="col"
							class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
							Aktionen
						</th>
					</tr>
				</thead>
				<tbody class="bg-white divide-y divide-gray-200">
					@forelse ($applications as $application)
						<tr class="hover:bg-gray-50">
							<td class="px-6 py-4 whitespace-nowrap">
								<a href="{{ route('admin_antrag', ['application_id' => $application->id, 'locale' => app()->getLocale()]) }}"
									class="text-primary-600 hover:text-primary-900">
									{{ $application->name }}
								</a>
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-gray-900">
								{{ $application->bereich }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-gray-900">
								{{ $application->user->lastname }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-gray-900">
								{{ $application->user->firstname }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-gray-900">
								{{ $application->user->email }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-gray-900">
								@if($application->approval_appl)
									{{ $application->approval_appl->format('d.m.Y') }}
								@else
									-
								@endif
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
								<button wire:click="generateReport({{ $application->id }})"
									wire:loading.attr="disabled"
									wire:target="generateReport({{ $application->id }})"
									class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-primary-700 bg-primary-100 hover:bg-primary-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
									<svg class="-ml-0.5 mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
										<path d="M10.75 2.75a.75.75 0 00-1.5 0v8.614L6.295 8.235a.75.75 0 10-1.09 1.03l4.25 4.5a.75.75 0 001.09 0l4.25-4.5a.75.75 0 00-1.09-1.03l-2.955 3.129V2.75z"/>
										<path d="M3.5 12.75a.75.75 0 00-1.5 0v2.5A2.75 2.75 0 004.75 18h10.5A2.75 2.75 0 0018 15.25v-2.5a.75.75 0 00-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5z"/>
									</svg>
									<span wire:loading.remove wire:target="generateReport({{ $application->id }})">
										Report
									</span>
									<span wire:loading wire:target="generateReport({{ $application->id }})">
										Generiere...
									</span>
								</button>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="7" class="px-6 py-4 text-center text-gray-500">
								{{ __('application.no_projects') }}
							</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div class="px-6 py-4 border-t border-gray-200">
			{{ $applications->links() }}
		</div>
	</div>
</div>
