<?php namespace App\Repositories\Admin;

use Artesaos\Defender\Permission;
use InfyOm\Generator\Common\BaseRepository;

class PermissionRepository extends BaseRepository {

    public function model()  {
        return Permission::class;
    }

}