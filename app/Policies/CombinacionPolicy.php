<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Combinacion;
use Illuminate\Auth\Access\HandlesAuthorization;

class CombinacionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Combinacion');
    }

    public function view(AuthUser $authUser, Combinacion $combinacion): bool
    {
        return $authUser->can('View:Combinacion');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Combinacion');
    }

    public function update(AuthUser $authUser, Combinacion $combinacion): bool
    {
        return $authUser->can('Update:Combinacion');
    }

    public function delete(AuthUser $authUser, Combinacion $combinacion): bool
    {
        return $authUser->can('Delete:Combinacion');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Combinacion');
    }

    public function restore(AuthUser $authUser, Combinacion $combinacion): bool
    {
        return $authUser->can('Restore:Combinacion');
    }

    public function forceDelete(AuthUser $authUser, Combinacion $combinacion): bool
    {
        return $authUser->can('ForceDelete:Combinacion');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Combinacion');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Combinacion');
    }

    public function replicate(AuthUser $authUser, Combinacion $combinacion): bool
    {
        return $authUser->can('Replicate:Combinacion');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Combinacion');
    }

}