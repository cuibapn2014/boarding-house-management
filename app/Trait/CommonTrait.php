<?php

namespace App\Trait;

trait CommonTrait 
{
    /**
     * Boot the trait
     */
    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
                $model->updated_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });
    }
}