<?php

namespace App\Http\Livewire;

use Livewire\Component;
//untuk autentifikasi
use Illuminate\Support\Facades\Auth;

//untuk memanggil model
use App\Models\Produk;

//untuk penyimpanan
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class TambahProduk extends Component
{
	public $nama,$harga,$berat,$gambar;
	use WithFileUploads;

	public function mount()
	{
		if(Auth::user())
		{
			if(Auth::user()->level !== 1)
			{
				return redirect()->to('');
			}
		}
	}

	public function store()
	{
		//validasi
		$this->validate(
			[
			'nama' 		=> 'required',
			'harga' 	=> 'required',
			'berat' 	=> 'required',
			'gambar' 	=> 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
			]
		);

		//pemrosesan data file gambar
		$nama_gambar = md5($this->gambar.microtime()).'.'.$this->gambar->extension();
		Storage::disk('public')->putFileAs('photos',$this->gambar,$nama_gambar);

		//memasukkan data ke database
		Produk::create(
			[
				'nama' 		=> $this->nama,
				'harga' 	=> $this->harga,
				'berat' 	=> $this->berat,
				'gambar' 	=> $nama_gambar
			]
		);

	//redirect
	return redirect()->to('');
	}

    public function render()
    {
        return view('livewire.tambah-produk')->extends('layouts.app')->section('content');
    }
}
