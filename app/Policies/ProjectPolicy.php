<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     * A logged-in user can always see the dashboard (which is the project list).
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * A user can view a project if they are a professor or the assigned student.
     */
    public function view(User $user, Project $project): bool
    {
        return $user->role === 'professeur' || $user->id === $project->student_id;
    }

    /**
     * Determine whether the user can create models.
     * Only professors can create projects.
     */
    public function create(User $user): bool
    {
        return $user->role === 'professeur';
    }

    /**
     * Determine whether the user can update the model.
     * A user can update a project (e.g., add tasks) if they are a professor or the assigned student.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->role === 'professeur' || $user->id === $project->student_id;
    }

    /**
     * Determine whether the user can delete the model.
     * Only professors can delete projects.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->role === 'professeur';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        return $user->role === 'professeur';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return $user->role === 'professeur';
    }
}
