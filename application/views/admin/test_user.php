<style>

	.card {
		margin-bottom: 0px;
	}

</style>


<div class="container-fluid" >

	<form action="/mypage/test_user/<?= $appuser["id"] ?>" method="post">
		<input type="hidden" name="id" value="<?= $appuser["id"] ?>" >
		<?php if(!empty($message)) { ?>
			<div class="alert alert-success" role="alert"><?= $message ?></div>
		<?php } ?>
		<?php if(!empty($err)) { ?>
			<div class="alert alert-danger" role="alert"><?= $err ?></div>
		<?php } ?>


		</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header card-header-primary">
					<h4 class="card-title">テスト <a href="https://twitter.com/<?= $appuser["account_name"] ?>"><?= $appuser["name"] ?></a></h4>

				</div>
				<div class="card-body" id="status_off">
					<div class="card">
						<div class="card-body">
							<?php if(!empty($output)) { ?>
								<h4 class="card-title">テスト結果</h4>
								<p class="category">
									<pre>
									<?= $output ?>
									</pre>
								</p>
							<?php } else { ?>
								以下のボタンを押してテスト実行
							<?php } ?>
						</div>
					</div>

				</div>





					<button type="submit" class="btn btn-primary pull-right">テスト実行</button>
					<div class="clearfix"></div>

			</div>
		</div>
<!--		<div class="col-md-4">-->
<!--			<div class="card card-profile">-->
<!--				<div class="card-avatar">-->
<!--					<a href="#pablo">-->
<!--						<img class="img" src="--><?//= $user_data["image_url"]; ?><!--" style="" />-->
<!--					</a>-->
<!--				</div>-->
<!--				<div class="card-body">-->
<!--					<h6 class="card-category text-gray">CEO / Co-Founder</h6>-->
<!--					<h4 class="card-title">Alec Thompson</h4>-->
<!--					<p class="card-description">-->
<!--						Don't be scared of the truth because we need to restart the human foundation in truth And I love you like Kanye loves Kanye I love Rick Owens’ bed design but the back is...-->
<!--					</p>-->
<!--					<a href="#pablo" class="btn btn-primary btn-round">Follow</a>-->
<!--				</div>-->
<!--			</div>-->
<!--		</div>-->

	</div>
	</form>
</div>
