<?php
/*   __________________________________________________
    |  Obfuscated by YAK Pro - Php Obfuscator  2.0.16  |
    |              on 2025-07-22 06:50:08              |
    |    GitHub: https://github.com/pk-fr/yakpro-po    |
    |__________________________________________________|
*/
 namespace App\Models; use Illuminate\Contracts\Auth\MustVerifyEmail; use Illuminate\Database\Eloquent\Factories\HasFactory; use Illuminate\Foundation\Auth\User as Authenticatable; use Illuminate\Notifications\Notifiable; use Laravel\Sanctum\HasApiTokens; class User extends Authenticatable { use HasApiTokens, HasFactory, Notifiable; protected $fillable = array('name', 'email', 'password'); protected $hidden = array('password', 'remember_token'); protected $casts = array('email_verified_at' => 'datetime'); }
