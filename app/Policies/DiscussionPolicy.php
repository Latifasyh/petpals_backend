<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Account;
use App\Models\Discussion;
use Illuminate\Auth\Access\Response;

class DiscussionPolicy
{
    public function modify(Account $account, Discussion $discussion)
    {
    // Votre logique pour vérifier si l'Account peut modifier le post
        return $account->id === $discussion->user_id
        ? Response::allow()
        : Response::deny('you do not own this message');
        ;
    }
}
