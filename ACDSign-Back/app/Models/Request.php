<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;
    protected $fillable = ['siren', 'company_name', 'address', 'city', 'zip', 'signed', 'PDL', 'PCE', 'first_name', 'last_name', 'email', 'supplier', 'user_id'];

    protected $with = ['user'];

    public function user()
    {
        return $this->hasOne(User::class, "id", "user_id");
    }
}
