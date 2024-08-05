<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware(['auth:api', 'isAdmin'])->only(
            'index',
            'store',
            'update',
            'destroy',
            'show'
        );
    }

    public function index()
    {
        $roles = Roles::all();

        return response()->json(
            [
                'success' => true,
                'message' => 'Berhasil Menampilkan Data Role',
                'data' => $roles,
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
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //save to database
        $role = Roles::create([
            'name' => $request->name,
        ]);

        //success save to database
        if ($role) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Data Role Berhasil Ditambahkan',
                    'data' => $role,
                ],
                201
            );
        } else {
            //failed save to database
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Role Gagal Ditambahkan',
                ],
                409
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Roles::find($id);

        if (!$role) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'ID Role tidak Ditemukan',
                    'data' => $id,
                ],
                404
            );
        }
        return response()->json([
            'success' => true,
            'message' => 'Berhasil Menampilkan Data Role',
            'data' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role = Roles::find($id);

        if (!$role) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'ID Genre tidak Ditemukan',
                    'data' => $id,
                ],
                404
            );
        }

        $role->name = $request['name'];
        $role->save();

        return response()->json(
            [
                'success' => true,
                'message' =>
                    'Data Role Berhasil Melakukan Update pada ID :' . $id,
            ],
            201
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Roles::find($id);

        if (!$role) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'ID Role tidak Ditemukan',
                    'data' => $id,
                ],
                404
            );
        }

        $role->delete();

        return response()->json(
            [
                'success' => true,
                'message' => 'Data Role Berhasil Dihapus',
            ],
            200
        );
    }
}
