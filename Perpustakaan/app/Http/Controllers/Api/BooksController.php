<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BooksRequest;
use App\Models\Books;
use Illuminate\Http\Request;
use Cloudinary\Cloudinary;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Api\ApiUtils;

class BooksController extends Controller
{
    protected $cloudinary;

    public function __construct()
    {
        $this->middleware(['auth:api', 'isAdmin'])->only(
            'store',
            'update',
            'destroy'
        );

        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key' => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Books::with('category', 'listBorrow')->get();

        return response()->json(
            [
                'success' => true,
                'message' => 'Berhasil Menampilkan Data Book',
                'data' => $books,
            ],
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BooksRequest $request)
    {
        $data = $request->validated();

        // jika file gambar diinput
        if ($request->hasFile('image')) {
            // Upload gambar ke Cloudinary
            $image = $request->file('image');
            $upload = $this->cloudinary
                ->uploadApi()
                ->upload($image->getRealPath());

            // Mendapatkan URL gambar dari Cloudinary
            $data['image'] = $upload['secure_url'];
        }

        $book = Books::create($data);

        return response()->json(
            [
                'success' => true,
                'message' => 'Berhasil Menambahkan Data Buku',
                'data' => $book,
            ],
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Books::with('category', 'listBorrow')->find($id);

        if (!$book) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'ID Buku tidak Ditemukan',
                    'data' => $id,
                ],
                404
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Menampilkan Data Buku',
            'data' => $book,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BooksRequest $request, string $id)
    {
        $data = $request->validated();

        $book = Books::find($id);

        if (!$book) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'ID Buku tidak Ditemukan',
                    'data' => $id,
                ],
                404
            );
        }

        if ($request->hasFile('image')) {
            // Hapus gambar lama dari Cloudinary jika ada
            if ($book->image) {
                // Extract public ID from URL
                $oldImagePublicId = $this->getPublicIdFromUrl($book->image);
                $this->cloudinary->uploadApi()->destroy($oldImagePublicId);
            }

            // Upload gambar baru ke Cloudinary
            $image = $request->file('image');
            $upload = $this->cloudinary
                ->uploadApi()
                ->upload($image->getRealPath());

            $data['image'] = $upload['secure_url'];
        }

        $book->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Mengupdate Data Buku dengan ID ' . $id,
            'data' => $book,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Books::find($id);

        if (!$book) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'ID Buku tidak Ditemukan',
                    'data' => $id,
                ],
                404
            );
        }

        // Hapus gambar dari Cloudinary jika ada
        if ($book->image) {
            $oldImagePublicId = $this->getPublicIdFromUrl($book->image);
            $this->cloudinary->uploadApi()->destroy($oldImagePublicId);
        }

        $book->delete();

        return response()->json(
            [
                'success' => true,
                'message' => 'Data Buku Berhasil Dihapus',
            ],
            200
        );
    }

    /**
     * Extracts the public ID from the Cloudinary image URL.
     *
     * @param string $url
     * @return string
     */
    private function getPublicIdFromUrl($url)
    {
        $parts = parse_url($url);
        $path = $parts['path'];
        // Remove leading '/'
        $path = ltrim($path, '/');
        // Split the path and get the public ID
        $segments = explode('/', $path);
        $filename = array_pop($segments);
        // Remove the file extension from filename
        $publicId = pathinfo($filename, PATHINFO_FILENAME);
        return $publicId;
    }
}
