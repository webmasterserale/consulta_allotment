<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Allotment;
use Illuminate\Auth\Access\HandlesAuthorization;

class AllotmentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Allotment');
    }

    public function view(AuthUser $authUser, Allotment $allotment): bool
    {
        return $authUser->can('View:Allotment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Allotment');
    }

    public function update(AuthUser $authUser, Allotment $allotment): bool
    {
        return $authUser->can('Update:Allotment');
    }

    public function delete(AuthUser $authUser, Allotment $allotment): bool
    {
        return $authUser->can('Delete:Allotment');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Allotment');
    }

    public function restore(AuthUser $authUser, Allotment $allotment): bool
    {
        return $authUser->can('Restore:Allotment');
    }

    public function forceDelete(AuthUser $authUser, Allotment $allotment): bool
    {
        return $authUser->can('ForceDelete:Allotment');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Allotment');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Allotment');
    }

    public function replicate(AuthUser $authUser, Allotment $allotment): bool
    {
        return $authUser->can('Replicate:Allotment');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Allotment');
    }

}