<x-layout.eilinger>
    <section class="py-16">
        <div class="container mx-auto px-4">
            <x-heading.decorative class="text-center">
                {{ __('notice.verify') }}
            </x-heading.decorative>
            <p>{{ __('notice.verify_line1') }}</p>
            <p>{{ __('notice.verify_line2') }}</p>

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
