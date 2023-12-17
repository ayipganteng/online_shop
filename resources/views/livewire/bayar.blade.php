<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-6">
			@if($belanja->status == 1)
			<div class="row">
				<div class="col-md-12">
					<button id="pay-button" type="button" class="btn btn-primary center-block"> Bayar</button>
				</div>
			</div>
			@elseif($belanja->status == 2)
			<div class="card">
				<div class="col-md-12">
					<div class="row">
						<div class="col">
							<table class="table" style="border-top: hidden;">
								<tr>
									<td>Virtual Akun</td>
									<td>:</td>
									<td>{{$va_number}}</td>
								</tr>
								<tr>
									<td>Bank</td>
									<td>:</td>
									<td>{{$bank}}</td>
								</tr>
								<tr>
									<td>Total Harga</td>
									<td>:</td>
									<td>{{$gross_amount}}</td>
								</tr>
								<tr>
									<td>Status</td>
									<td>:</td>
									<td>{{$transaction_status}}</td>
								</tr>
								<tr>
									<td>Batas Waktu Pembayaran</td>
									<td>:</td>
									<td>{{$deadline}}</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
			@endif
		</div>
	</div>
</div>
<div>
	<form id="payment-form" method="get" action="Payment">
		<input type="hidden" name="result_data" id="result-data" value=""></div>
	</form>
</div>
</body>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-server-CxlF82V2fdNzg4whG-Dsw-RH"></script>
<script type="text/javascript">
	document.getElementById('pay-button').onclick = function(){
		var resultType = document.getElementById('result-type');
		var resultData = document.getElementById('result-data');
		function changeResult(type,data){
			$("result-type").val(type);
			$("result-data").val(JSON.stringify(data));
		}

	snap.pay('<?=$snapToken?>',{
		//optional
		onSuccess: function(result){
			changeResult('success', result);
			console.log(result.status_message);
			console.log(result);
			$("#payment-form").submit();
		},
		onPending: function(result){
			changeResult('pending', result);
			console.log(result.status_message);
			console.log(result);
			$("#payment-form").submit();
		},
		onError: function(result){
			changeResult('error', result);
			console.log(result.status_message);
			console.log(result);
			$("#payment-form").submit();
		}

	});
	}
</script>