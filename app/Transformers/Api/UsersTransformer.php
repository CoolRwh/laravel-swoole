<?php

namespace App\Transformers\Api;

use App\Models\Users;
use League\Fractal\TransformerAbstract;

class UsersTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     *
     * @param Users $users
     * @return array
     */
    public function transform(Users $users)
    {
       $data = $users->toArray();
       return $data;
    }
}
