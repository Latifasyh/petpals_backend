<?php

namespace App\Policies;

use App\Models\User;
use App\Models\veto;
use App\Models\Account;
use Illuminate\Auth\Access\Response;

class VetoPolicy
{
    public function modify(Account $account, Veto $veto)
    {
    // Votre logique pour vérifier si l'Account peut modifier le post
        return $account->id === $veto->user_id
        ? Response::allow()
        : Response::deny('you do not own this post');
        ;
    }
}
