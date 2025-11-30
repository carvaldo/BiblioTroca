<?php

namespace App\Models\Logs;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Relations\BelongsTo as MongoDBBelongsTo;
use MongoDB\Laravel\Eloquent\Model;

class Log extends Model
{
    protected $connection = 'mongodb';
    protected string $collection = 'logs';

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'created_at',
    ];

    // Relacionamento opcional com User (se User estiver em MariaDB)
    public function user(): BelongsTo|MongoDBBelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }
}
