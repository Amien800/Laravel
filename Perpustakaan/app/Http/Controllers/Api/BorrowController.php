<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Books;
use App\Models\Borrows;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware(['auth:api', 'isAdmin'])->only(
            'update',
            'index',
            'show'
        );
    }

    public function index()
    {
        $borrow = Borrows::with('books', 'user')->get();

        return response()->json(
            [
                'success' => true,
                'message' => 'Berhasil Menampilkan Data Cast Borrow',
                'data' => $borrow,
            ],
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id', // Asumsikan ada tabel users
            'borrow_date' => 'required|date',
            'load_date' => 'nullable|date',
        ]);

        $book = Books::find($request->book_id);

        if ($book->stok < 1) {
            return response()->json(['message' => 'Book out of stock'], 400);
        }

        // Buat peminjaman baru
        $loan = Borrows::create([
            'book_id' => $request->book_id,
            'user_id' => $request->user_id,
            'borrow_date' => $request->borrow_date,
            'load_date' => $request->load_date,
        ]);

        // Kurangi stok buku
        $book->decrement('stok');

        return response()->json(
            ['message' => 'Book loaned successfully', 'loan' => $loan],
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $borrow = Borrows::with('books', 'user')->find($id);

        if (!$borrow) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'ID Borrow tidak Ditemukan',
                    'data' => $id,
                ],
                404
            );
        }
        return response()->json([
            'success' => true,
            'message' => 'Berhasil Menampilkan Data Peminjaman Buku',
            'data' => $borrow,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'load_date' => 'required|date',
            'book_id' => 'required|exists:books,id',
        ]);

        $loan = Borrows::find($id);
        $book = Books::find($request->book_id);

        if (!$loan) {
            return response()->json(['message' => 'Loan not found'], 404);
        }

        if ($loan->load_date) {
            return response()->json(
                ['message' => 'Book already returned'],
                400
            );
        }

        // Update return date
        $loan->update([
            'load_date' => $request->load_date,
            'book_id' => $request->book_id,
        ]);

        // Tambah stok buku kembali
        $book->increment('stok');

        return response()->json(
            ['message' => 'Book returned successfully', 'loan' => $loan],
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    }
}
