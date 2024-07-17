<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'last_modified',
        'data',
    ];

    public static function createLead(string $leadId, int $lastModified, array $data): void
    {
        self::create([
            'lead_id'       => $leadId,
            'last_modified' => (int) $lastModified,
            'data'          => json_encode($data),
        ]);
    }

    public static function updateLead(string $leadId, int $lastModified, array $data): void
    {
        self::where('lead_id', $leadId)->update([
            'last_modified' => (int) $lastModified,
            'data'          => json_encode($data),
        ]);
    }

    public static function getLeadById(string $leadId)
    {
        return self::all()->where('lead_id', $leadId)->first();
    }

    public static function getAllLeads(): array
    {
        return self::all()->toArray();
    }

    public static function deleteLeadById(string $leadId)
    {
        return self::where('lead_id', $leadId)->delete();
    }
}
