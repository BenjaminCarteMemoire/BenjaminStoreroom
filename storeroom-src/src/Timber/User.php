<?php

namespace App\Core\Timber;

class User extends \Timber\User
{
    public function profile_url()
    {
        return \get_author_posts_url($this->id);
    }
}
