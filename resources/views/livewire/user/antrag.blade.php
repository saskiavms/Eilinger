<section class="home-section">
    <h2 class="text-3xl font-ubuntu text-primary font-semibold mb-6">
        {{ __('antrag.title') }}
    </h2>
    <p>{{ __('antrag.text1') }}: <a target='_blank'
            href="{{ route('index', app()->getLocale()) }}#our-values">{{ __('home.funding') }}</a>.</p>
    <p>{{ __('antrag.text2') }} </p>

    <p class="text-danger font-bold">{{ __('antrag.textSave') }}</p>


    <div class="home-content">

        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        @if ($application->form->value === \App\Enums\Form::Stipendium->value)
            @livewire('user.stipendium')
        @elseif ($application->user->type->value === \App\Enums\Types::nat->value)
            @livewire('user.darlehen-privat')
        @else
            @livewire('user.darlehen-verein')
        @endif
    </div>
</section>
