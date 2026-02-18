{{-- resources/views/admin/donations/index.blade.php --}}
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
                                <th>@lang('Campaign')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Reference')</th>
                                <th>@lang('Payment Method')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($donations as $donation)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="user">
                                        <span class="name">{{ $donation->donor_name }}</span>
                                        <br>
                                        <small class="text--muted">{{ $donation->donor_email }}</small>
                                        @if($donation->donor_phone)
                                        <br>
                                        <small>{{ $donation->donor_phone }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.fundraisers.edit', $donation->fundraiser_id) }}">
                                        {{ Str::limit($donation->fundraiser->title ?? 'N/A', 30) }}
                                    </a>
                                </td>
                                <td>
                                    <strong>{{ $donation->currency }} {{ number_format($donation->amount, 2) }}</strong>
                                    @php
                                        $metadata = json_decode($donation->metadata, true);
                                    @endphp
                                    @if(isset($metadata['final_amount']) && $metadata['final_amount'] != $donation->amount)
                                    <br>
                                    <small class="text--info">
                                        @lang('Total with fees'): {{ $donation->currency }} {{ number_format($metadata['final_amount'], 2) }}
                                    </small>
                                    @endif
                                </td>
                                <td>
                                    <span class="text--primary">{{ $donation->payment_reference }}</span>
                                </td>
                                <td>
                                    @php
                                        $methodLabels = [
                                            'credit_card' => 'primary',
                                            'bank_transfer' => 'info',
                                            'digital_wallet' => 'success',
                                            'other' => 'secondary'
                                        ];
                                    @endphp
                                    <span class="badge badge--{{ $methodLabels[$donation->payment_method] ?? 'secondary' }}">
                                        @lang(ucwords(str_replace('_', ' ', $donation->payment_method)))
                                    </span>
                                    @if($donation->payment_method == 'bank_transfer' && isset($metadata['gateway_name']))
                                    <br>
                                    <small>{{ $metadata['gateway_name'] }}</small>
                                    @endif
                                </td>
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
                                <td>
                                    {{ showDateTime($donation->created_at) }}
                                    <br>
                                    <small>{{ diffForHumans($donation->created_at) }}</small>
                                </td>
                                <td>
                                    <div class="button--group">
                                        <a href="{{ route('admin.donations.details', $donation->id) }}" 
                                           class="btn btn-sm btn--primary">
                                            <i class="las la-eye"></i> @lang('Details')
                                        </a>
                                        
                                        @if($donation->payment_status == 'pending')
                                            <button type="button" 
                                                    class="btn btn-sm btn--success approveBtn" 
                                                    data-id="{{ $donation->id }}"
                                                    data-donor="{{ $donation->donor_name }}"
                                                    data-amount="{{ $donation->currency }} {{ number_format($donation->amount, 2) }}">
                                                <i class="las la-check"></i> @lang('Approve')
                                            </button>
                                            
                                            <button type="button" 
                                                    class="btn btn-sm btn--danger rejectBtn" 
                                                    data-id="{{ $donation->id }}"
                                                    data-donor="{{ $donation->donor_name }}">
                                                <i class="las la-times"></i> @lang('Reject')
                                            </button>
                                        @endif
                                        
                                        <button class="btn btn-sm btn--danger confirmationBtn" 
                                                data-question="@lang('Are you sure to delete this donation?')" 
                                                data-action="{{ route('admin.donations.delete', $donation->id) }}">
                                            <i class="las la-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="9">@lang('No donations found')</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($donations->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($donations) }}
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Approve Modal --}}
<div id="approveModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirm Approval')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="" method="POST" id="approveForm">
                @csrf
                <div class="modal-body">
                    <p>@lang('Are you sure you want to approve this donation from') <span class="fw-bold donor-name"></span>?</p>
                    <p>@lang('Amount:') <span class="fw-bold donation-amount text--success"></span></p>
                    <p class="text--info">@lang('This will mark the donation as completed and update campaign stats.')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Cancel')</button>
                    <button type="submit" class="btn btn--success">@lang('Approve')</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div id="rejectModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Reject Donation')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="" method="POST" id="rejectForm">
                @csrf
                <div class="modal-body">
                    <p>@lang('Are you sure you want to reject this donation from') <span class="fw-bold reject-donor"></span>?</p>
                    
                    <div class="form-group mt-3">
                        <label class="fw-bold">@lang('Rejection Reason') <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control" rows="4" required placeholder="@lang('Please provide reason for rejection...')"></textarea>
                        <small class="text--info">@lang('This reason will be visible to the donor.')</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Cancel')</button>
                    <button type="submit" class="btn btn--danger">@lang('Reject')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<x-confirmation-modal />
@endsection

@push('script')
<script>
    (function ($) {
        "use strict";
        
        $('.approveBtn').on('click', function () {
            var modal = $('#approveModal');
            var id = $(this).data('id');
            var donor = $(this).data('donor');
            var amount = $(this).data('amount');
            
            // Build the URL using route name - FIXED: Using proper route generation with id parameter
            var url = '{{ route("admin.donations.approve", ":id") }}';
            url = url.replace(':id', id);
            
            modal.find('.donor-name').text(donor);
            modal.find('.donation-amount').text(amount);
            modal.find('#approveForm').attr('action', url);
            modal.modal('show');
        });
        
        $('.rejectBtn').on('click', function () {
            var modal = $('#rejectModal');
            var id = $(this).data('id');
            var donor = $(this).data('donor');
            
            // Build the URL using route name - FIXED: Using proper route generation with id parameter
            var url = '{{ route("admin.donations.reject", ":id") }}';
            url = url.replace(':id', id);
            
            modal.find('.reject-donor').text(donor);
            modal.find('#rejectForm').attr('action', url);
            modal.modal('show');
        });
        
    })(jQuery);
</script>
@endpush