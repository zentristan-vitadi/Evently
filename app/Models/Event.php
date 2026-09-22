<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'organizer_id',
        'title',
        'description',
        'location',
        'start_date',
        'end_date',
        'capacity',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'capacity' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class, 'event_id');
    }

    public function approvedRegistrations(): HasMany
    {
        return $this->hasMany(Registration::class, 'event_id')->where('status', 'approved');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'registrations', 'event_id', 'user_id')
            ->withPivot('status', 'registered_at')
            ->withTimestamps();
    }

    public function getApprovedCountAttribute(): int
    {
        if ($this->relationLoaded('approvedRegistrations')) {
            return $this->approvedRegistrations->count();
        }

        if (array_key_exists('approved_registrations_count', $this->attributes)) {
            return (int) $this->attributes['approved_registrations_count'];
        }

        return $this->approvedRegistrations()->count();
    }

    public function getRemainingQuotaAttribute(): int
    {
        return max(0, $this->capacity - $this->approved_count);
    }

    public function isFull(): bool
    {
        return $this->remaining_quota <= 0;
    }

    public function canAcceptRegistrations(): bool
    {
        return in_array($this->status, ['upcoming', 'ongoing'], true) && ! $this->isFull();
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, function (Builder $query, string $search): void {
                $query->where(function (Builder $q) use ($search): void {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->when($filters['category_id'] ?? null, function (Builder $query, string $categoryId): void {
                $query->where('category_id', $categoryId);
            })
            ->when($filters['status'] ?? null, function (Builder $query, string $status): void {
                $query->where('status', $status);
            });
    }
}
