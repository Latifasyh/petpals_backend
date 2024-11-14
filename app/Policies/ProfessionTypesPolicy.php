<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Account;
use App\Models\ProfessionTypes;
use Illuminate\Auth\Access\Response;

class ProfessionTypesPolicy
{

    // app/Policies/PostPolicy.php
    public function modify(Account $account, ProfessionTypes $professionTypes)
    {
    // Votre logique pour vérifier si l'Account peut modifier le post
        return $account->id === $professionTypes->user_id
        ? Response::allow()
        : Response::deny('you do not own this post');
        ;
    }
}
