<?php
// /home/rodhni/tendocharity/app/Models/CauseMilestone.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CauseMilestone extends Model
{
    protected $table = 'cause_milestones';
    
    protected $fillable = [
        'fundraiser_id', 'title', 'description', 'target_amount',
        'completion_date', 'status', 'sort_order'
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'completion_date' => 'date'
    ];

    public function fundraiser()
    {
        return $this->belongsTo(Fundraiser::class);
    }
}