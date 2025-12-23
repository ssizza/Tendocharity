@extends('layouts.app')

@section('app')

    @stack('fbComment')
    
    @include('partials.header')

    @include('partials.breadcrumb')

    @yield('content')

    @include('partials.footer')

    @include('partials.subscribe')
    
    <x-cookie-policy />
@endsection 
 