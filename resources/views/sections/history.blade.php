@php
    $history = @getContent('history.content', null, true)->first();
@endphp

@if($history)
<section class="history-section section-full py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0 @if(isset($history->data_values->image_alignment) && $history->data_values->image_alignment == 'right') order-lg-2 @endif">
                <div class="history-content">
                    <h2 class="section-heading mb-4">
                        {{ __($history->data_values->heading ?? 'Our History') }}
                    </h2>
                    
                    <div class="history-description">
                        <p class="mb-4">
                            {{ __($history->data_values->description ?? "The Hannah Karema Foundation was established by Hannah Karema, leveraging her influence gained as Miss Uganda 2023/24. Motivated by her personal experiences, she aimed to effect significant change in the lives of Ugandan women. From its grassroots beginnings, the foundation has evolved into a prominent entity dedicated to the empowerment of women in Uganda.") }}
                        </p>
                    </div>
                </div>
            </div>
            
            @if(isset($history->data_values->has_image) && $history->data_values->has_image == '1' && isset($history->data_values->image))
            <div class="col-lg-6 @if(isset($history->data_values->image_alignment) && $history->data_values->image_alignment == 'right') order-lg-1 @endif">
                <div class="history-image-wrapper">
                    <img src="{{ getImage('assets/images/frontend/history/' . $history->data_values->image, '800x600') }}" 
                         alt="{{ __($history->data_values->heading ?? 'Our History') }}" 
                         class="img-fluid rounded shadow-lg">
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<style>
.history-section {
    background-color: #ffffff;
}

.history-description {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #5a6c7d;
}

.history-image-wrapper img {
    border: 5px solid #fff;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.history-image-wrapper img:hover {
    transform: translateY(-5px);
}
</style>
@endif