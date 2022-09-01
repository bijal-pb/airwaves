<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Setting;
use App\Models\MatchUser;
use App\Models\Post;
use App\Models\PostMedia;
use App\Models\PostComment;
use App\Models\PostLike;
use App\Models\PostReport;
use App\Models\SavePost;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PostResource;
use VideoThumbnail;
use FFMpeg;



class PostController extends BaseController
{
    /**
	 *  @OA\Post(
	 *     path="/api/create-post",
	 *     tags={"create post"},
	 *     summary="Create Post",
	 *     security={{"bearer_token":{}}},
	 *     operationId="create-post",
	 * 
	 *     @OA\Parameter(
	 *         name="message",
	 *         in="query",
	 *         @OA\Schema(
	 *             type="text"
	 *         )
	 *     ),
     *     @OA\Parameter(
	 *         name="privacy",
	 *         in="query",
	 * 	 	   description="1-local & match | 2- local | 3- match",				
	 *         required=true,
	 *         @OA\Schema(
	 *             type="integer"
	 *         )
	 *     ),   
     *     @OA\Parameter(
     *         name="tracks",
     *         in="query",
     *         description="tracks list",
     *         @OA\Schema(
     *           type="string",
     *           @OA\Items(type="string"),
     *         ),
     *     ),
     *    @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="images[]",
     *                      description="images",
     *                      type="array",
     *                      @OA\Items(type="string", format="binary")
     *                   ),
     *                   @OA\Property(
     *                      property="videos[]",
     *                      description="videos",
     *                      type="array",
     *                      @OA\Items(type="string", format="binary")
     *                   ),
     *               ),
     *           ),
     *       ),
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  
     *               ),
     *           ),
     *       ),
     *
	 *     @OA\Response(
	 *         response=200,
	 *         description="Success",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=401,
	 *         description="Unauthorized"
	 *     ),
	 *     @OA\Response(
	 *         response=400,
	 *         description="Invalid request"
	 *     ),
	 *     @OA\Response(
	 *         response=404,
	 *         description="not found"
	 *     ),
	 * )
	**/
    public function create_post(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'images.*' => 'nullable|mimes:jpeg,jpg,png,gif',
            'videos.*' => 'nullable|mimes:mp4,ogx,oga,ogv,ogg,webm,avi,mov',
            'privacy' => 'required|in:1,2,3'
        ]);
        if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
        }
        DB::beginTransaction();
        try{
            $post = new Post;
            $post->user_id = Auth::id();
            $post->message = $request->message;
            $post->privacy = $request->privacy;
            $post->save();
            if($request->hasfile('images')) {
                foreach($request->file('images') as $file)
                {
                    $filename = time().$file->getClientOriginalName();
                    $file->move(public_path().'/images/', $filename);
                    $pm = new PostMedia;
                    $pm->user_id = Auth::id();
                    $pm->post_id = $post->id;
                    $pm->media = $filename;
                    $pm->type = 'image';
                    $pm->save();
                }
            }
            if($request->hasfile('videos')) {
                foreach($request->file('videos') as $file)
                {
                    $filename = time().$file->getClientOriginalName();
                    $file->move(public_path().'/videos/', $filename);
                    $pm = new PostMedia;
                    $pm->user_id = Auth::id();
                    $pm->post_id = $post->id;
                    $pm->media = $filename;
                    $pm->type = 'video';
                    $pm->save();

                    // $thumbName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
                    $thumbName = $filename;
                    VideoThumbnail::createThumbnail(public_path().'/videos/'. $filename, public_path().'/thumbs/', $thumbName.'.jpg', 2, 1920, 1080);
                    // $thumb = FFMpeg::fromDisk('local_public')
                    //     ->open('/videos/'. $filename)
                    //     ->getFrameFromSeconds(10)
                    //     ->export()
                    //     ->toDisk('local_public')
                    //     ->save('/thumbs/'.$thumbName.'.jpg');

                    // return $this->sendResponse($thumb, 'Posted successfully!.');
                }
            }
            // if(is_array($request->tracks))
            // {
            //     foreach($request->tracks as $track)
            //     {
            //         $pm = new PostMedia;
            //         $pm->user_id = Auth::id();
            //         $pm->post_id = $post->id;
            //         $pm->media = $track;
            //         $pm->type = 'track';
            //         $pm->save(); 
            //     }
            // }
            // return $request;
            if($request->tracks != null)
            {
                $tracks = json_decode($request->tracks);
                foreach($tracks as $track)
                {
                    $pm = new PostMedia;
                    $pm->user_id = Auth::id();
                    $pm->post_id = $post->id;
                    $pm->media = $track;
                    $pm->type = 'track';
                    $pm->save(); 
                }
            }
            DB::commit();
            $post->is_like = $this->check_like($request->post_id);  
            $post->is_save = $this->check_save($request->post_id);
            $data =  new PostResource($post);
            return $this->sendResponse($data, 'Posted successfully!.');
        }catch(Exception $e)
        {
            DB::rollBack();
            return $this->sendError('Something went wrong, Please try again!.',$e,200);
        }
    }
    /**
     *  @OA\Get(
     *     path="/api/get-post",
     *     tags={"get post"},
     *     summary="get post list",
     *     security={{"bearer_token":{}}},
     *     operationId="get post",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     * )
     **/
    public function get_post(Request $request)
    {
        $posts = Post::with(['media','postComments'])
                ->where('user_id',Auth::id())
                ->orderBy('id','desc')
                ->paginate(10);
        foreach($posts as $p)
        {
            $p->is_like = $this->check_like($p->id); 
            $p->is_save = $this->check_save($p->id);   
        }
        $data =  PostResource::collection($posts);
        return $this->sendResponse($data, 'Post list.');
    }
    /**
     *  @OA\Get(
     *     path="/api/user-post",
     *     tags={"user post"},
     *     summary="get user post list",
     *     security={{"bearer_token":{}}},
     *     operationId="get user post",
     *    
     *     @OA\Parameter(
	 *         name="user_id",
	 *         in="query",
	 *         @OA\Schema(
	 *             type="integer"
	 *         )
	 *     ), 
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     * )
     **/
    public function user_post(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'user_id' => 'required',
        ]);
        if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
        }   
        $posts = Post::with(['media','postComments'])
                ->where('user_id',$request->user_id)
                ->orderBy('id','desc')
                ->paginate(10);
        foreach($posts as $p)
        {
            $p->is_like = $this->check_like($p->id); 
            $p->is_save = $this->check_save($p->id);   
        }
        $data =  PostResource::collection($posts);
        return $this->sendResponse($data, 'Post list.');
    }
    /**
     *  @OA\Get(
     *     path="/api/home",
     *     tags={"home"},
     *     summary="Home post list",
     *     security={{"bearer_token":{}}},
     *     operationId="home",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     * )
     **/
    public function home(Request $request)
    {
        $user = User::find(Auth::id());
        $latitude = $user->latitude;
		$longitude = $user->longitude;
		$setting = Setting::latest()->first();
		$distance = $setting->distance;

        $match_users = MatchUser::select('match_users.user_id2')
                        ->where('match_users.user_id1',Auth::id())
                        ->where('match_users.status',2);
        
        $match_user2 = MatchUser::select('match_users.user_id1')
                        ->where('match_users.user_id2',Auth::id())
                        ->where('match_users.status',2);

        $matchs = $match_user2->union($match_users)->pluck('user_id1')->toArray();

        $explores = User::select(DB::Raw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) AS distance"),'users.*')
                    ->whereRaw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                    ->where('id','!=',Auth::id())
                    ->whereNotIn('users.id',$matchs)
                    ->orderBy('distance','asc')
                    ->pluck('id')->toArray();

        // return $explores;
        $users = array_unique (array_merge($matchs,$explores));
        
        // $posts = Post::with(['postBy','media','postComments'])
        //         ->where('user_id',Auth::id())
        //         ->orWhereIn('user_id',$users)
        //         ->orderBy('id','desc')
        //         ->paginate(10);

        $posts = Post::with(['media','postComments'])
                    ->where('user_id',Auth::id());
                    // ->orWhereIn('user_id',$users);

        $posts = $posts->orWhere(function($q) use($explores){
                    return $q->whereIn('user_id',$explores)
                            ->where('privacy','!=',3);
                });
        $posts = $posts->orWhere(function($q) use($matchs){
                    return $q->whereIn('user_id',$matchs)
                            ->where('privacy','!=',2);
                });

        $posts = $posts->orderBy('id','desc')->paginate(10);
        foreach($posts as $p)
        {
            $p->is_like = $this->check_like($p->id);  
            $p->is_save = $this->check_save($p->id);
        }
        $posts = PostResource::collection($posts);
        return $this->sendResponse($posts, 'Home post data.');
    }

    public function check_like($post_id)
    {
        $check = PostLike::where('post_id',$post_id)->where('user_id',Auth::id())->first();
        if($check)
        {
           return $check->like;
        }else {
           return 3;
        }
    }
    public function check_save($post_id)
    {
        $check = SavePost::where('post_id',$post_id)->where('user_id',Auth::id())->first();
        if($check)
        {
           return 1;
        }else {
           return 2;
        }
    }
    /**
     *  @OA\Post(
     *     path="/api/get-post",
     *     tags={"get post"},
     *     summary="get particular post ",
     *     security={{"bearer_token":{}}},
     *     operationId="get-post",
     *     
     *    @OA\Parameter(
	 *         name="post_id",
	 *         in="query",
	 *         required=true,
	 * 		   description="post id",
	 *         @OA\Schema(
	 *             type="integer"
	 *         )
	 *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     * )
     **/
    public function get_post_by_id(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'post_id' => 'required',
        ]);
        if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
        }
        $post = Post::with(['media','postComments'])
                ->where('id',$request->post_id)
                ->first();
        $post->is_like = $this->check_like($request->post_id);
        $post->is_save = $this->check_save($request->post_id);  
        $data =  new PostResource($post);
        return $this->sendResponse($data, 'Get Particular post.');
    }
    /**
     *  @OA\Post(
     *     path="/api/get-save-post",
     *     tags={"get save posts"},
     *     summary="get save posts ",
     *     security={{"bearer_token":{}}},
     *     operationId="get-save-post",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     * )
     **/
    public function get_save_post(Request $request)
    {
        $post_ids = SavePost::where('user_id',Auth::id())->pluck('post_id');
        $posts = Post::with(['media','postComments'])
                ->whereIn('id',$post_ids)
                ->orderBy('id','desc')
                ->paginate(10);
                foreach($posts as $p)
                {
                    $p->is_like = $this->check_like($p->id);  
                    $p->is_save = $this->check_save($p->id);  
                }
        $posts = PostResource::collection($posts);
        return $this->sendResponse($posts, 'Save posts.');
    }
    /**
	 *  @OA\Post(
	 *     path="/api/post-like",
	 *     tags={"post like & unlike"},
	 *     summary="Post Like",
	 *     security={{"bearer_token":{}}},
	 *     operationId="post-like",
	 * 
	 *     @OA\Parameter(
	 *         name="post_id",
	 *         in="query",
	 *         required=true,
	 * 		   description="post id",
	 *         @OA\Schema(
	 *             type="integer"
	 *         )
	 *     ),
     *    @OA\Parameter(
	 *         name="like",
	 *         in="query",
	 *         required=true,
	 * 		   description="like - 1, unlike - 2, 3 - remove like or unlike",
	 *         @OA\Schema(
	 *             type="integer"
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Success",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=401,
	 *         description="Unauthorized"
	 *     ),
	 *     @OA\Response(
	 *         response=400,
	 *         description="Invalid request"
	 *     ),
	 *     @OA\Response(
	 *         response=404,
	 *         description="not found"
	 *     ),
	 * )
	**/
    public function post_like(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'post_id' => 'required',
            'like' => 'required|in:1,2,3',
        ]);
        if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
        }
        $login_user = User::find(Auth::id());
        $post_like = PostLike::where('user_id',Auth::id())->where('post_id',$request->post_id)->first();
        if(isset($post_like))
        {
            $data =  null;
            $post_like->like = $request->like;
            $post_like->save();
            if($request->like == 1)
            {
                $post =  Post::find($request->post_id);
                $user = User::find($post->user_id);
                if(Auth::id() != $post->user_id)
                {
                    sendPushNotification($user->device_token,$user->device_type,$login_user->name.' has liked your post',$login_user->name.' has liked your post',1,$post->user_id);
                }
                return $this->sendResponse($data, 'Like successfully!');
                
            }
            if($request->like == 2)
            {
                $post =  Post::find($request->post_id);
                $user = User::find($post->user_id);
                if(Auth::id() != $post->user_id)
                {
                    sendPushNotification($user->device_token,$user->device_type,$login_user->name.' has disliked your post',$login_user->name.' has disliked your post',1,$post->user_id);
                }
                return $this->sendResponse($data, 'Dislike successfully!');
            }
            if($request->like == 3)
            {
                return $this->sendResponse($data, 'Remove successfully!');
            }
        }
        $pl = new PostLike;
        $pl->user_id = Auth::id();
        $pl->post_id = $request->post_id;
        $pl->like = $request->like;
        $pl->save();
        $data =  null;
        if($request->like == 1)
        {
            $post =  Post::find($request->post_id);
            $user = User::find($post->user_id);
            if(Auth::id() != $post->user_id)
            {
                sendPushNotification($user->device_token,$user->device_type,$login_user->name.' has liked your post',$login_user->name.' has liked your post',1,$post->user_id);
            }
            return $this->sendResponse($data, 'Like successfully!');
        }
        if($request->like == 2)
        {
            $post =  Post::find($request->post_id);
            $user = User::find($post->user_id);
            if(Auth::id() != $post->user_id)
            {
                sendPushNotification($user->device_token,$user->device_type,$login_user->name.' has disliked your post',$login_user->name.' has disliked your post',1,$post->user_id);
            }
            return $this->sendResponse($data, 'Dislike successfully!');
        }
        if($request->like == 3)
        {
            return $this->sendResponse($data, 'Remove successfully!');
        }
    }
    /**
	 *  @OA\Post(
	 *     path="/api/post-comment",
	 *     tags={"post comment"},
	 *     summary="Post Comment",
	 *     security={{"bearer_token":{}}},
	 *     operationId="post-comment",
	 * 
	 *     @OA\Parameter(
	 *         name="post_id",
	 *         in="query",
	 *         required=true,
	 * 		   description="post id",
	 *         @OA\Schema(
	 *             type="integer"
	 *         )
	 *     ),
     *     @OA\Parameter(
	 *         name="comment_id",
	 *         in="query",
	 * 		   description="comment id",
	 *         @OA\Schema(
	 *             type="integer"
	 *         )
	 *     ),
     *    @OA\Parameter(
	 *         name="comment",
	 *         in="query",
	 *         required=true,
	 * 		   description="comment",
	 *         @OA\Schema(
	 *             type="string"
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Success",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=401,
	 *         description="Unauthorized"
	 *     ),
	 *     @OA\Response(
	 *         response=400,
	 *         description="Invalid request"
	 *     ),
	 *     @OA\Response(
	 *         response=404,
	 *         description="not found"
	 *     ),
	 * )
	**/
    public function post_comment(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'post_id' => 'required',
            'comment_id' => 'nullable|exists:post_comments,id',
            'comment' => 'required',
        ]);
        if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
        }
        $login_user = User::find(Auth::id());
        try {
            $post_comment = new PostComment;
            $post_comment->post_id = $request->post_id;
            $post_comment->user_id = Auth::id();
            $post_comment->comment_id = $request->comment_id;
            $post_comment->comment = $request->comment;
            $post_comment->save();
            $comment = PostComment::where('id',$post_comment->id)->with('commentBy')->first();
            $post =  Post::find($request->post_id);
            $user = User::find($post->user_id);
            if(Auth::id() != $post->user_id)
            {
                sendPushNotification($user->device_token,$user->device_type,$login_user->name.' has commented your post',$login_user->name.' has commented your post',1,$post->user_id);
            }
            if($comment->commentBy)
            {
                if($comment->commentBy->photo != null)
                {
                    $comment->commentBy->profile_photo = url('/uploads/'.$comment->commentBy->photo);
                }
            }
            return $this->sendResponse($comment, 'comment successfully!');
        } catch(Exception $e)
        {
            return $this->sendError('Something went wrong, Please try again!.',$e,200);
        }
    }
    /**
	 *  @OA\Post(
	 *     path="/api/post-save",
	 *     tags={"post save"},
	 *     summary="Post save",
	 *     security={{"bearer_token":{}}},
	 *     operationId="post-save",
	 * 
	 *     @OA\Parameter(
	 *         name="post_id",
	 *         in="query",
	 *         required=true,
	 * 		   description="post id",
	 *         @OA\Schema(
	 *             type="integer"
	 *         )
	 *     ),
     *    @OA\Parameter(
	 *         name="type",
	 *         in="query",
	 *         required=true,
	 * 		   description="1 - save  | 2 - remove",
	 *         @OA\Schema(
	 *             type="integer"
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Success",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=401,
	 *         description="Unauthorized"
	 *     ),
	 *     @OA\Response(
	 *         response=400,
	 *         description="Invalid request"
	 *     ),
	 *     @OA\Response(
	 *         response=404,
	 *         description="not found"
	 *     ),
	 * )
	**/
    public function post_save(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'post_id' => 'required',
            'type' => 'required|in:1,2'
        ]);
        if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
        }
        try {
            if($request->type == 1)
            {
                $post_save = SavePost::where('post_id',$request->post_id)->where('user_id',Auth::id())->first();
                if($post_save)
                {
                    return $this->sendResponse(null, 'Post is already save.');
                } else {
                    $post_save = new SavePost;
                    $post_save->post_id = $request->post_id;
                    $post_save->user_id = Auth::id();
                    $post_save->save();
                    return $this->sendResponse(null, 'Post saved successfully!');
                }
            }
            if($request->type == 2)
            {
                $post_save = SavePost::where('post_id',$request->post_id)->where('user_id',Auth::id())->first();
                if($post_save)
                {
                    $post_save->delete();
                    return $this->sendResponse(null, 'Post removed successfully!');    
                } else {
                    return $this->sendResponse(null, 'Not in save list this post!'); 
                }
            }
        } catch(Exception $e)
        {
            return $this->sendError('Something went wrong, Please try again!.',$e,200);
        }
    }

    /**
	 *  @OA\Post(
	 *     path="/api/delete-post",
	 *     tags={"post delete"},
	 *     summary="Post delete",
	 *     security={{"bearer_token":{}}},
	 *     operationId="post-delete",
	 * 
	 *     @OA\Parameter(
	 *         name="post_id",
	 *         in="query",
	 *         required=true,
	 * 		   description="post id",
	 *         @OA\Schema(
	 *             type="integer"
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Success",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=401,
	 *         description="Unauthorized"
	 *     ),
	 *     @OA\Response(
	 *         response=400,
	 *         description="Invalid request"
	 *     ),
	 *     @OA\Response(
	 *         response=404,
	 *         description="not found"
	 *     ),
	 * )
	**/
    public function delete_post(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'post_id' => 'required',
        ]);
        if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
        }
        try {
                $post_delete = Post::find($request->post_id);
                if($post_delete)
                {
                    $post_delete->delete();
                    return $this->sendResponse(null, 'Post deleted successfully!');
                }
                return $this->sendError('Enter valid post id!.','',200);
        } catch(Exception $e)
        {
            return $this->sendError('Something went wrong, Please try again!.',$e,200);
        }
    }
    /**
	 *  @OA\Post(
	 *     path="/api/report-post",
	 *     tags={"post report"},
	 *     summary="Post report",
	 *     security={{"bearer_token":{}}},
	 *     operationId="post-report",
	 * 
	 *     @OA\Parameter(
	 *         name="post_id",
	 *         in="query",
	 *         required=true,
	 * 		   description="post id",
	 *         @OA\Schema(
	 *             type="integer"
	 *         )
	 *     ),
     *     @OA\Parameter(
	 *         name="reason",
	 *         in="query",
     *         required=true,
	 * 		   description="reason",
	 *         @OA\Schema(
	 *             type="string"
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Success",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=401,
	 *         description="Unauthorized"
	 *     ),
	 *     @OA\Response(
	 *         response=400,
	 *         description="Invalid request"
	 *     ),
	 *     @OA\Response(
	 *         response=404,
	 *         description="not found"
	 *     ),
	 * )
	**/
    public function report_post(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'post_id' => 'required',
            'reason' => 'required',
        ]);
        if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
        }
        try {
                $post_reason = new PostReport;
                $post_reason->user_id = Auth::id();
                $post_reason->post_id = $request->post_id;
                $post_reason->reason = $request->reason;
                $post_reason->save();
                return $this->sendResponse($post_reason, 'Post reported successfully!');
        } catch(Exception $e)
        {
            return $this->sendError('Something went wrong, Please try again!.',$e,200);
        }
    }
}
