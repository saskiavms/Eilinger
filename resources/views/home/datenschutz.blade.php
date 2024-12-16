<x-layout.eilinger>

    @section('title', __('dataprotection.data-protection'))
    @section('link', 'datenschutz')

    <section class="py-16">
        <div class="container mx-auto px-4">
            <x-heading.decorative class="text-center">
                {{ __('dataprotection.data-protection') }}
            </x-heading.decorative>

            <p>{{ __('dataprotection.data-protection-text') }}</p>
            <br>
            <x-heading.h3>{{ __('dataprotection.responsible') }}</x-heading.h3>
            <address>
                <strong>Eilinger Stiftung</strong><br>
                Seeweg 45<br>
                8264 Eschenz, CH<br>
                <a href="mailto:{{ config('mail.from.address') }}" target="_blank">{{ config('mail.from.address') }}</a>
            </address>
            <br>
            <x-heading.h3>{{ __('dataprotection.categories') }}</x-heading.h3>
            <div class="table-responsive">
                <table class="w-full border-collapse border border-gray-300 mb-4">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="border border-gray-300 px-4 py-2">{{ __('dataprotection.category') }}</th>
                            <th class="border border-gray-300 px-4 py-2">{{ __('dataprotection.purpose') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="even:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ __('dataprotection.category_contact') }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                {{ __('dataprotection.purpose_contact') }}
                                <ul class="list-disc ml-4 mt-2">
                                    <li>{{ __('dataprotection.purpose_contact1') }}</li>
                                    <li>{{ __('dataprotection.purpose_contact2') }}</li>
                                    <li>{{ __('dataprotection.purpose_contact3') }}</li>
                                </ul>
                                {{ __('dataprotection.purpose_contact4') }}
                            </td>
                        </tr>
                        <tr class="even:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ __('dataprotection.category_ip') }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                {{ __('dataprotection.purpose_ip') }}
                                <ul class="list-disc ml-4 mt-2">
                                    <li>{{ __('dataprotection.purpose_ip1') }}</li>
                                </ul>
                            </td>
                        </tr>
                        <tr class="even:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ __('dataprotection.category_education') }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                {{ __('dataprotection.purpose_education') }}
                                <ul class="list-disc ml-4 mt-2">
                                    <li>{{ __('dataprotection.purpose_education1') }}</li>
                                </ul>
                            </td>
                        </tr>
                        <tr class="even:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ __('dataprotection.category_family') }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                {{ __('dataprotection.purpose_family') }}
                                <ul class="list-disc ml-4 mt-2">
                                    <li>{{ __('dataprotection.purpose_family1') }}</li>
                                    <li>{{ __('dataprotection.purpose_family2') }}</li>
                                    <li>{{ __('dataprotection.purpose_family3') }}</li>
                                </ul>
                            </td>
                        </tr>
                        <tr class="even:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ __('dataprotection.category_finance') }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                {{ __('dataprotection.purpose_finance') }}
                                <ul class="list-disc ml-4 mt-2">
                                    <li>{{ __('dataprotection.purpose_finance1') }}</li>
                                    <li>{{ __('dataprotection.purpose_finance2') }}</li>
                                </ul>
                            </td>
                        </tr>
                        <tr class="even:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ __('dataprotection.category_voluntary') }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                {{ __('dataprotection.purpose_voluntary') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <x-heading.h3>{{ __('dataprotection.data_transfer') }} </x-heading.h3>
            <p>{{ __('dataprotection.data_transfer_text') }} </p>
            <br>
            <x-heading.h3>{{ __('dataprotection.cookies') }} </x-heading.h3>
            <p>{{ __('dataprotection.cookies_text') }} </p>
            <div class="table-responsive">
                <table class="w-full border-collapse border border-gray-300 mb-4">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="border border-gray-300 px-4 py-2">{{ __('dataprotection.cookie_name') }}</th>
                            <th class="border border-gray-300 px-4 py-2">{{ __('dataprotection.cookie_function') }}
                            </th>
                            <th class="border border-gray-300 px-4 py-2">{{ __('dataprotection.cookie_meaning') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="even:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ __('XSRF-Token') }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                {{ __('dataprotection.cookie_XSRF_function') }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ __('dataprotection.cookie_XSRF_meaning') }}
                            </td>
                        </tr>
                        <tr class="even:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ __('eilinger_stiftung_session') }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                {{ __('dataprotection.cookie_session_function') }}
                                <ul class="list-disc ml-4 mt-2">
                                    <li>{{ __('dataprotection.cookie_session_function1') }}</li>
                                    <li>{{ __('dataprotection.cookie_session_function2') }}</li>
                                    <li>{{ __('dataprotection.cookie_session_function3') }}</li>
                                    <li>{{ __('dataprotection.cookie_session_function4') }}</li>
                                    <li>{{ __('dataprotection.cookie_session_function5') }}</li>
                                </ul>
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                <p>{{ __('dataprotection.cookie_session_meaning1') }}</p>
                                <p class="mt-2">{{ __('dataprotection.cookie_session_meaning2') }}</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <x-heading.h3>{{ __('dataprotection.rights') }} </x-heading.h3>
            <p>{{ __('dataprotection.rights_text') }} </p>
            <br>
            <p>{{ __('dataprotection.rights_text1') }} </p>
            <ul class="list-disc ml-4 mt-2">
                <li>{{ __('dataprotection.rights_text2') }}</li>
                <li>{{ __('dataprotection.rights_text3') }}</li>
                <li>{{ __('dataprotection.rights_text4') }}</li>
                <li>{{ __('dataprotection.rights_text5') }}</li>
                <li>{{ __('dataprotection.rights_text6') }}</li>
                <li>{{ __('dataprotection.rights_text7') }}</li>
            </ul>
            <br>
            <x-heading.h3>{{ __('dataprotection.change') }} </x-heading.h3>
            <p>{{ __('dataprotection.change_text') }} </p>
            <br>

            © 2024 Eilinger-Stiftung
        </div>
        </div>
    </section>


</x-layout.eilinger>
