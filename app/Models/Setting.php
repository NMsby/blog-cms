<?php

namespace App\Models;

use Database\Factories\SettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    protected static function newFactory(): SettingFactory
    {
        return SettingFactory::new();
    }

    public static function getValue($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value, $group = 'general'): Setting
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group
            ]
        );
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeGroup($query, $group)
    {
        return $query->where('group', $group);
    }
}
