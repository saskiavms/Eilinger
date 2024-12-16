@php
    use App\Enums\Education;
    use App\Enums\InitialEducation;
@endphp
<form wire:submit="saveEducation">
    @csrf
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-primary mb-2">{{ __('education.education') }}</h3>
        <p class="text-sm text-gray-600">{{ __('education.subtitle') }}</p>
    </div>

    <x-notification />

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Initial Education -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('education.initial_education') }}? *
            </label>
            <select wire:model.blur="initial_education"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                <option value="">{{ __('attributes.please_select') }}</option>
                @foreach (InitialEducation::cases() as $initial)
                    <option value="{{ $initial->value }}">
                        {{ __('education.initial_education_name.' . $initial->name) }}
                    </option>
                @endforeach
            </select>
            @error('initial_education')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Education Type -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('education.education') }} *
            </label>
            <select wire:model.blur="education_type"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                <option value="">{{ __('attributes.please_select') }}</option>
                @foreach (Education::cases() as $education)
                    <option value="{{ $education->value }}">
                        {{ __('education.education_name.' . $education->name) }}
                    </option>
                @endforeach
            </select>
            @error('education_type')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Name -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('education.name') }} *
            </label>
            <input wire:model.blur="name" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Final -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('education.final') }} *
            </label>
            <input wire:model.blur="final" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
            @error('final')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Grade -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('education.grade') }} *
            </label>
            <select wire:model.blur="grade"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                <option value="">{{ __('attributes.please_select') }}</option>
                @foreach (App\Enums\Grade::cases() as $grade)
                    <option value="{{ $grade->value }}">
                        {{ __('education.grade_name.' . $grade->name) }}
                    </option>
                @endforeach
            </select>
            @error('grade')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- ECTS Points -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('education.ects_points') }} *
            </label>
            <input wire:model.blur="ects_points" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
            @error('ects_points')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Time -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('education.time') }} *
            </label>
            <select wire:model.blur="time"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                <option value="">{{ __('attributes.please_select') }}</option>
                @foreach (App\Enums\Time::cases() as $time)
                    <option value="{{ $time->value }}">
                        {{ __('application.time.' . $time->name) }}
                    </option>
                @endforeach
            </select>
            @error('time')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Begin Education -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('education.begin_edu') }} *
            </label>
            <input wire:model.blur="begin_edu" type="date"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
            @error('begin_edu')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Duration -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('education.duration_edu') }} *
            </label>
            <input wire:model.blur="duration_edu" type="number"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
            @error('duration_edu')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Start Semester -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('education.start_semester') }} *
            </label>
            <input wire:model.blur="start_semester" type="number"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
            @error('start_semester')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Submit Button -->
    <div class="mt-8 flex justify-center">
        <button type="submit"
            class="px-6 py-2 bg-success text-white rounded-md hover:bg-successHover transition-colors">
            {{ __('attributes.save') }}
        </button>
    </div>
</form>
