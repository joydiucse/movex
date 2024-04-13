<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account\CompanyAccount;
use App\Models\Hub;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\UserInterface;
use App\Repositories\Interfaces\PermissionInterface;
use App\Repositories\Interfaces\Role\RoleInterface;
use App\Models\Role;
use App\Http\Requests\Admin\Users\UserStoreRequest;
use App\Http\Requests\Admin\Users\UserUpdateRequest;
use Image;
use App\Models\Image as ImageModel;
use App\Models\User;
use App\Models\LogActivity;
use Cartalyst\Sentinel\Laravel\Facades\Activation;


class UserController extends Controller
{
    protected $users;
    protected $roles;
    protected $permissions;

    public function __construct(UserInterface $users, RoleInterface $roles, PermissionInterface $permissions)
    {
        $this->users           = $users;
        $this->roles           = $roles;
        $this->permissions     = $permissions;

    }

    public function index()
    {
        $users = $this->users->paginate(\Config::get('greenx.paginate'));
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = $this->roles->all()->where('id', '!=', 1);
        $hubs  = Hub::all();
        $permissions = $this->permissions->all();
        return view('admin.users.create', compact('roles', 'permissions','hubs'));
    }

    public function changeRole(Request $request)
    {
        $role_permissions = $this->roles->get($request->role_id)->permissions;
        $permissions = $this->permissions->all();
        return view('admin.users.permissions', compact('permissions', 'role_permissions'))->render();
    }

    public function store(UserStoreRequest $request)
    {
       if($this->users->store($request)):
            return redirect()->route('users')->with('success', __('created_successfully'));
       else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
       endif;
    }

    public function edit($id)
    {
        if ($id == '1'):
            return back()->with('danger', __('access_denied'));
        endif;
        $user = $this->users->get($id);
        $roles = $this->roles->all()->where('id', '!=', 1);
        $hubs  = Hub::all();
        $role_permissions = $user->permissions;
        $permissions = $this->permissions->all();
        return view('admin.users.edit', compact('user', 'roles', 'permissions', 'role_permissions','hubs'));

    }

    public function update(UserUpdateRequest $request)
    {
        if ($request->id == '1'):
            return back()->with('danger', __('access_denied'));
        endif;
        $hub = Hub::where('user_id', $request->id)->first();

        if (isset($hub) && $request->hub != $hub->id):
            return back()->with('danger', __('this_user_is_in_charge_of_another_hub'));
        endif;

        if($this->users->update($request)):
            return redirect()->route('users')->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;

    }

    public function delete($id)
    {
        if($this->users->delete($id)):
            $success[0] = __('deleted_successfully');
            $success[1] = 'success';
            $success[2] = __('deleted');
            return response()->json($success);
        else:
            $success[0] = __('something_went_wrong_please_try_again');
            $success[1] = 'error';
            $success[2] = __('oops');
            return response()->json($success);
        endif;

    }

    public function statusChange(Request $request)
    {
        if($this->users->statusChange($request['data'])):
            $success = __('updated_successfully');
            return response()->json($success);
        else:
            $success = __('something_went_wrong_please_try_again');
            return response()->json($success);
        endif;

    }

    public function personalInfo($id)
    {
        $user = $this->users->get($id);
        return view('admin.users.details.personal-info', compact('user'));
    }

    public function accountActivity($id)
    {
        $login_activities = LogActivity::where('user_id', $id)->orderBy('id', 'desc')->limit(20)->get();
        $user = $this->users->get($id);
        return view('admin.users.details.account-activity', compact('login_activities', 'user'));
    }

    public function paymentLogs($id)
    {
        $statements = CompanyAccount::orderby('id', 'desc')->where('user_id', $id)->paginate(\Config::get('greenx.paginate'));
        $user = $this->users->get($id);
        return view('admin.users.details.payment-logs', compact('statements', 'user'));
    }

    public function staffAccounts($id)
    {
        $user = $this->users->get($id);
        $accounts = $user->accounts($id);
        return view('admin.users.details.accounts', compact('user','accounts'));
    }

    public function logoutUserDevices($id)
    {
        $user = \Sentinel::findById($id);

        if(\Sentinel::logout($user, true)):
            $success[0] = __('logout_successfully');
            $success[1] = 'success';
            $success[2] = __('logout');
            return response()->json($success);
        else:
            $success[0] = __('something_went_wrong_please_try_again');
            $success[1] = 'error';
            $success[2] = __('oops');
            return response()->json($success);
        endif;
    }

}
