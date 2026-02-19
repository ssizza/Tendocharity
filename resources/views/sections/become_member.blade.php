@php
    $becomeMember = @getContent('become_member.content', null, true)->first();
@endphp

@if($becomeMember)
<section class="w-100 py-5 py-lg-5 d-flex align-items-center justify-content-center">
    <div class="container px-4 px-sm-5 px-lg-4 px-xl-5">
        <div class="custom--card border--base bg--base p-4 p-sm-5 shadow-lg text-center">
            <div class="d-flex flex-column align-items-center justify-content-center gap-4">
                <h3 class="h2 fw-bold text--white mb-0">
                    {{ __($becomeMember->data_values->heading ?? 'Become a Member') }}
                </h3>
                
                <p class="lead text--white opacity-90 mb-0 mx-auto" style="max-width: 600px;">
                    {{ __($becomeMember->data_values->description ?? 'Join TNTCEO and be part of our community') }}
                </p>
                
                <a href="{{ $becomeMember->data_values->button_link ?? '#register' }}" 
                   class="btn btn--outline-light btn--lg px-5 py-3 d-inline-flex align-items-center">
                    {{ __($becomeMember->data_values->button_text ?? 'Join Now') }}
                    <i class="las la-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>
@endif