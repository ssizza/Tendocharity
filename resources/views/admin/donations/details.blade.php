@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">@lang('Donation Details') - {{ $donation->payment_reference }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Donor Information -->
                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-header bg--primary text-white">
                                <h6 class="m-0"><i class="las la-user me-2"></i>@lang('Donor Information')</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">@lang('Name'):</th>
                                        <td>{{ $donation->donor_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Email'):</th>
                                        <td>{{ $donation->donor_email }}</td>
                                    </tr>
                                    @if($donation->donor_phone)
                                    <tr>
                                        <th>@lang('Phone'):</th>
                                        <td>{{ $donation->donor_phone }}</td>
                                    </tr>
                                    @endif
                                    @if($donation->donor_address)
                                    <tr>
                                        <th>@lang('Address'):</th>
                                        <td>{{ $donation->donor_address }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>@lang('Anonymous'):</th>
                                        <td>
                                            @if($donation->is_anonymous)
                                                <span class="badge badge--success">@lang('Yes')</span>
                                            @else
                                                <span class="badge badge--secondary">@lang('No')</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Tax Deductible'):</th>
                                        <td>
                                            @if($donation->tax_deductible)
                                                <span class="badge badge--success">@lang('Yes')</span>
                                            @else
                                                <span class="badge badge--secondary">@lang('No')</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($donation->donor_id)
                                    <tr>
                                        <th>@lang('Donor Profile'):</th>
                                        <td>
                                            <a href="{{ route('admin.donors.details', $donation->donor_id) }}" class="btn btn-sm btn--primary">
                                                <i class="las la-eye"></i> @lang('View Donor')
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Donation Information -->
                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-header bg--success text-white">
                                <h6 class="m-0"><i class="las la-donate me-2"></i>@lang('Donation Information')</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">@lang('Reference'):</th>
                                        <td><strong>{{ $donation->payment_reference }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Amount'):</th>
                                        <td><strong class="text--success">{{ $donation->currency }} {{ number_format($donation->amount, 2) }}</strong></td>
                                    </tr>
                                    @if(isset($metadata['final_amount']) && $metadata['final_amount'] != $donation->amount)
                                    <tr>
                                        <th>@lang('Total with Fees'):</th>
                                        <td><span class="text--info">{{ $donation->currency }} {{ number_format($metadata['final_amount'], 2) }}</span></td>
                                    </tr>
                                    @endif
                                    @if(isset($metadata['charge']) && $metadata['charge'] > 0)
                                    <tr>
                                        <th>@lang('Processing Fee'):</th>
                                        <td>{{ $donation->currency }} {{ number_format($metadata['charge'], 2) }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>@lang('Payment Method'):</th>
                                        <td>
                                            <span class="badge badge--primary">{{ ucwords(str_replace('_', ' ', $donation->payment_method)) }}</span>
                                            @if(isset($metadata['gateway_name']))
                                                <br><small>{{ $metadata['gateway_name'] }}</small>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Status'):</th>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'completed' => 'success',
                                                    'failed' => 'danger',
                                                    'refunded' => 'info'
                                                ];
                                            @endphp
                                            <span class="badge badge--{{ $statusColors[$donation->payment_status] ?? 'secondary' }} badge--lg">
                                                @lang(ucfirst($donation->payment_status))
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Date'):</th>
                                        <td>{{ showDateTime($donation->created_at) }} <br> <small>{{ diffForHumans($donation->created_at) }}</small></td>
                                    </tr>
                                    @if($donation->message)
                                    <tr>
                                        <th>@lang('Message'):</th>
                                        <td><em>"{{ $donation->message }}"</em></td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Campaign Information -->
                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-header bg--info text-white">
                                <h6 class="m-0"><i class="las la-hand-holding-heart me-2"></i>@lang('Campaign Information')</h6>
                            </div>
                            <div class="card-body">
                                @if($donation->fundraiser)
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">@lang('Title'):</th>
                                        <td>
                                            <a href="{{ route('admin.fundraisers.edit', $donation->fundraiser_id) }}">
                                                {{ $donation->fundraiser->title }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Target'):</th>
                                        <td>{{ $donation->currency }} {{ number_format($donation->fundraiser->target_amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Raised'):</th>
                                        <td>{{ $donation->currency }} {{ number_format($donation->fundraiser->raised_amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Progress'):</th>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg--success" 
                                                     role="progressbar" 
                                                     style="width: {{ $donation->fundraiser->progress_percentage }}%;" 
                                                     aria-valuenow="{{ $donation->fundraiser->progress_percentage }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ number_format($donation->fundraiser->progress_percentage, 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                @else
                                <p class="text--danger">@lang('Campaign not found')</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Technical Information -->
                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-header bg--secondary text-white">
                                <h6 class="m-0"><i class="las la-server me-2"></i>@lang('Technical Information')</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">@lang('IP Address'):</th>
                                        <td>{{ $donation->ip_address ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Browser'):</th>
                                        <td>{{ $donation->browser ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('OS'):</th>
                                        <td>{{ $donation->os ?? 'N/A' }}</td>
                                    </tr>
                                    @if(isset($metadata['client_info']))
                                        <tr>
                                            <th>@lang('Location'):</th>
                                            <td>
                                                @if(isset($metadata['client_info']['country']))
                                                    {{ $metadata['client_info']['country'] }}
                                                @endif
                                                @if(isset($metadata['client_info']['city']))
                                                    , {{ $metadata['client_info']['city'] }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>@lang('Receipt Sent'):</th>
                                        <td>
                                            @if($donation->receipt_sent)
                                                <span class="badge badge--success">@lang('Yes')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('No')</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Manual Payment Details -->
                    @if($donation->payment_method == 'bank_transfer' && isset($metadata['manual_payment']))
                    <div class="col-md-12">
                        <div class="card border mb-3">
                            <div class="card-header bg--warning">
                                <h6 class="m-0"><i class="las la-file-invoice me-2"></i>@lang('Manual Payment Details')</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @if(isset($metadata['manual_payment']['payment_proof']))
                                    <div class="col-md-4 text-center">
                                        <h6>@lang('Payment Proof')</h6>
                                        <a href="{{ asset($metadata['manual_payment']['payment_proof']) }}" target="_blank">
                                            <img src="{{ asset($metadata['manual_payment']['payment_proof']) }}" 
                                                 alt="@lang('Proof')" 
                                                 class="img-fluid img-thumbnail"
                                                 style="max-height: 200px;"
                                                 onerror="this.onerror=null; this.src='{{ asset('assets/images/default.png') }}';">
                                        </a>
                                        <br>
                                        <a href="{{ asset($metadata['manual_payment']['payment_proof']) }}" 
                                           class="btn btn-sm btn--primary mt-2" 
                                           download>
                                            <i class="las la-download"></i> @lang('Download')
                                        </a>
                                    </div>
                                    @endif
                                    
                                    <div class="col-md-8">
                                        <h6>@lang('Submission Details')</h6>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th width="40%">@lang('Submitted At'):</th>
                                                <td>{{ isset($metadata['manual_payment']['submitted_at']) ? showDateTime($metadata['manual_payment']['submitted_at']) : 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>@lang('IP Address'):</th>
                                                <td>{{ $metadata['manual_payment']['ip_address'] ?? 'N/A' }}</td>
                                            </tr>
                                            @if(isset($metadata['manual_payment']['form_data']))
                                                @foreach($metadata['manual_payment']['form_data'] as $key => $value)
                                                <tr>
                                                    <th>{{ ucwords(str_replace('_', ' ', $key)) }}:</th>
                                                    <td>
                                                        @if(is_array($value))
                                                            {{ implode(', ', $value) }}
                                                        @elseif(strpos($value, 'assets/uploads/') === 0)
                                                            <a href="{{ asset($value) }}" target="_blank">@lang('View File')</a>
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Rejection Information -->
                    @if(isset($metadata['rejection']))
                    <div class="col-md-12">
                        <div class="card border-danger mb-3">
                            <div class="card-header bg--danger text-white">
                                <h6 class="m-0"><i class="las la-exclamation-triangle me-2"></i>@lang('Rejection Information')</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="20%">@lang('Reason'):</th>
                                        <td>{{ $metadata['rejection']['reason'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Rejected By'):</th>
                                        <td>{{ $metadata['rejection']['rejected_by'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Rejected At'):</th>
                                        <td>{{ isset($metadata['rejection']['rejected_at']) ? showDateTime($metadata['rejection']['rejected_at']) : 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Action Buttons -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-end gap-2">
                            @if($donation->payment_status == 'pending')
                                <button type="button" class="btn btn--success approveBtn" 
                                        data-id="{{ $donation->id }}"
                                        data-donor="{{ $donation->donor_name }}"
                                        data-amount="{{ $donation->currency }} {{ number_format($donation->amount, 2) }}">
                                    <i class="las la-check"></i> @lang('Approve Donation')
                                </button>
                                
                                <button type="button" class="btn btn--danger rejectBtn" 
                                        data-id="{{ $donation->id }}"
                                        data-donor="{{ $donation->donor_name }}">
                                    <i class="las la-times"></i> @lang('Reject Donation')
                                </button>
                            @endif
                            
                            <a href="{{ route('admin.donations.index') }}" class="btn btn--dark">
                                <i class="las la-arrow-left"></i> @lang('Back')
                            </a>
                        </div>
                    </div>
                </div>
            </div>
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