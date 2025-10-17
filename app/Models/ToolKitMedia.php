<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolKitMedia extends Model
{
    protected $fillable = ['tool_kit_id', 'file_path'];

    public function toolKit()
    {
        return $this->belongsTo(ToolKit::class);
    }
}
