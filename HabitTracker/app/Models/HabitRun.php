<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HabitRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'ring_count',
        'tree_type_id',
        'ring_count',
        'started_on',
    ];

    public function treeType(): BelongsTo
    {
        return $this->belongsTo(TreeType::class);
    }

    public function habitLogs(): HasMany
    {
        return $this->hasMany(HabitLog::class);
    }
}
