<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware(['auth:api', 'isAdmin'])->only(
            'store',
            'update',
            'destroy'
        );
    }

    public function index()
    {
        $category = Category::all();

        return response()->json(
            [
                'success' => true,
                'message' => 'Berhasil Menampilkan Data Category',
                'data' => $category,
            ],
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'img_link' => 'required|string',
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //save to database
        $category = Category::create([
            'name' => $request->name,
            'img_link' => $request->img_link,
        ]);

        //success save to database
        if ($category) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Data Category Berhasil Ditambahkan',
                    'data' => $category,
                ],
                201
            );
        }

        //failed save to database
        return response()->json(
            [
                'success' => false,
                'message' => 'Category Gagal Ditambahkan',
            ],
            409
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::with('listBook')->find($id);

        if (!$category) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'ID Category tidak Ditemukan',
                    'data' => $id,
                ],
                404
            );
        }
        return response()->json([
            'success' => true,
            'message' => 'Berhasil Menampilkan Data Category',
            'data' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'img_link' => 'required|string',
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category = Category::find($id);

        if (!$category) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'ID Category tidak Ditemukan',
                    'data' => $id,
                ],
                404
            );
        }

        $category->update([
            'name' => $request->name,
            'img_link' => $request->img_link,
        ]);

        return response()->json(
            [
                'success' => true,
                'message' =>
                    'Data Category Berhasil Melakukan Update pada ID :' . $id,
            ],
            201
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'ID Category tidak Ditemukan',
                    'data' => $id,
                ],
                404
            );
        }

        $category->delete();

        return response()->json(
            [
                'success' => true,
                'message' => 'Data Category Berhasil Dihapus',
            ],
            200
        );
    }
}
