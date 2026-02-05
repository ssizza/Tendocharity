@extends('layouts.app')

@section('app')

    @stack('fbComment')
    
    @include('partials.header')

    {{-- Conditionally show breadcrumb based on AppServiceProvider variable --}}
    @unless($hideBreadcrumb ?? false)
        @include('partials.breadcrumb')
    @endunless

    @yield('content')

    @include('partials.footer')

    @include('partials.subscribe')
    
    <x-cookie-policy />
@endsection