<?php

namespace LaravelAdmin\Crud\Traits;

use Illuminate\Support\Facades\Gate;

/**
 * Mix this trait into a controller to make it aware of
 * user roles and how those should be handled. The actual check before opening
 * a page or executing an actino should still be done in the controller itself!
 */
trait CanBeSecured
{
    protected $authorizedRoles = [];

    /**
     * Enforce that the user has one of the authorized roles, abort otherwise
     */
    protected function checkRole() : void
    {
        $authorized = false;

        foreach ($this->authorizedRoles as $role) {
            if (Gate::allows('has-role', $role)) {
                $authorized = true;
                break;
            }
        }

        if (!$authorized) {
            abort(403, 'You are not allowed to access this');
        }
    }
}
