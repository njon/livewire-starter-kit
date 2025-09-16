<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\OwnerScope;

class Invoice extends Model
{
    use HasFactory, OwnerScope;

    protected $fillable = [
        'order_id',
        'owner_id',
        'invoice_number',
        'invoice_path',
        'amount',
        'currency_code',
        'generated_at',
        'status',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('invoices.download', $this->id);
    }

    public function markAsDownloaded(): void
    {
        $this->update(['status' => 'downloaded']);
    }
}