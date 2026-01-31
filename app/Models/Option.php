<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = [
        'option_group_id',
        'name',
        'extra_price',
        'is_active',
    ];

    protected $casts = [
        'extra_price' => 'integer',
        'is_active' => 'boolean',
    ];


    /**
     * Get the optionGroup that owns the Option
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function optionGroup()
    {
        return $this->belongsTo(OptionGroup::class);
    }
}
