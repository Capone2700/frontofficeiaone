<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Image;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function detail($id){
        $posts = Post::findOrFail($id);
        return view('detail', [
            'post'=>$posts
        ]);
    }
    
    public function store(Request $request){
        $request->validate([
            'titre'=>['required', 'max:255', 'min:10', 'unique:posts'],
            'description'=>['required',  'min:10', 'unique:posts'],
            'detail'=>['required', 'min:10', 'unique:posts'],
            'updated_at'=>['required'],
            'created_at'=>['required']
        ]);

        $filename=time().'.'.$request->file('avatar')->extension(); 
            $path = $request->file('avatar')->storeAs(
                'avatars',
                $filename,
                'public'
            );

        $posts = Post::create([
            'titre'=>$request->titre,
            'description'=>$request->description,
            'detail'=>$request->detail,
            'updated_at'=>$request->updated_at,
            'created_at'=>$request->created_at
        ]);

        $image = new Image(); 
        $image->path = $path;

        $posts->image()->save($image);

        return redirect(route("posts.index"));
    }
}
