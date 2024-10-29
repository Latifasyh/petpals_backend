<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Account;
use App\Models\Reaction;
use App\Models\Reactions;
use Illuminate\Auth\Access\Response;

class ReactionsPolicy
{
    public function modify(Account $account, Reaction $reaction)
    {
    // Votre logique pour vérifier si l'Account peut modifier le post
        return $account->id === $reaction->user_id
        ? Response::allow()
        : Response::deny('you do not own this post');
        ;
    }
}
