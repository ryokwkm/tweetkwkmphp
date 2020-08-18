<style>



</style>
<pre>
<?php

$chartData = '';
$chartArray = array();
$chartArray["datasets"] = array();
//ラベル作成。key()は最初の要素のキーを返す
foreach ($userFollowers[key($userFollowers)]["followers"] as $date => $follower) {
	$labels[] = $date;
}
$chartArray["labels"] = $labels;

//データ作成
foreach ($userFollowers as $followers) {
	$u_followers = array();
	foreach ($followers["followers"] as $f) {
		$u_followers[] = $f;
	}
	if(!isset($followers["name"])) {
		$followers["name"] = "";
	}
	$chartArray["datasets"][] = array(
		"label" => $followers["name"],
		"data"=> $u_followers,
		"backgroundColor" => "rgba(0,0,0,0)",
	);

}

$chartData = json_encode($chartArray, JSON_UNESCAPED_UNICODE);


?>
	</pre>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<script src="https://github.com/nagix/chartjs-plugin-colorschemes/releases/download/v0.2.0/chartjs-plugin-colorschemes.min.js"></script>
<script>
	'use strict';

	$(function(){

		var ctx = document.getElementById("myChart").getContext('2d');
		var options = {
			title: {
				display: true,
				text: 'フォロワー推移'
			},

			plugins: {
				colorschemes: {
					scheme: 'brewer.Paired12'
				}
			}
		}

		var data =<?php echo $chartData; ?>

		var myChart = new Chart(ctx, {
			type: 'line',
			data: data,
			options: options,
		});
	});
</script>

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-primary">
				ユーザーリスト
				<?php echo date('Y-m-d'); ?>
			</div>
			<script>
				// selectの内容をinputへ反映
				$(function(){
					$('.datetimepicker').datetimepicker({

						format : 'YYYY-MM-DD',
						defaultDate: <?php echo date('Y-m-d'); ?>,
						locale: 'ja'
					});


					$("#targetUser").change(function(){
						$('input[name="targetUser"]').val( $(this).val() );
					});
				});
			</script>
			<form action="followers" method="get">

				<!-- input with datetimepicker -->
				<div class="form-group">
					<label class="label-control">Datetime Picker</label>
					<input type="text" class="form-control datetimepicker" value="21/06/2018"/>
				</div>


				<input type="hidden" name="targetUser" value="" >
				<div class="form-group">
					<label >対象キュレーターを選択</label>
					<select multiple class="form-control selectpicker" data-style="btn btn-link" id="targetUser">
						<option>1</option>
						<option>2</option>
						<option>3</option>
						<option>4</option>
						<option>5</option>
					</select>

					<button>選択する</button>
				</div>
			</form>


			<canvas id="myChart" width="400" height="400"></canvas>



















			<div class="clearfix"></div>

		</div>
	</div>


</div>

</div>
