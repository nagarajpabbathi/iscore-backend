<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Likes;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = Post::with('category')
            ->when(isset($request->name), function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->name}%");
            })
            ->when(isset($request->status), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when(isset($request->category), function ($q) use ($request) {
                $q->where('category_id', $request->category);
            })
            ->when(isset($request->type), function ($q) use ($request) {
                $q->where('type', $request->type);
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        $cats = Category::where('status', 1)->pluck('name', 'id');

        return view('post.index', compact('posts', 'cats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = Category::where('status', 1)
            ->pluck('name', 'id');

        return view('post.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            'title' => 'required|unique:posts',
            'location' => 'required',
            'description' => 'required|max:500',
            'file' => 'required',
            'status' => 'required',
            'type' => 'required',
            'html' => 'nullable',
            'category_id' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,bmp,gif,svg,webp'
        ]);

        $input['user_id'] = auth()->id();
        $input['filename'] = uploadImage($request->file, 'posts');

        if ($request->hasFile('image')) {

            $input['image'] = uploadImage($request->image, 'image');
        }

        Post::create($input);

        return redirect()
            ->route('post')
            ->with('success', 'Post Created SuccessFully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);

        $category = Category::where('status', 1)
            ->pluck('name', 'id');

        return view('post.edit', compact('post', 'category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->validate([
            'title' => 'required|unique:posts,title,' . $id,
            'location' => 'required',
            'description' => 'required|max:500',
            'file' => 'nullable',
            'status' => 'required',
            'type' => 'required',
            'html' => 'nullable',
            'category_id' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,bmp,gif,svg,webp'
        ]);

        $post = Post::find($id);

        if ($request->hasFile('file')) {

            $input['filename'] = uploadImage($request->file, 'posts');

            deleteImage($post->filename);
        }

        if ($request->hasFile('image')) {

            $input['image'] = uploadImage($request->image, 'image');
        } else {

            unset($input['image']);
        }

        unset($input['file']);

        Post::where('id', $id)->update($input);

        return redirect()
            ->route('post')
            ->with('success', 'Post Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        deleteImage($post->filename);

        Post::destroy($id);

        return redirect()
            ->route('post')
            ->with('success', 'Post deleted successfully.');
    }

    public function postList(Request $request)
    {
        $data = Post::with('user')
            ->select(
                'id',
                'user_id',
                'id as like_status',
                'description',
                'title',
                'type',
                'filename',
                'likes',
                'created_at',
                'image'
            )
            ->when(isset($request->category), function ($q) use ($request) {
                $q->where('category_id', $request->category);
            })
            ->when(isset($request->title), function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->title}%");
            })
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return response()->json([
            'data'      => $data,
            'message'   => 'Posts List!',
            'response'  => true
        ], 200);
    }

    public function postPrediction(Request $request)
    {
        $data['post'] = Post::with('user')
            ->select(
                'id',
                'user_id',
                'title',
                'filename',
                'created_at',
                'image'
            )
            ->when(isset($request->category), function ($q) use ($request) {
                $q->where('category_id', $request->category);
            })
            ->when(isset($request->title), function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->title}%");
            })
            ->where('type', 'image')
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->paginate(10);

        $data['trending_categories'] = Category::select('id', 'name')
            ->where('status', 1)
            ->orderBy('likes', 'desc')
            ->limit(6)
            ->get();

        return response()->json([
            'data'      => $data,
            'message'   => 'Posts Prediction List!',
            'response'  => true
        ], 200);
    }

    public function postDetails($id)
    {
        $data['post'] = Post::with(['user', 'category'])
            ->select(
                'id',
                'user_id',
                'id as like_status',
                'title',
                'location',
                'description',
                'filename',
                'html',
                'likes',
                'created_at',
                'updated_at',
                'type',
                'category_id',
                'image'
            )
            ->where('id', $id)
            ->first();

        $data['related_articles'] = Post::select('id', 'title')
            ->where('category_id', $data['post']['category_id'])
            ->where('id', '!=', $data['post']['id'])
            ->where('status', 1)
            ->latest()
            ->limit(2)
            ->get();

        $data['trending_categories'] = Category::select('id', 'name')
            ->where('id', '!=', $data['post']['category_id'])
            ->where('status', 1)
            ->orderBy('likes', 'desc')
            ->limit(6)
            ->get();

        return response()->json([
            'data'      => $data,
            'message'   => 'Posts In Details!',
            'response'  => true
        ], 200);
    }

    public function likes(Request $request)
    {
        $v = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id',
        ]);

        if ($v->fails()) {

            return response()->json([
                'message' => $v->errors()->first(),
                'errors' => $v->errors(),
                'response'  => false
            ], 422);
        }

        if (Likes::where('post_id', $request->post_id)
            ->where('ip', $request->ip())
            ->exists()
        ) {

            Likes::where('post_id', $request->post_id)
                ->where('ip', $request->ip())
                ->delete();

            $post = Post::where('id', $request->post_id)->first();

            $post->decrement('likes', 1);

            Category::where('id', $post->category_id)
                ->decrement('likes', 1);
        } else {

            Likes::create([
                'post_id' => $request->post_id,
                'ip' => $request->ip()
            ]);

            $post = Post::where('id', $request->post_id)->first();

            $post->increment('likes', 1);

            Category::where('id', $post->category_id)
                ->increment('likes', 1);
        }

        $res['likes'] = Post::where('id', $request->post_id)->value('likes');

        return response()->json([
            'data'      => $res,
            'message'   => 'Posts Likes!',
            'response'  => true
        ], 200);
    }
}
