<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class PaymentGatewayConfig extends Model
{
    protected $table = 'payment_gateway_configs';

    protected $fillable = [
        'gateway_name',
        'display_name',
        'status',
        'api_key',
        'api_secret',
        'webhook_secret',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    /**
     * Scope to get only active gateway
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function setApiKeyAttribute($value)
    {
        if (!empty($value) && !Hash::needsRehash($value)) {
            $this->attributes['api_key'] = bcrypt($value);
        } else {
            $this->attributes['api_key'] = $value;
        }
    }

    public function setApiSecretAttribute($value)
    {
        if (!empty($value) && !Hash::needsRehash($value)) {
            $this->attributes['api_secret'] = bcrypt($value);
        } else {
            $this->attributes['api_secret'] = $value;
        }
    }

    public function setWebhookSecretAttribute($value)
    {
        if (!empty($value) && !Hash::needsRehash($value)) {
            $this->attributes['webhook_secret'] = bcrypt($value);
        } else {
            $this->attributes['webhook_secret'] = $value;
        }
    }


}
