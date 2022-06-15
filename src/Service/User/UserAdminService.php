<?php

namespace App\Service\User;

class UserAdminService
{
    public function isAdmin($user)
    {
        if($user)
        {
            foreach($user->getRoles() as $role)
            {
                if($role == '[ROLE_ADMIN]')
                {
                    return true;
                }
            }
            return false;
        }
        return false;
    }
}