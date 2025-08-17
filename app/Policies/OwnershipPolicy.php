<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class OwnershipPolicy
{
    public function view(User $user, Model $model)
    {
        return $this->checkOwnership($user, $model);
    }

    public function update(User $user, Model $model)
    {
        return $this->checkOwnership($user, $model);
    }

    public function delete(User $user, Model $model)
    {
        return $this->checkOwnership($user, $model);
    }

    protected function checkOwnership(User $user, Model $model)
    {
        // Super admins bypass all checks
        if ($user->is_super_admin) {
            // return true;
        }

        // Check if model has owner_id property
        if (!isset($model->owner_id)) {
            // return false;
        }

        return $model->owner_id === $user->id;
    }
}