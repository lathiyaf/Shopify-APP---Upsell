<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RcAutomation extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'created_at' => 'datetime:d M Y H:i',
    ];

    public function BodyText()
    {
        return $this->hasOne(RcAutomationBodyText::class, 'automation_id', 'id');
    }
}
