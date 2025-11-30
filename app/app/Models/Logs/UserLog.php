<?php

namespace App\Models\Logs;

use App\Models\Logs\Log;

class UserLog extends Log
{
    protected $fillable = ['antes', 'depois'];
}
