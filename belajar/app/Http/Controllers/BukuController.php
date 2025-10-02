<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Symfony\Contracts\Service\Attribute\Required;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $buku = Buku::all();
        return view("buku.index") ->with("buku" , $buku);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("buku.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $val = $request->validate([
        'kode_buku' => 'required|max:50',
        'judul' => 'required|max:50',
        'pengarang' => 'required|max:50',
        'penerbit' => 'required|max:50',
        'tahun_terbit' => 'required',
       ]);
      Buku::create($val);
      return redirect()->route('buku.index')->with('succes', $val ['judul'],'Berhasil di Simpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Buku $buku)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Buku $buku)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Buku $buku)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Buku $buku)
    {
        //
    }
}
