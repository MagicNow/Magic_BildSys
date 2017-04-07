<?php namespace App\Repositories\Admin;

use Artesaos\Defender\Role;
use InfyOm\Generator\Common\BaseRepository;

class RoleRepository extends BaseRepository {

    public function model()  {
        return Role::class;
    }

}