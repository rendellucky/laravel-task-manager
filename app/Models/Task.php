<?php

namespace App\Models;

use App\Observers\TaskObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[Fillable(['name', 'project_id'])]
#[ObservedBy(TaskObserver::class)]
class Task extends Model
{
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    protected function casts(): array
    {
        return [
            'priority' => 'integer',
        ];
    }
}
