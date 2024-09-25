<?php

namespace App\Policies;

use App\Models\Pet;
use App\Models\User;
use App\Models\Account;
use Illuminate\Auth\Access\Response;

class PetPolicy
{
    public function modify(Account $account, Pet $pet)
    {
    // Votre logique pour vérifier si l'Account peut modifier le post
        return $account->id === $pet->user_id
        ? Response::allow()
        : Response::deny('you do not own this post');
        ;
    }
}
