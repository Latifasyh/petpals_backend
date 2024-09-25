<?php

namespace App\Policies;

use App\Models\post;
use App\Models\User;
use App\Models\Account;
use Illuminate\Auth\Access\Response;


class PostPolicy


{
    // app/Policies/PostPolicy.php
    public function modify(Account $account, Post $post)
    {
    // Votre logique pour vérifier si l'Account peut modifier le post
        return $account->id === $post->user_id
        ? Response::allow()
        : Response::deny('you do not own this post');
        ;
    }


   /*  public function modify(User $user, post $post):Response
    {
      return $user->id === $post->user_id

        ? Response::allow()
        : Response::deny('you do not own this post');
    } */
}
