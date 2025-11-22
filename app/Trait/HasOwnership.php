<?php

namespace App\Trait;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait HasOwnership
 * 
 * Provides scope to filter records by ownership.
 * Admin users can see all records, regular users only see their own.
 * 
 * @package App\Trait
 */
trait HasOwnership
{
    /**
     * Scope to filter records by current user or admin
     * 
     * Regular users: Only see records they created
     * Admin users: See all records
     * 
     * @param Builder $query
     * @return Builder
     */
    public function scopeBySelf(Builder $query): Builder
    {
        return $query->when(!$this->isUserAdmin(), function($query) {
            $query->where('created_by', auth()->id());
        });
    }

    /**
     * Scope to filter only records created by current user
     * (Ignores admin status)
     * 
     * @param Builder $query
     * @return Builder
     */
    public function scopeByOwner(Builder $query): Builder
    {
        return $query->where('created_by', auth()->id());
    }

    /**
     * Check if current user is admin
     * 
     * @return bool
     */
    protected function isUserAdmin(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        // Check for is_admin property
        if (auth()->user()->is_admin ?? false) {
            return true;
        }

        // Check for admin role if using roles
        if (method_exists(auth()->user(), 'hasRole')) {
            return auth()->user()->hasRole('admin');
        }

        return false;
    }

    /**
     * Check if the record is owned by current user
     * 
     * @return bool
     */
    public function isOwnedByCurrentUser(): bool
    {
        return $this->created_by === auth()->id();
    }

    /**
     * Check if current user can access this record
     * 
     * @return bool
     */
    public function canAccess(): bool
    {
        return $this->isUserAdmin() || $this->isOwnedByCurrentUser();
    }
}

