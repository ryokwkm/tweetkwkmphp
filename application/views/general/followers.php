<style>



</style>
<pre>
<?php

$chartData = '';


$chartData = json_encode($chartArray, JSON_UNESCAPED_UNICODE);


?>
	</pre>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<script src="https://github.com/nagix/chartjs-plugin-colorschemes/releases/download/v0.2.0/chartjs-plugin-colorschemes.min.js"></script>
<script>
	'use strict';

	$(function(){



		//スマホ？
		if(window.innerWidth < 500 ) {
			$("#myChart").height(window.innerHeight * 0.8);
		} else {
			$("#myChart").height(window.innerHeight * 0.6);
		}


		var ctx = document.getElementById("myChart").getContext('2d');
		var options = {
			title: {
				display: true,
				text: 'フォロワー推移'
			},
			tooltips: {
				mode: 'index',
				itemSort: function(a, b, data) {
					return (b.yLabel - a.yLabel);
				},
			},
			plugins: {
				colorschemes: {
					scheme: 'brewer.Paired12'
				}
			},
			scales: {
				xAxes: [
					{
						ticks: {
							maxTicksLimit: 9
						}
					}
				]
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

<?php if(!empty($err)) { ?>
	<div class="alert alert-danger" role="alert"><?= $err ?></div>
<?php } ?>

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-primary">
				フォロワー推移
			</div>
			<div class="card-body" id="main_form">
				<form action="" method="get">
				<div class="row">


					<script>
						$(function(){
							//データピッカー
							$('.datetimepicker').datetimepicker({
								format : 'YYYY-MM-DD',
								locale: 'ja'
							});

							// selectの内容をinputへ反映
							var allSelect = 0;
							$("#selectall").click(function(){
								console.log("click")
								if(allSelect == 0 ) {
									//全選択解除
									allSelect = 1
									$("#targetUser option").prop("selected", false);
								} else {
									//全選択
									allSelect = 0
									$("#targetUser option").prop("selected", true);
									$("#option0").prop("selected", false) //0は解除
								}
								//inputフォームに反映
								$('input[name="targetUser"]').val( $("#targetUser").val() );
								$('.selectpicker').selectpicker('refresh');
							});

							$("#targetUser").change(function(){
								//inputフォームに反映
								$('input[name="targetUser"]').val( $(this).val() );
							});
						});
					</script>


						<!-- input with datetimepicker -->
						<div class="col-md-6">
							<div class="form-group">
								<label class="label-control">開始日</label>
								<input name="start" type="text" class="form-control datetimepicker" value="<?= $formDefault["start"] ?>"/>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="label-control">終了日</label>
								<input name="end" type="text" class="form-control datetimepicker" value="<?= $formDefault["end"] ?>"/>
							</div>
						</div>

						<div class="col-md-12">

							<input type="hidden" name="targetUser" value="<?= $formDefault["targetUser"] ?>" >
							<div class="form-group">
								<label >対象キュレーターを選択</label>　<span ><a href="#" id="selectall"><i class='fa fa-check-square-o'></i> 全選択</a></span>
								<select multiple class="form-control selectpicker" data-style="btn btn-link" id="targetUser">
									<?php
									$target_user_ids = explode(",", $formDefault["targetUser"]);
									foreach($users as $user) {	?>
										<option value="<?= $user["id"] ?>"
											<?php if (in_array($user["id"], $target_user_ids)) echo " selected"; ?> >
											<?= $user["name"] ?>
										</option>
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="col-md-12">

							<button>検索</button>
						</div>



					<div class="col-md-12">
						<div class="row">
							<canvas id="myChart" ></canvas>
						</div>


					</div>

				</div>
				</form>
			</div>




















			<div class="clearfix"></div>

		</div>
	</div>


</div>

</div>
