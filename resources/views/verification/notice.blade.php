<x-layout.eilinger>
    <section class="py-16">
        <div class="container mx-auto px-4">
            <x-heading.decorative class="text-center">
                {{ __('notice.verify') }}
            </x-heading.decorative>
            
            {{-- Flash Message for Redirected Users --}}
            @if (session('info'))
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">{{ session('info') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            {{-- Important Notice Box --}}
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-yellow-700 mb-1">{{ __('notice.verify_important_title') }}</h3>
                        <div class="text-sm text-yellow-700">
                            <p>{{ __('notice.verify_line1') }}</p>
                            <p class="mt-1">{{ __('notice.verify_line2') }}</p>
                            <p class="mt-2 font-semibold">{{ __('notice.verify_check_spam') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>

                @if (session('status') == 'verification-link-sent')
                    <div class="text-green-500 mb-6">
                        {{ __('notice.verify_email_sent') }}
                    </div>
                @endif

                <div class="flex items-center justify-between gap-4 mt-4 mx-auto">
                    <form method="POST" action="{{ route('verification.send', app()->getLocale()) }}">
                        @csrf
                        <button type="submit"
                            class="px-6 py-2 bg-primary text-white rounded-md hover:bg-danger-hover transition-colors">
                            {{ __('notice.verify_resend') }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout', app()->getLocale()) }}">
                        @csrf
                        <button type="submit"
                            class="px-6 py-2 bg-primary text-white rounded-md hover:bg-danger-hover transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layout.eilinger>
