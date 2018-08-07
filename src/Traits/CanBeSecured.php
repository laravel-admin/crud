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
    /**
     * Enforce that the user has one of the authorized roles, abort otherwise
     */
    protected function checkRole() : void
    {
        $authorized = false;

        // If there are no authorized roles specified ($authorizedRoles = [])
        // we make an extra check for 'administrator', otherwise the gate facade
        // is never triggered, and the before() check in laravel-admin/base fails.
        if (count($this->authorizedRoles) === 0) {
            if (Gate::allows('has-role', 'administrator')) {
                $authorized = true;
            }
        }

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
