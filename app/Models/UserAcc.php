<?php
/*   __________________________________________________
    |  Obfuscated by YAK Pro - Php Obfuscator  2.0.16  |
    |              on 2025-07-22 06:50:08              |
    |    GitHub: https://github.com/pk-fr/yakpro-po    |
    |__________________________________________________|
*/
 namespace App\Models; use Illuminate\Database\Eloquent\Factories\HasFactory; use Illuminate\Database\Eloquent\Model; class UserAcc extends Model { use HasFactory; protected $table = 'user_acc'; protected $primaryKey = 'user_name'; public $incrementing = false; protected $keyType = 'string'; public $timestamps = false; protected $fillable = array('user_name', 'user_level', 'user_pass', 'user_exp', 'employeeid', 'workstation'); }
