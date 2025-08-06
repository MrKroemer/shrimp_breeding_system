@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }} | <img src="{{ asset('img/system_logo.png') }}" alt="{{ config('app.system_owner_name') }}" width="50">
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
        <p>
            <strong>
                Copyright &copy; {{ config('app.system_copyright_year') }} 
                <a href="http://{{ config('app.system_owner_site') }}/" target="_blank">{{ config('app.system_owner_name') }}</a>
            </strong> 
            Todos os direitos reservados.
        </p>
        @endcomponent
    @endslot
@endcomponent
