<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Html\Editor\Fields\Hidden;

class ToolKitEnquiry extends Model
{
    use HasFactory;

    protected $table = 'toolkit_enquiries';

    protected $fillable = [
        'toolkit_id',
        'student_id',
        'student_name',
        'state',
        'phone',
        'email',
        'address',
        'toolkit_name',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    // Status constants (to avoid hardcoding in code)
    public const STATUS_REQUEST_PLACED = 'request_placed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_DELIVERED = 'delivered';

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'created_at',
        'updated_at',
        'toolkit_id',
        'student_id'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function toolkit()
    {
        return $this->belongsTo(Toolkit::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isDelivered(): bool
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }
}
