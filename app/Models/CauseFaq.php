<?php
// /home/rodhni/tendocharity/app/Models/CauseFaq.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CauseFaq extends Model
{
    protected $table = 'cause_faqs';
    
    protected $fillable = [
        'fundraiser_id', 'question', 'answer', 'sort_order'
    ];

    public function fundraiser()
    {
        return $this->belongsTo(Fundraiser::class);
    }
}