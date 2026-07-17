<?php

namespace App\Policies;

use App\Models\Investigation;
use App\Models\User;

class InvestigationPolicy
{
    /**
     * Determine whether the user can view the investigation.
     */
    public function view(User $user, Investigation $investigation): bool
    {
        // Super admin has full access
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Assigned investigator has access
        return $user->hasRole('investigator') && $investigation->investigator_id === $user->id;
    }

    /**
     * Determine whether the user can update the investigation (timelines, documents, final results).
     */
    public function update(User $user, Investigation $investigation): bool
    {
        // Cannot update if investigation is already completed
        if ($investigation->status === Investigation::STATUS_COMPLETED) {
            return false;
        }

        // Super admin has full access
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Assigned investigator has access
        return $user->hasRole('investigator') && $investigation->investigator_id === $user->id;
    }

    /**
     * Determine whether the user can securely download supporting documents.
     */
    public function downloadDocument(User $user, Investigation $investigation): bool
    {
        // Super admin has access
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Assigned investigator has access
        return $user->hasRole('investigator') && $investigation->investigator_id === $user->id;
    }
}
