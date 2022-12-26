<?php

namespace App\Models;

use App\Enums\MetaType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Meta
 *
 * @property MetaType $type
 * @property-read \App\Models\Project|null $project
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Meta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Meta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Meta query()
 *
 * @mixin \Eloquent
 *
 * @property int $id
 * @property int $project_id
 * @property string $key
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\MetaFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereValue($value)
 *
 * @property string $attribute
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereAttribute($value)
 */
class Meta extends Model
{
    use HasFactory;

    /**
     * The Project the model belongs to.
     *
     * @return BelongsTo<Project,Meta>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
