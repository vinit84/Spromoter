<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportTicket extends Model
{
    const STATUS_OPEN = 'open';
    const STATUS_CLOSED = 'closed';

    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';

    const DEPARTMENT_BILLING = 'billing';
    const DEPARTMENT_TECHNICAL = 'technical';
    const DEPARTMENT_SALES = 'sales';

    const PRIORITIES = [
        self::PRIORITY_LOW,
        self::PRIORITY_MEDIUM,
        self::PRIORITY_HIGH,
    ];
    const DEPARTMENTS = [
        self::DEPARTMENT_BILLING,
        self::DEPARTMENT_TECHNICAL,
        self::DEPARTMENT_SALES,
    ];

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'category',
        'priority',
        'department',
        'status',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(SupportTicketReply::class);
    }
}
