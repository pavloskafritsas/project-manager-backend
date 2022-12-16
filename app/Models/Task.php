<?php

namespace App\Models;

use App\Enums\Priority;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Task
 *
 * @property int $id
 * @property string $name
 * @property int $project_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Project $project
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task query()
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereUpdatedAt($value)
 * @mixin \Eloquent
 *
 * @property string|null $description
 * @property Priority $priority
 * @property Status $status
 *
 * @method static \Database\Factories\TaskFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereStatus($value)
 */
class Task extends Model
{
    use HasFactory;

    /**
     * The project the model belongs to.
     *
     * @return BelongsTo<Project,Task>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
