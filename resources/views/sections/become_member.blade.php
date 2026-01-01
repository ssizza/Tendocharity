@php
    $becomeMember = @getContent('become_member.content', null, true)->first();
@endphp

@if($becomeMember)
<section class="w-full py-5 py-lg-5 d-flex align-items-center justify-content-center">
    <div class="container px-4 px-sm-5 px-lg-4 px-xl-5">
        <div class="bg-primary rounded-3 p-4 p-sm-5 border border-primary-subtle shadow-lg">
            <div class="d-flex flex-column align-items-center justify-content-center gap-4 text-center">
                <h3 class="h2 fw-bold text-white mb-0">
                    {{ __($becomeMember->data_values->heading ?? 'Become a Member') }}
                </h3>
                
                <p class="lead text-white opacity-90 mb-0 mx-auto" style="max-width: 600px;">
                    {{ __($becomeMember->data_values->description ?? 'Join TNTCEO and be part of our community') }}
                </p>
                
                <a href="{{ $becomeMember->data_values->button_link ?? '#register' }}" class="text-decoration-none">
                    <button class="btn btn-lg px-5 py-3 text-white bg-transparent border-2 border-white hover-white d-inline-flex align-items-center" style="transition: all 0.3s ease;">
                        {{ __($becomeMember->data_values->button_text ?? 'Join Now') }}
                        <i class="las la-arrow-right ms-2"></i>
                    </button>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
.hover-white:hover {
    background: white !important;
    color: var(--primary) !important;
    transform: translateY(-2px);
}
</style>
@endif