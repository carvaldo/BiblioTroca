<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo as MongoDBBelongsTo;

class UserLog extends Model
{
    protected $connection = 'mongodb';
    protected string $collection = 'user_logs';

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'created_at',
        'session_id',
        ];

    public function user(): BelongsTo|MongoDBBelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function scopeLoginLogout($query)
    {
        return $query->where('action', 'login')->orWhere('action', 'logout');
    }
}
