<?php

namespace App\Traits;

trait UserScopedQueries
{
    /**
     * Scope a query to only include records owned by the authenticated user.
     */
    public function scopeOwnedByUser($query, $userId = null)
    {
        $userId = $userId ?? auth()->id();
        return $query->where('owner_id', $userId);
    }

    /**
     * Scope a query to include records owned by user or where user is collaborator.
     */
    public function scopeAccessibleByUser($query, $userId = null)
    {
        $userId = $userId ?? auth()->id();
        return $query->where(function ($q) use ($userId) {
            $q->where('owner_id', $userId)
              ->orWhereJsonContains('collaborators', $userId);
        });
    }

    /**
     * Scope a query for team-owned records.
     */
    public function scopeTeamOwned($query, $teamId = null)
    {
        $teamId = $teamId ?? auth()->user()->current_team_id;
        return $query->where('team_id', $teamId);
    }
}