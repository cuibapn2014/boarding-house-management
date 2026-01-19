<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    /**
     * Determine if the user can view any payments.
     */
    public function viewAny(User $user): bool
    {
        return true; // Users can view their own payments
    }

    /**
     * Determine if the user can view the payment.
     */
    public function view(User $user, Payment $payment): bool
    {
        // User can view their own payment or admin can view any
        return $payment->user_id === $user->id || $user->is_admin;
    }

    /**
     * Determine if the user can create payments.
     */
    public function create(User $user): bool
    {
        return true; // Authenticated users can create payments
    }

    /**
     * Determine if the user can update the payment.
     */
    public function update(User $user, Payment $payment): bool
    {
        // Only admin can update payments
        return $user->is_admin;
    }

    /**
     * Determine if the user can delete the payment.
     */
    public function delete(User $user, Payment $payment): bool
    {
        // Only admin can delete payments
        return $user->is_admin;
    }

    /**
     * Determine if the user can cancel the payment.
     */
    public function cancel(User $user, Payment $payment): bool
    {
        // User can cancel their own pending payment, admin can cancel any
        return ($payment->user_id === $user->id || $user->is_admin) 
            && $payment->canBeCancelled();
    }
}
