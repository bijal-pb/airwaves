<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Spatie\Permission\Models\Role;

use DB;
use Hash;
use DataTables;
use Illuminate\Support\Arr;
use Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.posts.index');
    }

    public function detail($id)
    {
        $posts = Post::with(['getUser','media','postComments'])->where('id',$id)->first();
    
        return view('admin.posts.detail',compact('posts'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
    
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
    
        $user->assignRole($request->input('roles'));
     //   notify()->success('User updated successfully');
        return redirect()->route('users.index')->with('message','User updated Successfully');
                    //    ->with('success','User updated successfully');
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();
        //notify()->success('Content deleted successfully');
        // return redirect()->back();
        return redirect()->route('livewire.post')
                         ->with('success','Post deleted successfully');
        //return response()->json(['success'=>'User deleted successfully.']);
    }
}
