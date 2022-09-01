<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;


class GroupController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.group.index');
    }

    public function detail($id)
    {
        $groups = Group::with(['created_user','GroupUser','genres'])->where('id',$id)->first();
        return view('admin.group.detail',compact('groups'));
    }

    public function destroy($id)
    {
        $group = Group::find($id);
        $group->delete();
        //notify()->success('Content deleted successfully');
        // return redirect()->back();
        return redirect()->route('livewire.post')
                         ->with('success','Group deleted successfully');
        //return response()->json(['success'=>'User deleted successfully.']);
    }
}
