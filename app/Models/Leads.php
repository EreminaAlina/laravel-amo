<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    use HasFactory;

    protected $fillable = [
        'amo_id',
        'amo_status_id',
        'lead_id',
    ];

    public static function updateStageLead()
    {
        //TODO save lead to DB
    }
}
