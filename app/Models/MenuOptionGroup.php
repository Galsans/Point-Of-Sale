<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuOptionGroup extends Model
{
    protected $fillable = [
        'menu_id',
        'option_group_id',
        'is_required',
        'min_choice',
        'max_choice',
        'sort_order',
    ];

    /**
     * Get the menus that owns the MenuOptionGroup
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menus()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Get the optionGroup that owns the MenuOptionGroup
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function optionGroup()
    {
        return $this->belongsTo(OptionGroup::class);
    }
}
