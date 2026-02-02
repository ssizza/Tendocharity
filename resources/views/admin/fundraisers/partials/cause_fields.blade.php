<div class="card mb-3">
    <div class="card-header bg--primary">
        <h6 class="card-title text-white mb-0">@lang('Cause Details')</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">@lang('Project Leader') <span class="text-danger">*</span></label>
                    <input type="text" name="project_leader" class="form-control" value="{{ old('project_leader', $fundraiser->project_leader ?? '') }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">@lang('Organization Name')</label>
                    <input type="text" name="organization_name" class="form-control" value="{{ old('organization_name', $fundraiser->organization_name ?? '') }}">
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">@lang('Organization Type')</label>
            <input type="text" name="organization_type" class="form-control" value="{{ old('organization_type', $fundraiser->organization_type ?? '') }}">
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">@lang('Problem Statement')</label>
                    <textarea name="problem_statement" class="form-control" rows="5">{{ old('problem_statement', $fundraiser->problem_statement ?? '') }}</textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">@lang('Solution Statement')</label>
                    <textarea name="solution_statement" class="form-control" rows="5">{{ old('solution_statement', $fundraiser->solution_statement ?? '') }}</textarea>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">@lang('Beneficiaries')</label>
            <textarea name="beneficiaries" class="form-control" rows="3">{{ old('beneficiaries', $fundraiser->beneficiaries ?? '') }}</textarea>
            <small class="text-muted">@lang('Who will benefit from this cause?')</small>
        </div>
        
        <div class="form-group">
            <label class="form-label">@lang('Total Beneficiaries Target')</label>
            <input type="number" name="total_beneficiaries_target" class="form-control" value="{{ old('total_beneficiaries_target', $fundraiser->total_beneficiaries_target ?? '') }}" min="0">
        </div>
        
        <div class="form-group">
            <label class="form-label">@lang('Project Scope')</label>
            <textarea name="project_scope" class="form-control" rows="5">{{ old('project_scope', $fundraiser->project_scope ?? '') }}</textarea>
        </div>
        
        <div class="form-group">
            <label class="form-label">@lang('Risks & Challenges')</label>
            <textarea name="risks_challenges" class="form-control" rows="5">{{ old('risks_challenges', $fundraiser->risks_challenges ?? '') }}</textarea>
        </div>
        
        <div class="form-group">
            <label class="form-label">@lang('Sustainability Plan')</label>
            <textarea name="sustainability_plan" class="form-control" rows="5">{{ old('sustainability_plan', $fundraiser->sustainability_plan ?? '') }}</textarea>
        </div>
    </div>
</div>