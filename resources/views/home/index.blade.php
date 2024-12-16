<x-layout.eilinger>

    @section('title', 'Homepage')

    <main id="main">

        <!-- ======= About Us Section ======= -->
        <section id="about" class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <x-heading.decorative class="text-center">
                    {{ __('home.about') }}
                </x-heading.decorative>

                <div class="grid md:grid-cols-2 gap-8 mt-8">
                    {{-- Philosophy Column --}}
                    <div class="space-y-4">
                        <h3 class="font-ubuntu text-[28px] font-bold leading-[1.2] text-black">
                            {{ __('home.philosophy') }}
                        </h3>
                        <p class="text-black leading-relaxed">
                            {{ __('home.philosophy_text1') }}
                        </p>
                        <p class="text-black leading-relaxed">
                            {{ __('home.philosophy_text2') }}
                        </p>
                    </div>

                    {{-- Purpose Column --}}
                    <div class="space-y-4">
                        <h3 class="font-ubuntu text-[28px] font-bold leading-[1.2] text-black">
                            {{ __('home.purpose') }}
                        </h3>
                        <p class="text-black leading-relaxed mb-4">
                            {{ __('home.purpose_text') }}:
                        </p>

                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <i class="bi bi-check2-all text-accent text-xl mr-2"></i>
                                <span class="text-black">{{ __('home.purpose1') }}</span>
                            </li>
                            <li class="flex items-start">
                                <i class="bi bi-check2-all text-accent text-xl mr-2"></i>
                                <span class="text-black">{{ __('home.purpose2') }}</span>
                            </li>
                            <li class="flex items-start">
                                <i class="bi bi-check2-all text-accent text-xl mr-2"></i>
                                <span class="text-black">{{ __('home.purpose3') }}</span>
                            </li>
                            <li class="flex items-start">
                                <i class="bi bi-check2-all text-accent text-xl mr-2"></i>
                                <span class="text-black">{{ __('home.purpose4') }}</span>
                            </li>
                            <li class="flex items-start">
                                <i class="bi bi-check2-all text-accent text-xl mr-2"></i>
                                <span class="text-black">{{ __('home.purpose5') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- ======= Förderbereiche ======= -->
        <section id="our-values" class="bg-bodyBg py-16">
            <div class="container mx-auto px-4">
                <x-heading.decorative class="text-center">
                    {{ __('home.funding') }}
                </x-heading.decorative>

                <!-- First row -->
                <div class="grid md:grid-cols-2 gap-8 mt-8">
                    <!-- Education Card -->
                    <div class="relative group overflow-hidden rounded-lg shadow-lg h-[500px]">
                        <div class="absolute inset-0 bg-cover bg-center"
                            style="background-image: url('{{ asset('images/Bildung_4.jpg') }}')">
                        </div>
                        <div class="absolute inset-0 px-3 sm:px-5 pb-5 pt-[160px]">
                            <div
                                class="w-full h-full bg-white/90 group-hover:bg-[#4f73a8] transition-colors duration-300 rounded-lg">
                                <h3
                                    class="font-ubuntu text-xl sm:text-[28px] font-bold text-center mb-4 text-primary group-hover:text-white pt-5">
                                    {{ __('home.education') }}
                                </h3>
                                <div class="px-4 sm:px-8">
                                    <p
                                        class="text-xs sm:text-base text-[#5e5e5e] group-hover:text-white mb-4 transition-colors duration-300">
                                        {{ __('home.education_text1') }}
                                    </p>
                                    <p class="text-xs sm:text-base text-black group-hover:text-black">
                                        {{ __('home.education_text2') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- People in Need Card -->
                    <div class="relative group overflow-hidden rounded-lg shadow-lg h-[500px]">
                        <div class="absolute inset-0 bg-cover bg-center"
                            style="background-image: url('{{ asset('images/Menschen_5.jpg') }}')">
                        </div>
                        <div class="absolute inset-0 px-3 sm:px-5 pb-5 pt-[160px]">
                            <div
                                class="w-full h-full bg-white/90 group-hover:bg-[#4f73a8] transition-colors duration-300 rounded-lg">
                                <h3
                                    class="font-ubuntu text-xl sm:text-[28px] font-bold text-center mb-4 text-primary group-hover:text-white pt-5">
                                    {{ __('home.need') }}
                                </h3>
                                <div class="px-4 sm:px-8">
                                    <p
                                        class="text-xs sm:text-base text-[#5e5e5e] group-hover:text-white mb-4 transition-colors duration-300">
                                        {{ __('home.need_text1') }}
                                    </p>
                                    <p class="text-xs sm:text-base text-black group-hover:text-black">
                                        {{ __('home.need_text2') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Second row -->
                <div class="grid md:grid-cols-2 gap-8 mt-8">
                    <!-- Animal Welfare Card -->
                    <div class="relative group overflow-hidden rounded-lg shadow-lg h-[500px]">
                        <div class="absolute inset-0 bg-cover bg-center"
                            style="background-image: url('{{ asset('images/Tier_1.jpeg') }}')">
                        </div>
                        <div class="absolute inset-0 px-3 sm:px-5 pb-5 pt-[160px]">
                            <div
                                class="w-full h-full bg-white/90 group-hover:bg-[#4f73a8] transition-colors duration-300 rounded-lg">
                                <h3
                                    class="font-ubuntu text-xl sm:text-[28px] font-bold text-center mb-4 text-primary group-hover:text-white pt-5">
                                    {{ __('home.welfare') }}
                                </h3>
                                <div class="px-4 sm:px-8">
                                    <p
                                        class="text-xs sm:text-base text-[#5e5e5e] group-hover:text-white mb-4 transition-colors duration-300">
                                        {{ __('home.welfare_text1') }}
                                    </p>
                                    <p class="text-xs sm:text-base text-black group-hover:text-black">
                                        {{ __('home.welfare_text2') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Environment Card -->
                    <div class="relative group overflow-hidden rounded-lg shadow-lg h-[500px]">
                        <div class="absolute inset-0 bg-cover bg-center"
                            style="background-image: url('{{ asset('images/Umwelt.jpg') }}')">
                        </div>
                        <div class="absolute inset-0 px-3 sm:px-5 pb-5 pt-[160px]">
                            <div
                                class="w-full h-full bg-white/90 group-hover:bg-[#4f73a8] transition-colors duration-300 rounded-lg">
                                <h3
                                    class="font-ubuntu text-xl sm:text-[28px] font-bold text-center mb-4 text-primary group-hover:text-white pt-5">
                                    {{ __('home.environment') }}
                                </h3>
                                <div class="px-4 sm:px-8">
                                    <p
                                        class="text-xs sm:text-base text-[#5e5e5e] group-hover:text-white mb-4 transition-colors duration-300">
                                        {{ __('home.environment_text1') }}
                                    </p>
                                    <p class="text-xs sm:text-base text-black group-hover:text-black">
                                        {{ __('home.environment_text2') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Third row - centered -->
                <div class="mt-8 max-w-2xl mx-auto">
                    <!-- Human Rights Card -->
                    <div class="relative group overflow-hidden rounded-lg shadow-lg h-[500px]">
                        <div class="absolute inset-0 bg-cover bg-center"
                            style="background-image: url('{{ asset('images/Menschen_4.jpg') }}')">
                        </div>
                        <div class="absolute inset-0 px-3 sm:px-5 pb-5 pt-[160px]">
                            <div
                                class="w-full h-full bg-white/90 group-hover:bg-[#4f73a8] transition-colors duration-300 rounded-lg">
                                <h3
                                    class="font-ubuntu text-xl sm:text-[28px] font-bold text-center mb-4 text-primary group-hover:text-white pt-5">
                                    {{ __('home.rights') }}
                                </h3>
                                <div class="px-4 sm:px-8">
                                    <p
                                        class="text-xs sm:text-base text-[#5e5e5e] group-hover:text-white mb-4 transition-colors duration-300">
                                        {{ __('home.rights_text1') }}
                                    </p>
                                    <p class="text-xs sm:text-base text-black group-hover:text-black">
                                        {{ __('home.rights_text2') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ======= Projekte Section ======= -->
        <section id="projekte" class="bg-white py-16">
            <div class="container mx-auto px-4">
                <x-heading.decorative class="text-center">
                    {{ __('home.projects') }}
                </x-heading.decorative>
                <div class="space-y-6 mb-12">
                    <p>{{ __('home.projects_text1') }}</p>
                    <p>{{ __('home.projects_text2') }}</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
                    <!-- Project 1 -->
                    <div
                        class="bg-white p-6 rounded-lg shadow-lg group hover:bg-[#4f73a8] transition-colors duration-300 relative min-h-[300px] overflow-hidden">
                        <div class="absolute -left-8 top-1/2 -translate-y-1/2">
                            <div class="text-[#ff689b] text-[100px]">
                                <i class="bi bi-briefcase"></i>
                            </div>
                        </div>
                        <div class="pl-16">
                            <h3 class="font-ubuntu text-xl font-bold mb-4 text-primary group-hover:text-white">
                                {{ __('home.project1_title') }}
                            </h3>
                            <p class="group-hover:text-white">
                                {{ __('home.project1_text') }}
                            </p>
                        </div>
                    </div>

                    <!-- Project 2 -->
                    <div
                        class="bg-white p-6 rounded-lg shadow-lg group hover:bg-[#4f73a8] transition-colors duration-300 relative min-h-[300px] overflow-hidden">
                        <div class="absolute -left-8 top-1/2 -translate-y-1/2">
                            <div class="text-[#e9bf06] text-[100px]">
                                <i class="bi bi-card-checklist"></i>
                            </div>
                        </div>
                        <div class="pl-16">
                            <h3 class="font-ubuntu text-xl font-bold mb-4 text-primary group-hover:text-white">
                                {{ __('home.project2_title') }}
                            </h3>
                            <p class="group-hover:text-white">
                                {{ __('home.project2_text') }}
                            </p>
                        </div>
                    </div>

                    <!-- Project 3 -->
                    <div
                        class="bg-white p-6 rounded-lg shadow-lg group hover:bg-[#4f73a8] transition-colors duration-300 relative min-h-[300px] overflow-hidden">
                        <div class="absolute -left-8 top-1/2 -translate-y-1/2">
                            <div class="text-[#3fcdc7] text-[100px]">
                                <i class="bi bi-bar-chart"></i>
                            </div>
                        </div>
                        <div class="pl-16">
                            <h3 class="font-ubuntu text-xl font-bold mb-4 text-primary group-hover:text-white">
                                {{ __('home.project3_title') }}
                            </h3>
                            <p class="group-hover:text-white">
                                {{ __('home.project3_text') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
                    <!-- Project 4 -->
                    <div
                        class="bg-white p-6 rounded-lg shadow-lg group hover:bg-[#4f73a8] transition-colors duration-300 relative min-h-[300px] overflow-hidden">
                        <div class="absolute -left-8 top-1/2 -translate-y-1/2">
                            <div class="text-[#41cf2e] text-[100px]">
                                <i class="bi bi-binoculars"></i>
                            </div>
                        </div>
                        <div class="pl-16">
                            <h3 class="font-ubuntu text-xl font-bold mb-4 text-primary group-hover:text-white">
                                {{ __('home.project4_title') }}
                            </h3>
                            <p class="group-hover:text-white">
                                {{ __('home.project4_text') }}
                            </p>
                        </div>
                    </div>

                    <!-- Project 5 -->
                    <div
                        class="bg-white p-6 rounded-lg shadow-lg group hover:bg-[#4f73a8] transition-colors duration-300 relative min-h-[300px] overflow-hidden">
                        <div class="absolute -left-8 top-1/2 -translate-y-1/2">
                            <div class="text-[#d6ff22] text-[100px]">
                                <i class="bi bi-brightness-high"></i>
                            </div>
                        </div>
                        <div class="pl-16">
                            <h3 class="font-ubuntu text-xl font-bold mb-4 text-primary group-hover:text-white">
                                {{ __('home.project5_title') }}
                            </h3>
                            <p class="group-hover:text-white">
                                {{ __('home.project5_text') }}
                            </p>
                        </div>
                    </div>

                    <!-- Project 6 -->
                    <div
                        class="bg-white p-6 rounded-lg shadow-lg group hover:bg-[#4f73a8] transition-colors duration-300 relative min-h-[300px] overflow-hidden">
                        <div class="absolute -left-8 top-1/2 -translate-y-1/2">
                            <div class="text-[#4680ff] text-[100px]">
                                <i class="bi bi-calendar4-week"></i>
                            </div>
                        </div>
                        <div class="pl-16">
                            <h3 class="font-ubuntu text-xl font-bold mb-4 text-primary group-hover:text-white">
                                {{ __('home.project6_title') }}
                            </h3>
                            <p class="group-hover:text-white">
                                {{ __('home.project6_text') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- ======= Gesuche Section ======= -->
        <section id="gesuche" class="bg-[#f3f5fa] py-16">
            <div class="container mx-auto px-4">
                <x-heading.decorative class="text-center">
                    {{ __('home.requests') }}
                </x-heading.decorative>

                <div class="space-y-6 mb-12">
                    <p>{{ __('home.request_text') }}</p>
                    <ol class="list-decimal pl-6 space-y-2">
                        <li>{{ __('home.request_text1') }}</li>
                        <li>{{ __('home.request_text2') }}</li>
                        <li>{{ __('home.request_text3') }}</li>
                        <li>{{ __('home.request_text4') }}</li>
                        <li>{{ __('home.request_text5') }}</li>
                    </ol>
                    <p>{{ __('home.request_disclaimer') }}</p>
                    <p>{{ __('home.request_meeting') }}</p>
                </div>

                <!-- Request Cards Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Bildung Card -->
                    <div class="bg-white p-8 rounded-lg shadow-lg flex flex-col h-full">
                        <h3 class="font-ubuntu text-2xl font-bold mb-6 text-primary">{{ __('home.education') }}</h3>
                        <ul class="space-y-4 flex-grow">
                            <li class="flex items-center">
                                <i class="bi bi-check2-all text-accent mr-2"></i>
                                {{ __('home.app_stip') }}
                            </li>
                            <li class="flex items-center">
                                <i class="bi bi-check2-all text-accent mr-2"></i>
                                {{ __('home.app_privat') }}
                            </li>
                            <li class="flex items-center">
                                <i class="bi bi-check2-all text-accent mr-2"></i>
                                {{ __('home.app_org') }}
                            </li>
                        </ul>
                        <div class="mt-8">
                            <a href="{{ route('user_dashboard', app()->getLocale()) }}"
                                class="inline-block w-full py-3 text-center border border-accent text-accent hover:bg-accent hover:text-white transition-colors duration-300 rounded-lg">
                                {{ __('home.to_portal') }}
                            </a>
                        </div>
                    </div>

                    <!-- Tierschutz Card -->
                    <div class="bg-white p-8 rounded-lg shadow-lg flex flex-col h-full">
                        <h3 class="font-ubuntu text-2xl font-bold mb-6 text-primary">{{ __('home.welfare') }}</h3>
                        <ul class="space-y-4 flex-grow">
                            <li class="flex items-center">
                                <i class="bi bi-check2-all text-accent mr-2"></i>
                                {{ __('home.app_privat') }}
                            </li>
                            <li class="flex items-center">
                                <i class="bi bi-check2-all text-accent mr-2"></i>
                                {{ __('home.app_org') }}
                            </li>
                        </ul>
                        <div class="mt-8">
                            <a href="{{ route('user_dashboard', app()->getLocale()) }}"
                                class="inline-block w-full py-3 text-center border border-accent text-accent hover:bg-accent hover:text-white transition-colors duration-300 rounded-lg">
                                {{ __('home.to_portal') }}
                            </a>
                        </div>
                    </div>

                    <!-- Menschenrechte Card -->
                    <div class="bg-white p-8 rounded-lg shadow-lg flex flex-col h-full">
                        <h3 class="font-ubuntu text-2xl font-bold mb-6 text-primary">{{ __('home.rights') }}</h3>
                        <ul class="space-y-4 flex-grow">
                            <li class="flex items-center">
                                <i class="bi bi-check2-all text-accent mr-2"></i>
                                {{ __('home.app_privat') }}
                            </li>
                            <li class="flex items-center">
                                <i class="bi bi-check2-all text-accent mr-2"></i>
                                {{ __('home.app_org') }}
                            </li>
                        </ul>
                        <div class="mt-8">
                            <a href="{{ route('user_dashboard', app()->getLocale()) }}"
                                class="inline-block w-full py-3 text-center border border-accent text-accent hover:bg-accent hover:text-white transition-colors duration-300 rounded-lg">
                                {{ __('home.to_portal') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Second Row -->
                <div class="grid md:grid-cols-2 gap-8 mt-8 lg:max-w-[66%] lg:mx-auto">
                    <!-- Umweltschutz Card -->
                    <div class="bg-white p-8 rounded-lg shadow-lg flex flex-col h-full">
                        <h3 class="font-ubuntu text-2xl font-bold mb-6 text-primary">{{ __('home.environment') }}</h3>
                        <ul class="space-y-4 flex-grow">
                            <li class="flex items-center">
                                <i class="bi bi-check2-all text-accent mr-2"></i>
                                {{ __('home.app_privat') }}
                            </li>
                            <li class="flex items-center">
                                <i class="bi bi-check2-all text-accent mr-2"></i>
                                {{ __('home.app_org') }}
                            </li>
                        </ul>
                        <div class="mt-8">
                            <a href="{{ route('user_dashboard', app()->getLocale()) }}"
                                class="inline-block w-full py-3 text-center border border-accent text-accent hover:bg-accent hover:text-white transition-colors duration-300 rounded-lg">
                                {{ __('home.to_portal') }}
                            </a>
                        </div>
                    </div>

                    <!-- Menschen in Not Card -->
                    <div class="bg-white p-8 rounded-lg shadow-lg flex flex-col h-full">
                        <h3 class="font-ubuntu text-2xl font-bold mb-6 text-primary">{{ __('home.need') }}</h3>
                        <ul class="space-y-4">
                            <li class="flex items-center">
                                <i class="bi bi-check2-all text-accent mr-2"></i>
                                {{ __('home.app_privat') }}
                            </li>
                            <li class="flex items-center">
                                <i class="bi bi-check2-all text-accent mr-2"></i>
                                {{ __('home.app_org') }}
                            </li>
                        </ul>
                        <div class="mt-8">
                            <a href="{{ route('user_dashboard', app()->getLocale()) }}"
                                class="inline-block w-full py-3 text-center border border-accent text-accent hover:bg-accent hover:text-white transition-colors duration-300 rounded-lg">
                                {{ __('home.to_portal') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </main><!-- End #main -->

</x-layout.eilinger>
