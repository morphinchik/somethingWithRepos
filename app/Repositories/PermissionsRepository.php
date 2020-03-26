<?php 

namespace App\Repositories;

use App\Permission;
use Gate;

class PermissionsRepository extends Repository
{
	protected $rol_rep;
	public function __construct(Permission $permission, RolesRepository $rol_rep)
	{
		$this->model = $permission;
		$this->rol_rep = $rol_rep;
	}
	public function changePermission($request)
	{
		if (Gate::denies('change', $this->model)) {
			 abort(403);
		}

		$data = $request->except('_token');

		$roles = $this->rol_rep->get();
		foreach ($roles as $role) {
			if (isset($data[$role->id])) {
				$role->savePermissions($data[$role->id]);
			} else{
				$role->savePermissions([]);
			}
		}

		return ['status' => 'Права обновлены'];
	}
}




 ?>