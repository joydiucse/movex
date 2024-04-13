<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ParcelsImport;
use File;
use Response;
use Sentinel;

class ImportExportController extends Controller
{
    public function importExportView()
    {
        return view('admin.bulk.import');
    }
    public function export(){
        $filename = (Sentinel::getUser()->user_type == 'merchant' || Sentinel::getUser()->user_type == 'merchant_staff') ? 'admin/excel/merchant-parcel-import-sample.xlsx' : 'admin/excel/staff-parcel-import-sample.xlsx';
        if (file_exists(public_path($filename))):
            $filepath = public_path($filename);
            return Response::download($filepath);
        else:
            return back()->with('danger',__('file_not_found'));
        endif;
    }
    public function import()
    {
        $extension = request()->file('file')->getClientOriginalExtension();

        if ($extension != 'xlsx' && $extension != 'csv'):
            return back()->with('danger', __('file_type_not_supported'));
        endif;

        $file = request()->file('file')->store('import');
        $import = new ParcelsImport();
        $import->import($file);

        unlink(storage_path('app/'.$file));

        return back()->with('success',__('successfully_imported'));
    }
}
