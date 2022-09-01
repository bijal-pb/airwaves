<?php
    
namespace App\Http\Controllers;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostLike;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use DataTables;
use Illuminate\Support\Arr;
use Auth;
    
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.users.index');
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('name','!=','developer')->pluck('name','name')->all();
        return view('admin.users.create',compact('roles'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
    
        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        // notify()->success('User created successfully!');
        
        return view('admin.users.index')->with('message','User added Successfully');
                      //  ->with('success','User created successfully');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('admin.users.show',compact('user'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::where('name','!=','developer')->pluck('name','name')->all();
        $userRole = $user->roles->pluck('name')->all();
    
        return view('admin.users.edit',compact('user','roles','userRole'));
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
            // 'name' => 'required',
            // 'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'status' => 'required',
           
            // 'roles' => 'required'
        ]);
    
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
     
        $user = User::find($id);
        $user->status = $request->status;
        $user->update($input);
        $user->tokens()->delete();
        // DB::table('model_has_roles')->where('model_id',$id)->delete();
    
        // $user->assignRole($request->input('roles'));
     //   notify()->success('User updated successfully');
        return redirect()->route('users.index')->with('message','User updated Successfully');
                    //    ->with('success','User updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        notify()->success('User deleted successfully');
        // return redirect()->back();
        return redirect()->route('admin.users.index')
                         ->with('success','User deleted successfully');
        //return response()->json(['success'=>'User deleted successfully.']);
    }
    
    public function dashboard()
    {
        $users = User::whereHas('roles', function($q){
                                $q->whereIn('name', ['user']);
                            })->count();
        
        //$year = ['Jan','Feb','March'];
        $cy = date("Y"); 
        $year = [$cy.'-01',$cy.'-02',$cy.'-03',$cy.'-04',$cy.'-05',$cy.'-06',$cy.'-07',$cy.'-08',$cy.'-09',$cy.'-10',$cy.'-11',$cy.'-12'];
        $user = [];
        foreach ($year as $key => $value) {
            $user[] = User::whereHas('roles', function($q){
                            $q->whereIn('name', ['user']);
                            })->where(\DB::raw("DATE_FORMAT(created_at, '%Y-%m')"),$value)->count();
        }
        $post = Post::count();

        $comment = PostComment::count();
        
        $like = PostLike::where('like',1)->count();
        $dis_like = PostLike::where('like',2)->count();
        return view('admin.dashboard',compact('users','post','comment','like','dis_like'))->with('year',json_encode($year,JSON_NUMERIC_CHECK))->with('user',json_encode($user,JSON_NUMERIC_CHECK));
    }

}