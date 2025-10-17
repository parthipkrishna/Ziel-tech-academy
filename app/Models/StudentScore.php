<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentScore extends Model
{
    use HasFactory;

    protected $table = 'student_scores';
    protected $fillable = [
        'exam_id',
        'student_id',
        'total_score',
        'correct_answers',
        'incorrect_answers',
        'exam_attempt_id'
    ];

    protected $appends = ['pass_status', 'joined_at', 'completed_at', 'total_time_taken'];

    // Relations
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function answers()
    {
        return $this->hasMany(StudentAnswer::class, 'exam_attempt_id', 'exam_attempt_id')
            ->with('selectedAnswer', 'question.answers');
    }

    public function participants()
    {
        return $this->hasMany(ExamParticipant::class, 'exam_attempt_id', 'exam_attempt_id');
    }

    // Accessors
    public function getPassStatusAttribute(): string
    {
        return $this->exam && $this->total_score >= $this->exam->minimum_passing_marks
            ? 'Passed' : 'Failed';
    }

    public function getJoinedAtAttribute()
    {
        $participant = $this->participants()->orderBy('joined_at')->first();
        return $participant?->joined_at ? Carbon::parse($participant->joined_at)->format('d M Y, H:i') : null;
    }

    public function getCompletedAtAttribute()
    {
        $participant = $this->participants()->whereNotNull('completed_at')->orderByDesc('completed_at')->first();
        return $participant?->completed_at ? Carbon::parse($participant->completed_at)->format('d M Y, H:i') : null;
    }

    public function getTotalTimeTakenAttribute()
    {
        $joined = $this->participants()->orderBy('joined_at')->first()?->joined_at;
        $completed = $this->participants()->whereNotNull('completed_at')->orderByDesc('completed_at')->first()?->completed_at;

        if ($joined && $completed) {
            $seconds = Carbon::parse($joined)->diffInSeconds(Carbon::parse($completed));
            return $seconds < 60 ? round($seconds, 1) . 's' : floor($seconds / 60) . 'm ' . ($seconds % 60) . 's';
        }

        return null;
    }

    public function attempt()
    {
        return $this->belongsTo(ExamAttempt::class, 'exam_attempt_id', 'unique_id');
    }

}
