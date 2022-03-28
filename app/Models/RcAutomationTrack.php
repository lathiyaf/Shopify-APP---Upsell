<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RcAutomationTrack extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:d M Y H:i',
    ];
    public function automation()
    {
        return $this->belongsTo(RcAutomation::class, 'automation_id', 'id');
    }
}
