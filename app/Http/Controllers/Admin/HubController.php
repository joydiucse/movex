<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HubRequest;
use App\Http\Requests\Admin\HubUpdateRequest;
use App\Models\Hub;
use App\Repositories\Interfaces\HubInterface;
use Illuminate\Http\Request;

class HubController extends Controller
{
    protected $hubs;

    public function __construct(HubInterface $hubs){
        $this->hubs  = $hubs;
    }

    public function index()
    {
        $hubs = $this->hubs->paginate();
        return view('admin.hub.index', compact('hubs'));
    }

    public function create()
    {
        $users    = $this->hubs->allUsers()->where('user_type', 'staff');
        return view('admin.hub.create', compact('users'));
    }

    public function store(HubRequest $request)
    {
        if ($this->hubs->store($request)):
            return redirect()->route('admin.hub')->with('success', __('created_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function edit($id)
    {
        $hub    = $this->hubs->get($id);
        $users  = $this->hubs->allUsers()->where('user_type', 'staff');

        return view('admin.hub.edit', compact('users', 'hub'));
    }

    public function update(HubUpdateRequest $request)
    {
        if($this->hubs->update($request)):
            return redirect()->route('admin.hub')->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function delete($id)
    {
        if($this->hubs->delete($id)):
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
}
