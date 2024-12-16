<div class="py-6">

    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <x-application-progress :current-step="$currentStep" :total-steps="9" />

        <x-application-step :current-step="$currentStep" :step-number="1" :total-steps="9" :title="__('user.candidate')"
            component="antrag.user-jur-form" />

        <x-application-step :current-step="$currentStep" :step-number="2" :total-steps="9" :title="__('address.title')"
            component="antrag.address-form" />

        <x-application-step :current-step="$currentStep" :step-number="3" :total-steps="9" :title="__('address.titleAboard')"
            component="antrag.aboard-address-form" />

        <x-application-step :current-step="$currentStep" :step-number="4" :total-steps="9" :title="__('account.title')"
            component="antrag.account-form" />

        <x-application-step :current-step="$currentStep" :step-number="5" :total-steps="9" :title="__('cost.cost')"
            component="antrag.cost-form-darlehen" />

        <x-application-step :current-step="$currentStep" :step-number="6" :total-steps="9" :title="__('financing.title')"
            component="antrag.financing-organisation-form" />

        <x-application-step :current-step="$currentStep" :step-number="7" :total-steps="9" :title="__('reqAmount.title')"
            component="antrag.req-amount-form" />

        <x-application-step :current-step="$currentStep" :step-number="8" :total-steps="9" :title="__('enclosure.title')"
            component="antrag.enclosure-organisation-form" />

        <x-application-step :current-step="$currentStep" :step-number="9" :total-steps="9" :title="__('sending.title')"
            component="antrag.sending-darlehen-form" />

        <x-application-navigation :current-step="$currentStep" :total-steps="9" :complete-app="$completeApp" :show-modal="$showModal" />
    </div>
</div>
