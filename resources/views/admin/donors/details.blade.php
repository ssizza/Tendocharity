@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">@lang('Donor Details') - {{ $donor->first_name }} {{ $donor->last_name }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-header bg--primary text-white">
                                <h6 class="m-0"><i class="las la-user me-2"></i>@lang('Personal Information')</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">@lang('Name'):</th>
                                        <td>{{ $donor->first_name }} {{ $donor->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Email'):</th>
                                        <td>{{ $donor->email }}</td>
                                    </tr>
                                    @if($donor->phone)
                                    <tr>
                                        <th>@lang('Phone'):</th>
                                        <td>{{ $donor->phone }}</td>
                                    </tr>
                                    @endif
                                    @if($donor->country)
                                    <tr>
                                        <th>@lang('Country'):</th>
                                        <td>{{ $donor->country }}</td>
                                    </tr>
                                    @endif
                                    @if($donor->city)
                                    <tr>
                                        <th>@lang('City'):</th>
                                        <td>{{ $donor->city }}</td>
                                    </tr>
                                    @endif
                                    @if($donor->address)
                                    <tr>
                                        <th>@lang('Address'):</th>
                                        <td>{{ $donor->address }}</td>
                                    </tr>
                                    @endif
                                    @if($donor->postal_code)
                                    <tr>
                                        <th>@lang('Postal Code'):</th>
                                        <td>{{ $donor->postal_code }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-header bg--success text-white">
                                <h6 class="m-0"><i class="las la-chart-line me-2"></i>@lang('Donation Statistics')</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">@lang('Total Donations'):</th>
                                        <td><span class="fw-bold">{{ $donor->donations_count }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Total Amount'):</th>
                                        <td><strong class="text--success">{{ gs()->cur_sym }} {{ number_format($donor->total_amount, 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Last Donation'):</th>
                                        <td>
                                            @if($donor->last_donation_at)
                                                {{ showDateTime($donor->last_donation_at) }}
                                                <br>
                                                <small>{{ diffForHumans($donor->last_donation_at) }}</small>
                                            @else
                                                @lang('N/A')
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Receive Updates'):</th>
                                        <td>
                                            @if($donor->receive_updates)
                                                <span class="badge badge--success">@lang('Yes')</span>
                                            @else
                                                <span class="badge badge--secondary">@lang('No')</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Tax Deductible Eligible'):</th>
                                        <td>
                                            @if($donor->tax_deductible_eligible)
                                                <span class="badge badge--success">@lang('Yes')</span>
                                            @else
                                                <span class="badge badge--secondary">@lang('No')</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Registered'):</th>
                                        <td>{{ showDateTime($donor->created_at) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="card border">
                            <div class="card-header bg--info text-white">
                                <h6 class="m-0"><i class="las la-donate me-2"></i>@lang('Donation History')</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive--sm table-responsive">
                                    <table class="table table--light style--two">
                                        <thead>
                                            <tr>
                                                <th>@lang('S.N.')</th>
                                                <th>@lang('Reference')</th>
                                                <th>@lang('Campaign')</th>
                                                <th>@lang('Amount')</th>
                                                <th>@lang('Status')</th>
                                                <th>@lang('Date')</th>
                                                <th>@lang('Action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($donations as $donation)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><span class="text--primary">{{ $donation->payment_reference }}</span></td>
                                                <td>
                                                    <a href="{{ route('admin.fundraisers.edit', $donation->fundraiser_id) }}">
                                                        {{ Str::limit($donation->fundraiser->title ?? 'N/A', 30) }}
                                                    </a>
                                                </td>
                                                <td><strong>{{ $donation->currency }} {{ number_format($donation->amount, 2) }}</strong></td>
                                                <td>
                                                    @php
                                                        $statusColors = [
                                                            'pending' => 'warning',
                                                            'completed' => 'success',
                                                            'failed' => 'danger',
                                                            'refunded' => 'info'
                                                        ];
                                                    @endphp
                                                    <span class="badge badge--{{ $statusColors[$donation->payment_status] ?? 'secondary' }}">
                                                        @lang(ucfirst($donation->payment_status))
                                                    </span>
                                                </td>
                                                <td>{{ showDateTime($donation->created_at) }}</td>
                                                <td>
                                                    <a href="{{ route('admin.donations.details', $donation->id) }}" 
                                                       class="btn btn-sm btn--primary">
                                                        <i class="las la-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td class="text-muted text-center" colspan="7">@lang('No donations found')</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if($donations->hasPages())
                                <div class="p-3">
                                    {{ paginateLinks($donations) }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.donors.index') }}" class="btn btn--dark">
                                <i class="las la-arrow-left"></i> @lang('Back to Donors')
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection