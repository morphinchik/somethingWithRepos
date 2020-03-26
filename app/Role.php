<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    public function users()
    {
        return $this->belongsToMany('App\User', 'role_user');
    }

    public function permissions()
    {
        return $this->belongsToMany('App\Permission', 'permission_role');
    }

    public function hasPermission($name, $require = FALSE)
    {
        if (is_array($name)) {
            foreach ($permission as $permissionName) {
                $hasPermission = $this->hasPermission($permissionName);
                if ($hasPermission && !$require) {
                    return TRUE;
                }
                elseif (!$hasPermission && $require) {
                    return FALSE;
                }    
            }
            return $require;
        } else {
           foreach ($this->permissions as $permission) {
                if ($permission->name == $name) {
                    return TRUE;
                }   
           }
        }
        return FALSE;
    }

    public function savePermissions($inputPermissions)
    {
    	if (!empty($inputPermissions)) {
    		$this->permissions()->sync($inputPermissions);
    	} else {
    		$this->permissions()->detach();
    	}

    	return TRUE;
    }
}
