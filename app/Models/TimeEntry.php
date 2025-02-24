<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'task_id',
        'project_id',
        'description',
        'started_at',
        'ended_at',
        'duration_minutes',
        'is_billable',
        'hourly_rate',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'is_billable' => 'boolean',
        'hourly_rate' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function calculateDuration()
    {
        if ($this->ended_at) {
            $this->duration_minutes = Carbon::parse($this->started_at)
                ->diffInMinutes(Carbon::parse($this->ended_at));
            $this->save();
        }
        return $this->duration_minutes;
    }

    public function getBillableAmount()
    {
        if (!$this->is_billable || !$this->hourly_rate || !$this->duration_minutes) {
            return 0;
        }
        return ($this->hourly_rate / 60) * $this->duration_minutes;
    }

    public function getFormattedDuration()
    {
        if (!$this->duration_minutes) {
            return '0h 0m';
        }

        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        return sprintf('%dh %dm', $hours, $minutes);
    }

    public function isRunning()
    {
        return !$this->ended_at;
    }

    public function stop()
    {
        if ($this->isRunning()) {
            $this->ended_at = now();
            $this->calculateDuration();
            $this->save();
        }
    }

    public function scopeRunning($query)
    {
        return $query->whereNull('ended_at');
    }

    public function scopeForPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('started_at', [$startDate, $endDate]);
    }
}
