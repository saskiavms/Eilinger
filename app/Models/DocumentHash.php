<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentHash extends Model
{
    protected $fillable = [
        'user_id',
        'application_id',
        'field_name',
        'file_hash',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public static function findDuplicates(string $hash, ?int $excludeApplicationId = null): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('file_hash', $hash)
            ->when($excludeApplicationId, fn ($q) => $q->where('application_id', '!=', $excludeApplicationId))
            ->with(['user', 'application'])
            ->get();
    }
}
