<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HabitRun extends Model
{
    use HasFactory;

    public function treeType(): BelongsTo
    {
        return $this->belongsTo(TreeType::class);
    }
}
