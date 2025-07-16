<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAcc extends Model
{
    use HasFactory;

    protected $table = 'user_acc';

    // If the primary key is not 'id'
    protected $primaryKey = 'user_name';

    // Primary key is not an integer and not auto-incrementing
    public $incrementing = false;

    // If primary key is not an integer
    protected $keyType = 'string';

    // If you don't use created_at and updated_at
    public $timestamps = false;

    // Mass assignable attributes
    protected $fillable = [
        'user_name',
        'user_level',
        'user_pass',
        'user_exp',
        'employeeid',
        'workstation',
    ];

}
