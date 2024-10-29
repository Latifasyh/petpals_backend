<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Account;
use App\Models\CoverPic;
use Illuminate\Auth\Access\Response;

class CoverPicPolicy
{
    public function modify(Account $account, CoverPic $coverPic)
    {
    // Votre logique pour vérifier si l'Account peut modifier le post
        return $account->id === $coverPic->user_id
        ? Response::allow()
        : Response::deny('you do not own this post');
        ;
    }
}
