<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends \Laravel\Cashier\Subscription
{
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
