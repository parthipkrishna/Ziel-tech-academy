<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateToken extends Model
{
    protected $fillable = ['student_id', 'course_id', 'token', 'expires_at'];
    public $timestamps = true;
}
