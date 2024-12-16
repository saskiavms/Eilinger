<!-- Page Header -->
<div>
    <div class="mb-6">
        <h1 class="text-3xl font-ubuntu text-primary font-semibold">
            Antrag {{ $application->name }}
            <span class="text-lg font-normal text-gray-600">(Bereich:
                {{ __('application.bereichs_name.' . $application->bereich->name) }})</span>
        </h1>
    </div>

    <!-- Application Content -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6">
            <!-- Accordion -->
            <div class="divide-y divide-gray-200" x-data="{ activeTab: 'application' }">
                <!-- Application Details -->
                <div class="py-4">
                    @include('partials.accAppl')
                </div>

                <!-- User Details -->
                <div class="py-4">
                    @if ($user->type == App\Enums\Types::nat)
                        @if ($application->form == App\Enums\Form::Darlehen)
                            @include('partials.accUserNatDarlehen')
                        @else
                            @include('partials.accUserNat')
                        @endif
                    @else
                        @include('partials.accUserJur')
                    @endif
                </div>

                <!-- Address Details -->
                <div class="py-4">
                    @include('partials.accAddress')
                </div>

                <!-- Additional Address -->
                <div class="py-4">
                    @if ($application->form == App\Enums\Form::Darlehen)
                        @include('partials.accAboardAddress')
                    @else
                        @include('partials.accAbwAddress')
                    @endif
                </div>

                <!-- Education Details -->
                @if ($application->form == App\Enums\Form::Stipendium)
                    <div class="py-4">
                        @include('partials.accEducation')
                    </div>
                @endif

                <!-- Account Details -->
                <div class="py-4">
                    @include('partials.accAccount')
                </div>

                <!-- Family Details -->
                @if ($application->form == App\Enums\Form::Stipendium)
                    <div class="py-4">
                        @include('partials.accParents')
                    </div>
                    <div class="py-4">
                        @include('partials.accSiblings')
                    </div>
                @endif

                <!-- Cost Details -->
                <div class="py-4">
                    @if ($application->form == App\Enums\Form::Stipendium)
                        @include('partials.accCost')
                    @else
                        @include('partials.accCostDarlehen')
                    @endif
                </div>

                <!-- Financing Details -->
                <div class="py-4">
                    @if ($user->type == App\Enums\Types::nat)
                        @include('partials.accFinancing')
                    @else
                        @include('partials.accFinancingOrganisation')
                    @endif
                </div>

                <!-- Enclosures -->
                <div class="py-4">
                    @if ($application->form == App\Enums\Form::Stipendium)
                        @include('partials.accEnclosure')
                    @else
                        @if ($user->type == App\Enums\Types::nat)
                            @include('partials.accEnclosureDarlehenPrivat')
                        @else
                            @include('partials.accEnclosureOrganisation')
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Status and Messages Section -->
    <div class="mt-6 space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            @livewire('set-status', ['application' => $application])
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            @livewire('messages-section', ['application' => $application])
        </div>
    </div>
</div>
