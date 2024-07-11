<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;

    protected $fillable = [
        'accessToken',
        'refreshToken',
        'expires',
        'baseDomain',
    ];

    public static function createClient($client){
        return self::create($client);
    }
}
