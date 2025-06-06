<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'service', 'request_body', 'http_code', 'response_body', 'ip_address',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}