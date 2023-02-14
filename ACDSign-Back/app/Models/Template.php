<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Ui\Presets\React;

class Template extends Model
{
    use HasFactory;
    protected $fillable = ['subject', 'message', 'footer'];
}

