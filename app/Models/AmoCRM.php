<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmoCRM extends Model
{
    protected $primaryKey = 'client_id';
    protected $fillable = [
        'client_id',
        'client_secret',
        'subdomain',
        'access_token',
        'redirect_uri',
        'token_type',
        'refresh_token',
        'expires_in',
    ];
}
