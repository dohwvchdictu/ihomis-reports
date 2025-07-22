<?php
/*   __________________________________________________
    |  Obfuscated by YAK Pro - Php Obfuscator  2.0.16  |
    |              on 2025-07-22 06:50:08              |
    |    GitHub: https://github.com/pk-fr/yakpro-po    |
    |__________________________________________________|
*/
 namespace App\Models; use Illuminate\Database\Eloquent\Factories\HasFactory; use Illuminate\Database\Eloquent\Model; class UserReport extends Model { use HasFactory; protected $table = 'users_reports'; protected $fillable = array('name', 'email', 'password'); protected $hidden = array('password', 'remember_token', 'email_verified_at', 'created_at', 'updated_at'); protected $dates = array('email_verified_at'); }
