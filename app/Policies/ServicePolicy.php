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
    // Vérifiez si l'utilisateur associé à l'Account a le même ProfessionType que le produit
        $user = $account->user; // Récupérer l'utilisateur associé à l'Account
        $professionTypeId = $user->professionType->id; // Récupérer l'ID du ProfessionType de l'utilisateur

        // Vérifiez si l'ID du ProfessionType de l'utilisateur correspond à celui du produit
        return $service->profession_type_id === $professionTypeId
            ? Response::allow()
            : Response::deny('You do not own this product');
            ;
    }

}
