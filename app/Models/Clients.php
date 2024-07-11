<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'accessToken',
        'refreshToken',
        'expires',
        'baseDomain',
    ];

    protected $primaryKey = 'id';

    public static function createClient($client){
        return self::updateOrCreate(['id' => $client['id']], $client);
    }
}
