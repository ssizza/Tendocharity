@extends('layouts.frontend')

@section('content')
    @include('sections.blog')
    
    @if(@$sections->secs != null)
        @foreach(json_decode($sections->secs) as $sec)
            @include('sections.'.$sec)
        @endforeach
    @endif
@endsection
  