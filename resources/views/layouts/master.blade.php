@extends('layouts.app')

@section('app')    
    @include('partials.auth_header')
    @include('partials.breadcrumb')

    @yield('content')

    @include('partials.footer')
@endsection 

@push('style')
    <link rel="stylesheet" href="{{asset('assets/global/css/select2.min.css')}}">   
@endpush

@push('script-lib')
    <script src="{{asset('assets/global/js/select2.min.js')}}"></script>
@endpush

@push('script')
    <script>
        "use strict";

        $('form').on('submit', function () {
            if ($(this).hasClass('form')) { 
                return false;
            }
            if ($(this).hasClass('exclude')) { 
                return false;
            } 
            if ($(this).valid()) {
                $(':submit', this).attr('disabled', 'disabled');
            }
        });

        $('.select2').each(function(index,element){
            $(element).select2({
                minimumResultsForSearch: "-1"
            });
        });

        $('.select2-searchable').each(function(index,element){
            $(element).select2({
                minimumResultsForSearch: "1"
            });
        });

        $('.select2-basic').each(function(index,element){
            $(element).select2({
                dropdownParent: $(element).closest('.select2-parent')
            });
        });

        var inputElements = $('[type=text],[type=password],select,textarea');
        $.each(inputElements, function (index, element) {
            element = $(element);
            element.closest('.form-group').find('label').attr('for',element.attr('name'));
            element.attr('id',element.attr('name'))
        });

        $.each($('input:not([type=checkbox]):not([type=hidden]), select, textarea'), function (i, element) {

            if (element.hasAttribute('required')) {
                $(element).closest('.form-group').find('label').addClass('required');
            }

        });

        Array.from(document.querySelectorAll('table')).forEach(table => {
            let heading = table.querySelectorAll('thead tr th');
            Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
                Array.from(row.querySelectorAll('td')).forEach((colum, i) => {
                    colum.setAttribute('data-label', heading[i].innerText)
                });
            });
        });

        let disableSubmission = false;
        $('.disableSubmission').on('submit',function(e){
            if (disableSubmission) {
            e.preventDefault()
            }else{
            disableSubmission = true;
            }
        });

    </script>
@endpush