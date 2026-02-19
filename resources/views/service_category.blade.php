@extends($activeTemplate . 'layouts.side_bar')

@php
    $products = $serviceCategory->products($filter = true)->paginate(getPaginate());
@endphp

@section('data')
    <div class="col-lg-9">
        <div class="row gy-4 justify-content-center">

            <div class="col-lg-12">
                <h3 class="title">{{ __($serviceCategory->name) }}</h3>
                <p class="text--body mt-2">{{ $serviceCategory->short_description }}</p>
            </div>

            @forelse($products as $product)
                <div class="col-md-4 col-sm-6">
                    <div class="custom--card h-100 position-relative">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="product-name title">{{ __($product->name) }}</h5>

                                @if ($product->stock_control)
                                    <span class="badge badge--fill-base position-absolute top-0 end-0 m-3">{{ $product->stock_quantity }} @lang('Available')</span>
                                @endif

                                <div class="pricing mt-4">
                                    @php
                                        $price = $product->price;
                                        $setup = pricing($product->payment_type, $price, $type = 'setupFee');
                                    @endphp

                                    <div class="pricing-header">
                                        <h3 class="pricing-header__price text--base">
                                            {{ gs('cur_sym') }}{{ pricing($product->payment_type, $price, $type = 'price') }} <span class="text--body">/ {{ __(gs('cur_text')) }}</span>
                                        </h3>
                                        <h5 class="pricing-header__time text--body">
                                            {{ pricing($product->payment_type, $price, $type = 'price', $showText = true) }}
                                        </h5>
                                        <p class="pricing-header__setup text--body">
                                            <span class="text--base">{{ gs('cur_sym') }}{{ $setup }}</span>
                                            {{ pricing($product->payment_type, $price, $type = 'setupFee', $showText = true) }}
                                        </p>
                                    </div>
                                </div>

                                <div class="card-text text--body mt-3">
                                    @php echo nl2br($product->description); @endphp
                                </div>
                            </div>

                            <div class="text-lg-center mt-4">
                                <a href="{{ route('product.configure', ['categorySlug' => $serviceCategory->slug, 'productSlug' => $product->slug, 'id' => $product->id]) }}" class="cmn--btn w-100 text-center">
                                    <i class="las la-shopping-bag"></i> @lang('Order Now')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-md-12">
                    <div class="alert alert--info d-flex justify-content-center flex-wrap p-4" role="alert">
                        <i class="las la-info-circle fs--20px me-2"></i>
                        @lang('No product available in this category')
                    </div>
                </div>
            @endforelse

            <div class="col-12">
                {{ paginateLinks($products) }}
            </div>
        </div>
    </div>
@endsection