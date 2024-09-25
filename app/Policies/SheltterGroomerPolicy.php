<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Account;
use App\Models\SheltterGroomer;
use Illuminate\Auth\Access\Response;

class SheltterGroomerPolicy
{
    public function modify(Account $account, SheltterGroomer $sheltterGroomer)
    {
    // Votre logique pour vérifier si l'Account peut modifier le post
        return $account->id === $sheltterGroomer->user_id
        ? Response::allow()
        : Response::deny('you do not own this post');
        ;
    }

}
