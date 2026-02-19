@php
    $supportUs = @getContent('support_us.content', null, true)->first();
@endphp

@if($supportUs)
<section class="w-full py-5 py-lg-5 d-flex align-items-center justify-content-center">
    <div class="container px-4 px-sm-5 px-lg-4 px-xl-5">
        <div class="rounded-3 p-4 p-sm-5 border shadow-lg" 
             style="background-color: hsl(var(--base)); border-color: hsl(var(--base-400)) !important;">
            <div class="d-flex flex-column align-items-center justify-content-center gap-4 text-center">
                <h3 class="h2 fw-bold mb-0" style="color: hsl(var(--white));">
                    {{ __($supportUs->data_values->heading ?? 'Support Us') }}
                </h3>
                
                <p class="lead mb-0 mx-auto" style="color: hsl(var(--white) / 0.9); max-width: 600px;">
                    {{ __($supportUs->data_values->description ?? 'Do you wish to support TNTCEO?') }}
                </p>
                
                <a href="{{ $supportUs->data_values->button_link ?? '#donate' }}" 
                   class="text-decoration-none d-inline-block">
                    <button class="btn btn-lg px-5 py-3 border-2 d-inline-flex align-items-center" 
                            style="color: hsl(var(--white)); 
                                   background-color: transparent; 
                                   border-color: hsl(var(--white)) !important;
                                   transition: all 0.3s ease;">
                        {{ __($supportUs->data_values->button_text ?? 'Donate Now') }}
                        <i class="las la-arrow-right ms-2"></i>
                    </button>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
.hover-white:hover {
    background: hsl(var(--white)) !important;
    color: hsl(var(--base)) !important;
    transform: translateY(-2px);
}
</style>
@endif