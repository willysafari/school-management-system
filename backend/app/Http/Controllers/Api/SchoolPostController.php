<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolCategory;
use App\Models\SchoolPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class SchoolPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get all data
        $posts = SchoolPost::get();
        return response()->json([
            'status' => 'success',
            'message' => 'all post  retrieved',
            'data' => $posts,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate data
        $validationdata = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:school_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:school_posts,slug|max:255|regex:/^[a-z0-9-]+$/',
            'content' => 'required|string',
            'excerpt' => 'required|string|max:500', // Adjust max as needed
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,archieved',
            'published_at' => 'required|date'
        ]);

        if ($validationdata->fails()) {
            return response()->json([
                'status' => 'failed',
                'error' => $validationdata->errors(),
            ]);
        }

        $data = $request->all();

        $imagePath = null;

        if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isvalid()) {
            $file = $request->file('thumbnail');
            $fileName = time() . '_' . $file->getClientOriginalName();

            // move the file to public directory
            $file->move(public_path('storage/blogpostimages'), $fileName);

            // save the relative path to database
            $imagePath = "storage/blogpostimages/" . $fileName;
        }

        $data['thumbnail'] = $imagePath;

        SchoolPost::create($data);
        return response()->json([
            'status' => 'success',
            'message' => 'post Created successfully',
            'data' => $data,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //id of post

        $validationdata = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:school_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:school_posts,slug|max:255|regex:/^[a-z0-9-]+$/',
            'content' => 'required|string',
            'excerpt' => 'required|string|max:500', // Adjust max as needed
            // 'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,archieved',
            'published_at' => 'required|date'
        ]);


        if ($validationdata->fails()) {
            return response()->json([
                'status' => 'failed',
                'error' => $validationdata->errors(),
            ]);
        }

        $posts = SchoolPost::find($id);
        if (!$posts) {
            return response()->json([
                'status' => 'failed',
                'error' => 'posts not found',
            ]);
        }
        //    user of post
        $logined = Auth::user();

        if ($logined->id != $request->user_id) {
            return response()->json([
                'status' => 'failed',
                'error' => 'You are not own of the post',
            ]);
        }

        $category = SchoolCategory::find($request->category_id);

        if (!$category) {
            return response()->json([
                'status' => 'failed',
                'error' => 'Category not found',
            ]);
        }

        $data = $request->all();

        SchoolPost::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data update successfully',
            'data' => $data,
        ]);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $blogPost = SchoolPost::find($id);

        if (!$blogPost) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Post not found',
            ], 400);
        }

        $blogPost->delete();



        return response()->json([
            'status' => 'succcess',
            'message' => 'Post Delete successfully',

        ], 200);

    }
    public function blogPostImage(Request $request, int $id)
    {
        $blogPost = SchoolPost::find($id);

        if (!$blogPost) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Post not found',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'thumbnail' => 'required|image|mimes:png,jpeg,gif,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'error' => $validator->errors(),

            ]);
        }


        $loginedInUser = Auth::user();

        if ($loginedInUser->id != $request->user_id) {
            return response()->json([
                'status' => 'fail',
                'message' => 'UnAuthorized Access',

            ], 400);
        }

        $imagePath = null;

        if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isvalid()) {
            $file = $request->file('thumbnail');
            $fileName = time() . '_' . $file->getClientOriginalName();

            // move the file to public directory
            $file->move(public_path('storage/blogpostimages'), $fileName);

            // save the relative path to database
            $imagePath = "storage/blogpostimages/" . $fileName;
        }

        $blogPost->thumbnail = isset($imagePath) ? $imagePath : $blogPost->thumbnail;
        $blogPost->save();


        return response()->json([
            'status' => 'succcess',
            'message' => 'image updates successfully',

        ], 200);

    }
}
