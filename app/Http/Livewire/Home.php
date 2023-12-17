<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Produk;
use App\Models\Belanja;
use ILLuminate\Support\Facades\Auth;

class Home extends Component
{
	public $products = [];

	//atribut filtering
	public $search,$min,$max;
	public function beli($id)
	{
		if(!Auth::user()){
			return redirect()->to('login'); 
		}
		//mencari data produk
		$produk = Produk::find($id);

		Belanja::create(
			[
				'user_id'		=> Auth::user()->id,
				'produk_id'		=> $produk->id,
				'total_harga'	=> $produk->harga,
				'status'		=> 0
			]
		);
		return redirect()->to('BelanjaUser');
	}

    public function render()
    {
    	//filter harga max
    	if($this->max){
    		$harga_max = $this->max;
    	}else{
    		$harga_max = 500000000;
    	}

    	//filter harga min
    	if($this->min){
    		$harga_min = $this->min;
    	}else{
    		$harga_min = 0;
    	}

    	if($this->search){
    		$this->products = Produk::where('nama','like','%'.$this->search.'%')
    								->where('harga','>=',$harga_min)
    								->where('harga','<=',$harga_max)
    								->get();
    	}else{
    		$this->products = Produk::where('harga','>=',$harga_min)
    								->where('harga','<=',$harga_max)
    								->get();
    	}

        return view('livewire.home')->extends('layouts.app')->section('content');
    }
}
