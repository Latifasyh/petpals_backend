<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Account;
use App\Models\Service;
use Illuminate\Auth\Access\Response;

class ServicePolicy
{
    public function modify(Account $account, Service $service)
    {
    // Votre logique pour vérifier si l'Account peut modifier le post
        return $account->id === $service->user_id
        ? Response::allow()
        : Response::deny('you do not own this post');
        ;
    }

}
