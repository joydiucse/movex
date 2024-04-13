<?php

namespace App\Repositories\Role;
use App\Models\Role;
use App\Repositories\Interfaces\Role\RoleInterface;

class RoleRepository implements RoleInterface {
    
    public function all()
    {
        return Role::get();
    }

    public function paginate($limit)
    {
        return Role::paginate($limit);
    }

    public function get($id)
    {
        return Role::findOrFail($id);
    }

    public function store(array $data)
    {
        $role                 = new Role();
        return $this->save($role, $data);
    }

    public function update($id, array $data)
    {
        $role                 = $this->get($id);
        return $this->save($role, $data);
    }

    public function delete($id)
    {
        $role = Role::find($id);
        return $role->delete();
    }

    public function save($role, $data)
    {
        // for new add and update
        $role->name           = $data['name'];
        if ($data['slug'] != null) :
            $role->slug       = $data['slug'];
        else :
            $role->slug       = \Str::slug($data['name'], '-');
        endif;
        $role->permissions    = $data['permissions'] ?? [];
        return $role->save();
    }
}
