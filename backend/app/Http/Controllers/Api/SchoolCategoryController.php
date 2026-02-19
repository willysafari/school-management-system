<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SchoolCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // to get all

        $category = SchoolCategory::all();

        return response()->json([
            'status' => 'suceess',
            'data' => $category,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // add category

        $validatorData = Validator::make($request->all(), [
            'name' => 'required|String|max:255',

        ]);

        if ($validatorData->fails()) {
            return response()->json([
                'status' => 'success',
                'data' => $validatorData->errors(),
            ]);
        }

        $data['name'] = $request->name;
        $data['slug'] = Str::slug($request->name);


        SchoolCategory::create($data);


        return response()->json([
            'status' => 'success',
            'message' => 'category created successfully',
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
        //
        $categoryid = SchoolCategory::find($id);

        $validatorData = Validator::make($request->all(), [
            'name' => 'required|String|max:255',

        ]);

        if ($validatorData->fails()) {
            return response()->json([
                'status' => 'fail',
                'data' => $validatorData->errors(),
            ]);
        }
        if ($categoryid) {
            $data['name'] = $request->name;
            $data['slug'] = Str::slug($request->name);
            SchoolCategory::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'category updated successfully',
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'status' => 'fail',
                'message' => 'the category not found',
            ]);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $category = SchoolCategory::find($id);

        if ($category) {
            SchoolCategory::destroy($id);
              return response()->json([
                'status' => 'success',
                'message' => 'category deleted successfully',
            ]);
        } else {
            return response()->json([
                'status' => 'fail',
                'message' => 'category id not found',
            ],400);
        }

    }
}
