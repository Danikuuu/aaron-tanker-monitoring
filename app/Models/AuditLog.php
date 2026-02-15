<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'description',
        'model_type', 'model_id', 'meta', 'ip_address',
    ];

    protected function casts(): array
    {
        return ['meta' => 'array'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record(
        string  $action,
        string  $description,
        mixed   $model = null,
        array   $meta = []
    ): void {
        static::create([
            'user_id'     => Auth::id(),
            'action'      => $action,
            'description' => $description,
            'model_type'  => $model ? get_class($model) : null,
            'model_id'    => $model?->id,
            'meta'        => $meta ?: null,
            'ip_address'  => request()->ip(),
        ]);
    }
}