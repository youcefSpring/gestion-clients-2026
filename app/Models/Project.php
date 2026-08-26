<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'customer_id', 'name', 'description', 'status'];

    protected function casts(): array
    {
        return ['status' => ProjectStatus::class];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return filled($this->name) ? $this->name : __('app.untitled_project');
    }

    /** Restricts the query to the signed-in user's own records. */
    public function scopeOwnedBy(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when(filled($term), function (Builder $query) use ($term) {
            $query->where(function (Builder $query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhereHas('customer', function (Builder $query) use ($term) {
                        $query->where('name', 'like', "%{$term}%")
                            ->orWhere('phone', 'like', "%{$term}%");
                    });
            });
        });
    }

    /** Hides confirmed and cancelled projects unless the user opts in or filters explicitly. */
    public function scopeVisible(Builder $query, bool $showArchived): Builder
    {
        return $query->unless($showArchived, fn (Builder $query) => $query->whereNotIn('status', ProjectStatus::archived()));
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        return $query->when(
            filled($status) && in_array($status, ProjectStatus::values(), true),
            fn (Builder $query) => $query->where('status', $status)
        );
    }
}
