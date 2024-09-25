<?php

namespace App\Policies;

use App\Models\User;
use App\Models\seller;
use App\Models\Account;
use Illuminate\Auth\Access\Response;

class SellerPolicy
{
    public function modify(Account $account, Seller $seller)
    {
    // Votre logique pour vérifier si l'Account peut modifier le post
        return $account->id === $seller->user_id
        ? Response::allow()
        : Response::deny('you do not own this post');
        ;
    }
}

