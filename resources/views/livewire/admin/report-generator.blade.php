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
					@foreach($years as $year)
						<option value="{{ $year }}">{{ $year }}</option>
					@endforeach
				</select>

				<button wire:click="generateReports" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-600 transition-colors" @if($isGenerating) disabled @endif>
					<span wire:loading.remove wire:target="generateReports">Generiere Report</span>
					<span wire:loading wire:target="generateReports">Generating...</span>
				</button>

				@if($downloadUrl)
					<button wire:click="downloadReport" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
						Download Reports
					</button>
				@endif
			</div>
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
					</tr>
				</thead>
				<tbody class="bg-white divide-y divide-gray-200">
					@forelse ($applications as $application)
						<tr class="hover:bg-gray-50">
							<td class="px-6 py-4 whitespace-nowrap">
								<a href="{{ route('admin_antrag', ['application_id' => $application->id, 'locale' => app()->getLocale()]) }}"
									class="text-primary-600 hover:text-primary-900">
									{{ $application->name }}
									<span class="text-gray-500">({{ $application->appl_status }})</span>
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
						</tr>
					@empty
						<tr>
							<td colspan="5" class="px-6 py-4 text-center text-gray-500">
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
