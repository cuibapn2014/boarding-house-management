<?php
namespace App\Trait;

trait CommonTrait {
    public static function boot()
    {
        parent::boot();
        static::creating(function($model) {
            $model->created_by = auth()->id();
            $model->updated_by = auth()->id();
        });
    }
}