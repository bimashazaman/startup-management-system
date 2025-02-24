<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Milestone extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'project_id',
        'due_date',
        'status',
        'budget',
        'actual_cost',
        'completion_percentage',
    ];

    protected $casts = [
        'due_date' => 'date',
        'budget' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'completion_percentage' => 'integer',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function isOverBudget()
    {
        return $this->budget && $this->actual_cost > $this->budget;
    }

    public function getBudgetVariance()
    {
        if (!$this->budget || !$this->actual_cost) {
            return 0;
        }
        return $this->actual_cost - $this->budget;
    }

    public function scopeUpcoming($query, $days = 30)
    {
        return $query->whereBetween('due_date', [now(), now()->addDays($days)]);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->where('status', '!=', 'completed');
    }
}
