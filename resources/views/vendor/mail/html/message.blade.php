@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
        {{ !empty($header_logo) ? $header_logo : '' }} {{ strtoupper(str_replace('-', ' ', config('app.name'))) }}
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            {{ !empty($footer) ? $footer : '' }}
            © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
        @endcomponent
    @endslot
@endcomponent
