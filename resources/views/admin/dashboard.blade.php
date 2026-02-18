@extends('admin.layouts.app')

@section('panel')
    {{-- Key Metrics Row --}}
    <div class="row g-3 mb-4">
        <div class="col-xxl-3 col-sm-6">
            <div class="dashboard-widget bg--primary">
                <div class="dashboard-widget__content">
                    <div class="dashboard-widget__left">
                        <span class="dashboard-widget__title text-white">Total Donations</span>
                        <h3 class="dashboard-widget__amount text-white">{{ $widget['total_donations'] }}</h3>
                    </div>
                    <div class="dashboard-widget__right">
                        <i class="las la-hand-holding-heart text-white"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xxl-3 col-sm-6">
            <div class="dashboard-widget bg--success">
                <div class="dashboard-widget__content">
                    <div class="dashboard-widget__left">
                        <span class="dashboard-widget__title text-white">Total Raised</span>
                        <h3 class="dashboard-widget__amount text-white">{{ showAmount($widget['total_raised_amount']) }}</h3>
                    </div>
                    <div class="dashboard-widget__right">
                        <i class="las la-arrow-up text-white"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xxl-3 col-sm-6">
            <div class="dashboard-widget bg--info">
                <div class="dashboard-widget__content">
                    <div class="dashboard-widget__left">
                        <span class="dashboard-widget__title text-white">Active Campaigns</span>
                        <h3 class="dashboard-widget__amount text-white">{{ $widget['active_campaigns'] }}</h3>
                    </div>
                    <div class="dashboard-widget__right">
                        <i class="las la-bullhorn text-white"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xxl-3 col-sm-6">
            <div class="dashboard-widget bg--warning">
                <div class="dashboard-widget__content">
                    <div class="dashboard-widget__left">
                        <span class="dashboard-widget__title text-white">Upcoming Events</span>
                        <h3 class="dashboard-widget__amount text-white">{{ $widget['upcoming_events'] }}</h3>
                    </div>
                    <div class="dashboard-widget__right">
                        <i class="las la-calendar text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards Grid --}}
    <div class="row g-3">
        {{-- Donors Card --}}
        <div class="col-xxl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-card__header">
                    <div class="stats-card__icon bg--primary">
                        <i class="las la-users text-white"></i>
                    </div>
                    <h6 class="stats-card__title">@lang('Donors Overview')</h6>
                </div>
                <div class="stats-card__body">
                    <div class="stats-card__item">
                        <span class="stats-card__label">@lang('Total Donors')</span>
                        <span class="stats-card__value text--primary">{{ $widget['total_donors'] }}</span>
                    </div>
                    <div class="stats-card__item">
                        <span class="stats-card__label">@lang('New This Month')</span>
                        <span class="stats-card__value text--success">{{ $widget['new_donors_this_month'] }}</span>
                    </div>
                    <div class="stats-card__item">
                        <span class="stats-card__label">@lang('Anonymous')</span>
                        <span class="stats-card__value text--info">{{ $widget['anonymous_donors'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Campaigns Card --}}
        <div class="col-xxl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-card__header">
                    <div class="stats-card__icon bg--success">
                        <i class="las la-bullhorn text-white"></i>
                    </div>
                    <h6 class="stats-card__title">@lang('Campaigns Overview')</h6>
                </div>
                <div class="stats-card__body">
                    <div class="stats-card__item">
                        <span class="stats-card__label">@lang('Total')</span>
                        <span class="stats-card__value">{{ $widget['total_campaigns'] }}</span>
                    </div>
                    <div class="stats-card__item">
                        <span class="stats-card__label">@lang('Active')</span>
                        <span class="stats-card__value text--success">{{ $widget['active_campaigns'] }}</span>
                    </div>
                    <div class="stats-card__item">
                        <span class="stats-card__label">@lang('Pending')</span>
                        <span class="stats-card__value text--warning">{{ $widget['pending_campaigns'] }}</span>
                    </div>
                    <div class="stats-card__item">
                        <span class="stats-card__label">@lang('Completed')</span>
                        <span class="stats-card__value text--info">{{ $widget['completed_campaigns'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Donations Card --}}
        <div class="col-xxl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-card__header">
                    <div class="stats-card__icon bg--info">
                        <i class="las la-money-bill text-white"></i>
                    </div>
                    <h6 class="stats-card__title">@lang('Donations Overview')</h6>
                </div>
                <div class="stats-card__body">
                    <div class="stats-card__item">
                        <span class="stats-card__label">@lang('Total Count')</span>
                        <span class="stats-card__value">{{ $widget['total_donations'] }}</span>
                    </div>
                    <div class="stats-card__item">
                        <span class="stats-card__label">@lang('Completed Amount')</span>
                        <span class="stats-card__value text--success">{{ showAmount($widget['total_donation_amount']) }}</span>
                    </div>
                    <div class="stats-card__item">
                        <span class="stats-card__label">@lang('Pending Amount')</span>
                        <span class="stats-card__value text--danger">{{ showAmount($widget['pending_donation_amount']) }}</span>
                    </div>
                    <div class="stats-card__item">
                        <span class="stats-card__label">@lang('Monthly')</span>
                        <span class="stats-card__value text--info">{{ showAmount($widget['monthly_donation_amount']) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Events & Services Card --}}
        <div class="col-xxl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-card__header">
                    <div class="stats-card__icon bg--warning">
                        <i class="las la-calendar text-white"></i>
                    </div>
                    <h6 class="stats-card__title">@lang('Events & Services')</h6>
                </div>
                <div class="stats-card__body">
                    <div class="stats-card__item">
                        <span class="stats-card__label">@lang('Total Events')</span>
                        <span class="stats-card__value">{{ $widget['total_events'] }}</span>
                    </div>
                    <div class="stats-card__item">
                        <span class="stats-card__label">@lang('Upcoming Events')</span>
                        <span class="stats-card__value text--success">{{ $widget['upcoming_events'] }}</span>
                    </div>
                    <div class="stats-card__item">
                        <span class="stats-card__label">@lang('Active Services')</span>
                        <span class="stats-card__value text--info">{{ $widget['active_services'] }}</span>
                    </div>
                    <div class="stats-card__item">
                        <span class="stats-card__label">@lang('Team Members')</span>
                        <span class="stats-card__value text--dark">{{ $widget['total_team_members'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activity Tables --}}
    <div class="row g-3 mt-4">
        {{-- Recent Donations --}}
        <div class="col-xxl-6">
            <div class="card custom--card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title">
                        <i class="las la-history me-2 text--primary"></i>
                        @lang('Recent Donations')
                    </h6>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline--primary">
                        <i class="las la-eye"></i> @lang('View All')
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive--sm">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Donor')</th>
                                    <th>@lang('Campaign')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Date')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentDonations->take(5) as $donation)
                                    <tr>
                                        <td>
                                            @if($donation->is_anonymous)
                                                <span class="badge badge--dark">@lang('Anonymous')</span>
                                            @else
                                                <span class="fw-500">{{ $donation->donor_name }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-500" title="{{ $donation->fundraiser->title ?? 'N/A' }}">
                                                {{ Str::limit($donation->fundraiser->title ?? 'N/A', 20) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text--success fw-600">{{ showAmount($donation->amount) }}</span>
                                        </td>
                                        <td>
                                            <span class="small">{{ showDateTime($donation->created_at, 'd M Y') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <i class="las la-frown fa-2x mb-2 text--muted d-block"></i>
                                            @lang('No donations yet')
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Campaigns --}}
        <div class="col-xxl-6">
            <div class="card custom--card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title">
                        <i class="las la-bullhorn me-2 text--success"></i>
                        @lang('Recent Campaigns')
                    </h6>
                    <a href="{{ route('admin.fundraisers.index') }}" class="btn btn-sm btn-outline--success">
                        <i class="las la-eye"></i> @lang('View All')
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive--sm">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Campaign')</th>
                                    <th>@lang('Goal')</th>
                                    <th>@lang('Raised')</th>
                                    <th>@lang('Progress')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentCampaigns->take(5) as $campaign)
                                    @php 
                                        $percentage = $campaign->target_amount > 0 ? min(100, round(($campaign->raised_amount / $campaign->target_amount) * 100)) : 0;
                                        $progressClass = $percentage >= 75 ? 'success' : ($percentage >= 50 ? 'info' : ($percentage >= 25 ? 'warning' : 'primary'));
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="fw-500" title="{{ $campaign->title }}">
                                                {{ Str::limit($campaign->title, 25) }}
                                            </span>
                                            @if($campaign->is_featured)
                                                <span class="badge badge--warning ms-1" title="Featured">★</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-500">{{ showAmount($campaign->target_amount) }}</span>
                                        </td>
                                        <td>
                                            <span class="text--success fw-600">{{ showAmount($campaign->raised_amount) }}</span>
                                        </td>
                                        <td style="min-width: 120px;">
                                            <div class="progress progress--small">
                                                <div class="progress-bar bg--{{ $progressClass }}" style="width: {{ $percentage }}%;">
                                                    <span class="progress-percentage">{{ $percentage }}%</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <i class="las la-frown fa-2x mb-2 text--muted d-block"></i>
                                            @lang('No campaigns yet')
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <div class="d-flex gap-2 flex-wrap">
        <button type="button" class="btn btn-sm btn-outline--primary" data-bs-toggle="modal" data-bs-target="#quickActionsModal">
            <i class="las la-bolt"></i> @lang('Quick Actions')
        </button>
        <a href="{{ route('admin.report.transaction') }}" class="btn btn-sm btn-outline--info">
            <i class="las la-file-invoice"></i> @lang('Reports')
        </a>
    </div>
@endpush

@push('style')
    <style>
        /* Dashboard Widget Styles */
        .dashboard-widget {
            border-radius: 10px;
            padding: 20px;
            color: #fff;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        
        .dashboard-widget:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .dashboard-widget__content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .dashboard-widget__title {
            font-size: 14px;
            opacity: 0.9;
            display: block;
            margin-bottom: 5px;
            color: #fff !important;
        }
        
        .dashboard-widget__amount {
            font-size: 28px;
            font-weight: 600;
            margin: 0;
            line-height: 1.2;
            color: #fff !important;
        }
        
        .dashboard-widget__right i {
            font-size: 48px;
            opacity: 0.3;
            color: #fff !important;
        }
        
        /* Stats Card Styles */
        .stats-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid #e5e5e5;
        }
        
        .stats-card:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }
        
        .stats-card__header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e5e5e5;
        }
        
        .stats-card__icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        
        .stats-card__title {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: #1e1e2f;
        }
        
        .stats-card__body {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .stats-card__item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
        }
        
        .stats-card__item:not(:last-child) {
            border-bottom: 1px dashed #e5e5e5;
        }
        
        .stats-card__label {
            font-size: 13px;
            color: #5b6e88;
        }
        
        .stats-card__value {
            font-weight: 600;
            font-size: 15px;
        }
        
        /* Card Styles */
        .custom--card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid #e5e5e5;
        }
        
        .custom--card:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        
        .custom--card .card-header {
            background: transparent;
            border-bottom: 1px solid #e5e5e5;
            padding: 15px 20px;
        }
        
        .custom--card .card-header .card-title {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: #1e1e2f;
        }
        
        .custom--card .card-body {
            padding: 0;
        }
        
        /* Table Styles */
        .table--light.style--two thead th {
            background: #f7f7f7;
            color: #1e1e2f;
            font-weight: 600;
            font-size: 13px;
            padding: 12px 15px;
            border: none;
        }
        
        .table--light.style--two tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid #e5e5e5;
            vertical-align: middle;
        }
        
        .table--light.style--two tbody tr:last-child td {
            border-bottom: none;
        }
        
        /* Progress Bar Styles */
        .progress--small {
            height: 8px;
            border-radius: 4px;
            background: #e9ecef;
            position: relative;
        }
        
        .progress--small .progress-bar {
            border-radius: 4px;
            position: relative;
        }
        
        .progress-percentage {
            position: absolute;
            right: 0;
            top: -18px;
            font-size: 11px;
            font-weight: 600;
            color: #5b6e88;
        }
        
        /* Badge Styles */
        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
        }
        
        /* Button Styles */
        .btn-outline--primary,
        .btn-outline--success,
        .btn-outline--info {
            border-width: 2px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-outline--primary:hover,
        .btn-outline--success:hover,
        .btn-outline--info:hover {
            transform: translateY(-1px);
        }
        
        /* Text Utilities */
        .fw-500 { font-weight: 500; }
        .fw-600 { font-weight: 600; }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .dashboard-widget__amount {
                font-size: 22px;
            }
            
            .dashboard-widget__right i {
                font-size: 36px;
            }
            
            .stats-card {
                padding: 15px;
            }
            
            .stats-card__title {
                font-size: 14px;
            }
        }
    </style>
@endpush

{{-- Quick Actions Modal --}}
<div class="modal fade" id="quickActionsModal" tabindex="-1" aria-labelledby="quickActionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickActionsModalLabel">
                    <i class="las la-bolt me-2 text--primary"></i>
                    @lang('Quick Actions')
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.fundraisers.create') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <div class="quick-action-icon bg--success me-3">
                            <i class="las la-plus-circle"></i>
                        </div>
                        <div>
                            <span class="d-block fw-600">@lang('Create New Campaign')</span>
                            <small class="text-muted">@lang('Start a new fundraising campaign')</small>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.events.create') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <div class="quick-action-icon bg--primary me-3">
                            <i class="las la-calendar-plus"></i>
                        </div>
                        <div>
                            <span class="d-block fw-600">@lang('Create New Event')</span>
                            <small class="text-muted">@lang('Schedule a new event')</small>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.services.create') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <div class="quick-action-icon bg--info me-3">
                            <i class="las la-hand-holding-heart"></i>
                        </div>
                        <div>
                            <span class="d-block fw-600">@lang('Add New Service')</span>
                            <small class="text-muted">@lang('Create a new service offering')</small>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.team.create') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <div class="quick-action-icon bg--warning me-3">
                            <i class="las la-user-plus"></i>
                        </div>
                        <div>
                            <span class="d-block fw-600">@lang('Add Team Member')</span>
                            <small class="text-muted">@lang('Add a new team member')</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('style-lib')
    <style>
        /* Quick Action Modal Styles */
        .quick-action-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 20px;
        }
        
        .list-group-item-action {
            border: none;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }
        
        .list-group-item-action:hover {
            background: #f8f9fa;
            transform: translateX(5px);
        }
        
        .list-group-item-action:last-child {
            margin-bottom: 0;
        }
    </style>
@endpush