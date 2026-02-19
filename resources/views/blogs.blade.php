@extends('layouts.frontend')

@section('content')

    {{-- Blog Content Section --}}
    @include('sections.blog')
    
    {{-- Additional Sections --}}
    @if(@$sections->secs != null)
        @foreach(json_decode($sections->secs) as $sec)
            @include('sections.'.$sec)
        @endforeach
    @endif
@endsection

@push('style')
<style>
    .page-header {
        position: relative;
        background: linear-gradient(135deg, hsl(var(--base)) 0%, hsl(var(--base-600)) 100%) !important;
        margin-bottom: 30px;
    }
    
    
    @media (max-width: 767px) {
        .page-title {
            font-size: 2.2rem;
        }
        
        .page-subtitle {
            font-size: 1rem;
        }
    }
</style>
@endpush