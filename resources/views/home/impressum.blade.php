<x-layout.eilinger>

    @section('title', __('impressum.title'))
    @section('link', 'impressum')

    <section class="py-16">
        <div class="container mx-auto px-4">
            <x-heading.decorative class="text-center">
                {{ __('impressum.title') }}
            </x-heading.decorative>

            <x-heading.h3 class="mb-4">{{ __('impressum.responsible') }}</x-heading.h3>
            <address>
                <strong>Eilinger Stiftung</strong><br>
                Seeweg 45<br>
                8264 Eschenz, CH<br>
                <a href="mailto:{{ config('mail.from.address') }}"
                    target="_blank">{{ config('mail.from.address') }}</a><br>
                UID: CHE-247.433.436<br>
                © Eilinger Stiftung 2024
            </address>
            <br>
            <x-heading.h3 class="mb-4">{{ __('impressum.concept') }}</x-heading.h3>
            <p>Eilinger Stiftung</p>
            <br>
            <x-heading.h3 class="mb-4">{{ __('impressum.copyrights') }}</x-heading.h3>
            <p>{{ __('impressum.copyrights_text') }}</p>
            <br>
            <x-heading.h4>{{ __('impressum.disclaimer') }}</x-heading.h4>
            <p>{{ __('impressum.disclaimer_text1') }}</p>
            <p>{{ __('impressum.disclaimer_text2') }}</p>
            <p>{{ __('impressum.disclaimer_text3') }}</p>
            <br>
            <x-heading.h4>{{ __('impressum.liability_links') }}</x-heading.h4>
            <p>{{ __('impressum.liability_links_text') }}</p>
        </div>
        </div>
    </section>

</x-layout.eilinger>
