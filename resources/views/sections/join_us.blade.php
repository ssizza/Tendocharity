@php
    $joinUs = @getContent('join_us.content', null, true)->first();
@endphp

@if($joinUs)
<section class="w-full py-5 py-lg-5 d-flex align-items-center justify-content-center">
    <div class="container px-4 px-sm-5 px-lg-4 px-xl-5">
        {{-- Use base color with proper HSL variable --}}
        <div class="rounded-3 p-4 p-sm-5 border shadow-lg join-us-card">
            <div class="d-flex flex-column align-items-center justify-content-center gap-4 text-center">
                <h3 class="h2 fw-bold mb-0 join-us-heading">
                    {{ __($joinUs->data_values->heading ?? 'Join Us') }}
                </h3>
                
                <p class="lead mb-0 mx-auto join-us-description">
                    {{ __($joinUs->data_values->description ?? 'Do you want to join TNTCEO as a member?') }}
                </p>
                
                <a href="{{ $joinUs->data_values->button_link ?? '#register' }}" 
                   class="text-decoration-none d-inline-block join-us-link">
                    <button class="btn btn-lg px-5 py-3 d-inline-flex align-items-center join-us-btn">
                        {{ __($joinUs->data_values->button_text ?? 'Become a Member') }}
                        <i class="las la-arrow-right ms-2"></i>
                    </button>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
.join-us-card {
    background-color: hsl(var(--base));
    border-color: hsl(var(--base-600));
}

.join-us-heading {
    color: hsl(var(--white));
}

.join-us-description {
    color: hsl(var(--white) / 0.9);
    max-width: 600px;
}

.join-us-btn {
    background-color: transparent;
    color: hsl(var(--white));
    border: 2px solid hsl(var(--white));
    transition: all 0.3s ease;
}

.join-us-btn:hover {
    background-color: hsl(var(--white)) !important;
    color: hsl(var(--base)) !important;
    transform: translateY(-2px);
}

.join-us-btn:hover i {
    color: hsl(var(--base));
}
</style>
@endif