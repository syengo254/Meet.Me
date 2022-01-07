<?php

namespace App\Http\Controllers;

use App\Http\Resources\MyPostResource;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::check()){
            return response(["success" => 0, "message" => "Origin failed authentication"], 500);
        }

        $post_data = $request->validate([
            "post" => 'required|string|min:10',
            "has_image" => 'required',
        ]);

        $post = new Post();//Post::create($post_data + ['user_id' => $request->user()->id]);
        $post->post = $post_data["post"];

        if($post_data["has_image"] == "true"){
            $post->has_image = TRUE;
            //upload image here
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,bmp|max:1024',
            ]);
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('img/posts'), $imageName);
            $post->image_url = '/img/posts/'.$imageName;
        }

        $post->user_id = $request->user()->id;
        $post->save();

        if(!$post->id) return response(["message" => "post creation failed", "success" => 0]);

        $post = Post::where('id', $post->id)->first();

        return response(["message" => "post created", "success" => 1, "post" => $post]);
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

    public function latest(Request $request, $id){
        $post = Post::where('user_id', $id)->latest()->first();

        return $post;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $post = $request->validate([
            "post" => "required",
        ]);

        $ori_post = Post::find($id);
        $ori_post->update($post["post"]);

        return response(["success" => 1, "post" => $ori_post]);
    }

    /**
     * Fetch logged user posts only
     * 
     * @param int $id -- a user id
     */

     public function myPosts(Request $request){
        $data = $request->validate([
            "user_id" => 'required||integer|min:1|'
        ]);

        $user = User::find($data["user_id"]);

        if($user){
            return ["success" => 1, "posts" => MyPostResource::collection(Post::limit(5)->where('user_id', $user->id)->latest()->get())];
        }
        else{
            return ["success" => 0, "message" => "This user is invalid"];
        }
     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
