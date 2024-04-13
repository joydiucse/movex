<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThirdPartyRequest;
use App\Repositories\Interfaces\Admin\ThirdPartyInterface;
use Illuminate\Http\Request;

class ThirdPartyController extends Controller
{
    protected $third_parties;

    public function __construct(ThirdPartyInterface $third_parties)
    {
        $this->third_parties = $third_parties;
    }

    public function index()
    {
        $third_parties = $this->third_parties->paginate();

        return view('admin.third-parties.index', compact('third_parties'));
    }

    public function create()
    {
        return view('admin.third-parties.create');
    }

    public function edit($id)
    {
        $third_party    = $this->third_parties->get($id);

        return view('admin.third-parties.edit', compact( 'third_party'));
    }

    public function store(ThirdPartyRequest $request)
    {
        if ($this->third_parties->store($request)):
            return redirect()->route('admin.third-parties')->with('success', __('created_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function update(ThirdPartyRequest $request)
    {
        if($this->third_parties->update($request)):
            return redirect()->route('admin.third-parties')->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function delete($id)
    {
        if($this->third_parties->delete($id)):
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

    public function changeStatus(Request $request)
    {
        if($this->third_parties->changeStatus($request['data'])):
            $success = __('updated_successfully');
            return response()->json($success);
        else:
            $success = __('something_went_wrong_please_try_again');
            return response()->json($success);
        endif;

    }
}
