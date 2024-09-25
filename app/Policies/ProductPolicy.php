<?php

namespace App\Policies;


use App\Models\Account;
use App\Models\Product;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{

    public function modify(Account $account, Product $product)
    {
    // Votre logique pour vérifier si l'Account peut modifier le post
        return $account->id === $product->seller_id
        ? Response::allow()
        : Response::deny('you do not own this post');
        ;
    }
}
