@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('S.N.')</th>
                                <th>@lang('Donor')</th>
                                <th>@lang('Contact')</th>
                                <th>@lang('Total Donations')</th>
                                <th>@lang('Total Amount')</th>
                                <th>@lang('Last Donation')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($donors as $donor)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="user">
                                        <span class="name">{{ $donor->first_name }} {{ $donor->last_name }}</span>
                                        <br>
                                        <small class="text--muted">
                                            @if($donor->is_anonymous)
                                                <span class="badge badge--warning">@lang('Anonymous')</span>
                                            @endif
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <span class="d-block"><i class="las la-envelope"></i> {{ $donor->email }}</span>
                                    @if($donor->phone)
                                    <span class="d-block"><i class="las la-phone"></i> {{ $donor->phone }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $donor->donations_count }}</span>
                                </td>
                                <td>
                                    <strong class="text--success">{{ gs()->cur_sym }} {{ number_format($donor->total_amount, 2) }}</strong>
                                </td>
                                <td>
                                    @if($donor->last_donation_at)
                                        {{ showDateTime($donor->last_donation_at) }}
                                        <br>
                                        <small>{{ diffForHumans($donor->last_donation_at) }}</small>
                                    @else
                                        @lang('N/A')
                                    @endif
                                </td>
                                <td>
                                    @if($donor->receive_updates)
                                        <span class="badge badge--success">@lang('Subscribed')</span>
                                    @else
                                        <span class="badge badge--secondary">@lang('Unsubscribed')</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.donors.details', $donor->id) }}" 
                                       class="btn btn-sm btn--primary">
                                        <i class="las la-eye"></i> @lang('Details')
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="8">@lang('No donors found')</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($donors->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($donors) }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection