<div class="py-6">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <x-application-progress :current-step="$currentStep" :total-steps="12" />

        <x-application-step :current-step="$currentStep" :step-number="1" :total-steps="12" :title="__('user.applicant')"
            component="antrag.user-nat-form" />

        <x-application-step :current-step="$currentStep" :step-number="2" :total-steps="12" :title="__('address.title')"
            component="antrag.address-form" />

        <x-application-step :current-step="$currentStep" :step-number="3" :total-steps="12" :title="__('address.titleWochen')"
            component="antrag.abweichende-address-form" />

        <x-application-step :current-step="$currentStep" :step-number="4" :total-steps="12" :title="__('education.education')"
            component="antrag.education-form" />

        <x-application-step :current-step="$currentStep" :step-number="5" :total-steps="12" :title="__('account.title')"
            component="antrag.account-form" />

        <x-application-step :current-step="$currentStep" :step-number="6" :total-steps="12" :title="__('parents.title')"
            component="antrag.parent-form" />

        <x-application-step :current-step="$currentStep" :step-number="7" :total-steps="12" :title="__('sibling.title')"
            component="antrag.sibling-form" />

        <x-application-step :current-step="$currentStep" :step-number="8" :total-steps="12" :title="__('cost.title')"
            component="antrag.cost-form" />

        <x-application-step :current-step="$currentStep" :step-number="9" :total-steps="12" :title="__('financing.title')"
            component="antrag.financing-form" />

        <x-application-step :current-step="$currentStep" :step-number="10" :total-steps="12" :title="__('reqAmount.title')"
            component="antrag.req-amount-form" />

        @if ($isInitialAppl)
            <x-application-step :current-step="$currentStep" :step-number="11" :total-steps="12" :title="__('enclosure.title')"
                component="antrag.enclosure-form-stipendium-erst" />
        @else
            <x-application-step :current-step="$currentStep" :step-number="11" :total-steps="12" :title="__('enclosure.title')"
                component="antrag.enclosure-form-stipendium-folge" />
        @endif

        <x-application-step :current-step="$currentStep" :step-number="12" :total-steps="12" :title="__('sending.title')"
            component="antrag.sending-form" />

        <x-application-navigation :current-step="$currentStep" :total-steps="12" :complete-app="$completeApp" :show-modal="$showModal" />
    </div>
</div>
