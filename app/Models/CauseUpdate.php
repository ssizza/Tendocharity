<?php
// /home/rodhni/tendocharity/app/Models/CauseUpdate.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CauseUpdate extends Model
{
    protected $table = 'cause_updates';
    
    protected $fillable = [
        'fundraiser_id', 'title', 'content', 'images', 'type', 'is_public', 'created_by'
    ];

    protected $casts = [
        'images' => 'array',
        'is_public' => 'boolean'
    ];

    public function fundraiser()
    {
        return $this->belongsTo(Fundraiser::class);
    }
}