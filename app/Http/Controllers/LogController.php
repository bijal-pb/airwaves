<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use App\Models\ApiLog;
use DB;
use Hash;
use DataTables;
use Auth;

class LogController extends Controller
{
    //
    
    public function logs(Request $request)
    {
        return view('admin.logs.index');
    }
    
    
    public function delete_all_log()
    {
        ApiLog::truncate();
        return redirect()->route('apilog.index')->with('message','All Api Log deleted successfully');
    }
}
