<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Belanja;
use ILLuminate\Support\Facades\Auth;

class Bayar extends Component
{
	public $snapToken;
	public $belanja;
	public $va_number, $gross_amount, $bank, $transaction_status, $deadline;

	public function mount($id){
		if(!Auth::user()){
			return redirect()->route('login');
		}
		// Set your Merchant Server Key
		\Midtrans\Config::$serverKey = 'SB-Mid-server-CxlF82V2fdNzg4whG-Dsw-RH';
		// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
		\Midtrans\Config::$isProduction = false;
		// Set sanitization on (default)
		\Midtrans\Config::$isSanitized = true;
		// Set 3DS transaction for credit card to true
		\Midtrans\Config::$is3ds = true;

		if(isset($_GET['result_data'])){
			$current_status = json_decode($_GET['result_data'],true);
			//dd($current_status);
			$order_id		= $current_status['order_id'];
			$this->belanja 	= Belanja::where('id',$order_id)->first();
			$this->belanja->status = 2;
			$this->belanja->update();
		}else{
			//ambil data belanja
			$this->belanja = Belanja::find($id);
		}

		//dd($this->belanja);

		if(!empty($this->belanja)){
			if ($this->belanja->status == 1) {
				$params = array(
					'transaction_details' => array(
						'order_id' => $this->belanja->id,
						'gross_amount' => $this->belanja->total_harga,
					),
					'customer_details' => array(
						'first_name' => 'saudara',
						'last_name' => Auth::user()->name,
						'email' => Auth::user()->email,
						'phone' => '08111222333',
					),
				);

				$this->snapToken = \Midtrans\Snap::getSnapToken($params);
			}
			elseif($this->belanja->status == 2){
				$status = \Midtrans\Transaction::status($this->belanja->id);
				$status = json_decode(json_encode($status),true);
				//dd($status);
				//menampilkan status pembayaran
				$this->va_number = $status['va_numbers'][0]['va_number'];
				$this->gross_amount = $status['gross_amount'];
				$this->bank = $status['va_numbers'][0]['bank'];
				$this->transaction_status = $status['transaction_status'];
				$transaction_time = $status['transaction_time'];
				$this->deadline = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($transaction_time)));
			}
		}
	}

	public function render()
	{
		return view('livewire.bayar')->extends('layouts.app')->section('content');
	}
}
