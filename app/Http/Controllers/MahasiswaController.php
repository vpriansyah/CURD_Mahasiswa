<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Exception;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index (Request $request)
    {
        $mahasiswas = Mahasiswa::when($request->search, function($query) use ($request) {
            $query->where('nama', 'like', '%'.$request->search.'%');
        })
        ->when($request->gender, function($query) use ($request) {
            $query->where('gender', $request->gender);
        })
        ->get();

        return view('mahasiswa.index', compact('mahasiswas'));
    }

    public function home (Request $request)
    {
        $mahasiswas = Mahasiswa::when($request->search, function($query) use ($request) {
            $query->where('nama', 'like', '%'.$request->search.'%');
        })
        ->when($request->gender, function($query) use ($request) {
            $query->where('gender', $request->gender);
        })
        ->get();

        return view('mahasiswa.home', compact('mahasiswas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mahasiswa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|max:20|unique:mahasiswas',
            'nama' => 'required|max:255',
            'alamat' => 'required',
            'tanggal_lahir' => 'required|date',
            'gender' => 'required',
            'usia' => 'required|integer',
        ]);

        $mahasiswa = Mahasiswa::create($validated);

        Alert::success('Success', 'Mahasiswa has been saved !');
        return redirect()->route('mahasiswa.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nim' => 'required|max:20|unique:mahasiswas,nim,' . $id,
            'nama' => 'required|max:255',
            'alamat' => 'required',
            'tanggal_lahir' => 'required|date',
            'gender' => 'required',
            'usia' => 'required|integer',
        ]);

        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->update($validated);

        Alert::info('Success', 'Mahasiswa has been updated !');
        return redirect()->route('mahasiswa.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $mahasiswa = Mahasiswa::findOrFail($id);

            $mahasiswa->delete();

            Alert::error('Success', 'Mahasiswa has been deleted !');
            return redirect()->route('mahasiswa.index');
        } catch (Exception $ex) {
            Alert::warning('Error', 'Can\'t delete, Mahasiswa already used !');
            return redirect()->route('mahasiswa.index');
        }
    }
}
