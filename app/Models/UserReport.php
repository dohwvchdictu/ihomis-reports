<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReport extends Model
{
    use HasFactory;

    // Specify the table name if it doesn't match the plural form
    protected $table = 'users_reports';

    // Specify the fillable fields (mass assignment protection)
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    // Optional: Specify the date fields for the model
    protected $dates = [
        'email_verified_at',
    ];
}
