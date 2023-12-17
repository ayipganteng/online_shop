<?php

namespace App\Http\Livewire;

use Livewire\Component;
use ILLuminate\Support\Facades\Auth;
use App\Models\Belanja;

class BelanjaUser extends Component
{
	public $belanja = [];
	public function mount()
	{
		//autentifikasi
		if(!Auth::user()){
			return redirect()->to('login');
		}	
	}

	public function destroy($pesanan_id)
	{
		$pesanan = Belanja::find($pesanan_id);
		$pesanan->delete();
	}

	public function render()
	{
		if(Auth::user()){
			$this->belanja = Belanja::where('user_id',Auth::user()->id)->get();
		}
		return view('livewire.belanja-user')->extends('layouts.app')->section('content');
	}
}
