<footer class="bg-footerBg text-white">
    <!-- Footer Top -->
    <div class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12">
                <!-- Contact Info -->
                <div>
                    <h3 class="font-ubuntu text-2xl font-bold mb-6 text-primary-700">EILINGER STIFTUNG</h3>
                    <div class="space-y-2 text-footerText">
                        <p>Seeweg 45</p>
                        <p>8264 Eschenz</p>
                        <p>Schweiz</p>
                        <div class="mt-4">
                            <p>
                                <span class="font-bold">Email:</span>
                                <a href="mailto:{{ config('mail.from.address') }}"
                                    class="hover:text-accent transition-colors text-primary-700">
                                    {{ config('mail.from.address') }}
                                </a>
                            </p>
                        </div>
                        <div class="mt-4">
                            <p>{{ __('home.desired_contact') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Useful Links -->
                <div>
                    <h4 class="font-ubuntu text-xl font-bold mb-6 text-primary-700">{{ __('home.links') }}</h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('index', app()->getLocale()) }}"
                                class="text-footerText hover:text-accent transition-colors flex items-center">
                                <i class="bi bi-chevron-right text-sm mr-2"></i>
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('impressum', app()->getLocale()) }}"
                                class="text-footerText hover:text-accent transition-colors flex items-center">
                                <i class="bi bi-chevron-right text-sm mr-2"></i>
                                Impressum
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('datenschutz', app()->getLocale()) }}"
                                class="text-footerText hover:text-accent transition-colors flex items-center">
                                <i class="bi bi-chevron-right text-sm mr-2"></i>
                                {{ __('dataprotection.data-protection') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="border-t border-gray-700 py-4 bg-primary-700">
        <div class="container mx-auto px-4">
            <div class="text-center text-footerText">
                &copy; Copyright <span class="font-bold text-white">Eilinger Stiftung</span>. All Rights Reserved
            </div>
        </div>
    </div>
</footer>
