<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionGroup extends Model
{
    protected $fillable = [
        'name',
        'type',
        'description',
    ];

    /**
     * Get all of the options for the OptionGroup
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function options()
    // {
    //     return $this->hasMany(Option::class);
    // }

    public function options()
    {
        return $this->hasMany(Option::class)->where('is_active', true);
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_option_groups');
    }
}
