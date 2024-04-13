<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Roles\RoleStoreRequest;
use App\Http\Requests\Admin\Roles\RoleUpdateRequest;
use App\Repositories\Interfaces\Role\RoleInterface;
use App\Repositories\Interfaces\PermissionInterface;
use App\Models\Permission;
use App\Models\Role;

class RoleController extends Controller
{
    protected $roles;
    protected $permissions;

    public function __construct(RoleInterface $roles, PermissionInterface $permissions)
    {
        $this->roles=$roles;
        $this->permissions=$permissions;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!hasPermission('role_read')):
            return view('errors.403');
        endif;
        $roles = $this->roles->paginate(\Config::get('greenx.paginate'));
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!hasPermission('role_create')):
            return view('errors.403');
        endif;
        $permissions = $this->permissions->all();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleStoreRequest $request)
    {
        if(!hasPermission('role_create')):
            return view('errors.403');
        endif;

        $role = $this->roles->store($request->all());

        return redirect()->route('roles.index')->with('success', __('created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!hasPermission('role_update')):
            return view('errors.403');
        endif;
        $permissions = $this->permissions->all();
        $role = $this->roles->get($id);
        return view('admin.roles.edit', compact('permissions', 'role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleUpdateRequest $request, $id)
    {
        if(!hasPermission('role_update')):
            return view('errors.403');
        endif;
        $this->roles->update($id, $request->all());
        return redirect()->route('roles.index')->with('success', __('updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if(!hasPermission('role_delete')):
            return view('errors.403');
        endif;
        $this->roles->delete($id);
        $success = __('deleted_successfully');
        return response()->json($success);
    }
}
