
<script>
	function DisplayProfileForm($elm) {
		if($("#input_trendsearch").prop('checked')){
			$(".trendsearch_box").show();
		} else {
			$(".trendsearch_box").hide();
		}

		if($("#input_replyaction").prop('checked')){
			$(".replyaction_box").show();
		} else {
			$(".replyaction_box").hide();
		}
	}

	function DispCharaMode() {
		$this = $('input[name="character_mode"]:checked');

		if( $this.val() == 1) {
			$(".character_mode1").show();
			$(".character_mode2").hide();
		} else if( $this.val() == 2) {
			$(".character_mode1").hide();
			$(".character_mode2").show();
		} else {
			$(".character_mode1").hide();
			$(".character_mode2").hide();
		}
	}

	function ChangeExeRate(){
		$perHour = $('input[name="exe_rate"]').val() * 6 / 100;
		$body = "1時間に約 " + $perHour.toFixed(1)  + "回実行される"
		$("#exe_rate_text").text($body);
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

	$(document).ready(function() {
		$('input[type="checkbox"]').each(function(){
			DisplayProfileForm($(this));
		});
		DispCharaMode();
		ChangeExeRate();


		$('input[type="checkbox"]').change(function(){
			DisplayProfileForm($(this));
		});
		$('input[name="character_mode"]').change(function(){
			DispCharaMode();
		});
		$('input[name="exe_rate"]').change(function(){
			ChangeExeRate();
		});
	});

</script>

<div class="container-fluid" >

	<form action="/mypage/userupdate" method="post">
		<input type="hidden" name="id" value="<?= $appuser["id"] ?>" >
		<?php if(!empty($message)) { ?>
			<div class="alert alert-success" role="alert"><?= $message ?></div>
		<?php } ?>


		</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header card-header-primary">
					<table width="100%">
						<tr >
							<td width="50%">
								<h4 class="card-title">Edit Profile</h4>
								<p class="card-category"><?= $appuser["name"] ?></p>
							</td>
							<td width="50%">
								<div class="togglebutton">
									<label>
										<input type="checkbox" name="main_status" value="1" id="input_main_status" <?php if($appuser["is_deleted"] == 0 || $appuser["is_deleted"]== 3) echo "checked" ?> >
										<span class="toggle toggle-main"></span>
										<span id="input_main_status_text" style="color: #ffffff"></span>
									</label>
								</div>
							</td>
						</tr>
					</table>

				</div>
				<div class="card-body">

						<div class="row">


							<div class="col-md-6">
								<div class="form-group">
									<label for="exampleInputPassword1">実行確率(/10min)</label>
									<input name="exe_rate" type="number" class="form-control"  min="1" max="100" value="<?= $appuser["exe_rate"] ?>">
								</div>
							</div>
							<div class="col-md-6">
								<blockquote class="blockquote" style="margin-top: 10px">
									<small class="text-muted" id="exe_rate_text">1時間に約、X回行われる</small>
								</blockquote>
							</div>

							<?php
							$character_mode0 = "";
							$character_mode1 = "";
							$character_mode2 = "";
							if(empty($appuser["character_mode"])) {
								$character_mode0 = " checked";
							} else if($appuser["character_mode"] == 1) {
								$character_mode1 = " checked";
							} else if($appuser["character_mode"] == 2) {
								$character_mode2 = " checked";
							} ?>

							<div class="col-md-3">
								<div class="form-check form-check-radio">
									<label class="form-check-label">
										<input class="form-check-input" type="radio" name="character_mode" value="0" <?= $character_mode0 ?> >
										なし
										<span class="circle">
											<span class="check"></span>
									</span>
									</label>
								</div>

								<div class="form-check form-check-radio">
									<label class="form-check-label">
										<input class="form-check-input" type="radio" name="character_mode" value="1" <?= $character_mode1 ?> >
										Twitterユーザー
										<span class="circle">
											<span class="check"></span>
									</span>
									</label>
								</div>

								<div class="form-check form-check-radio">
									<label class="form-check-label">
										<input class="form-check-input" type="radio" name="character_mode" id="exampleRadios2" value="2" <?= $character_mode2 ?> >
										キャラクター
										<span class="circle">
											<span class="check"></span>
									</span>
									</label>
								</div>
							</div>

							<div class="col-md-9">
								<div class="form-group character_mode1">
									<label for="exampleInputPassword1" style="margin-top: 10px;">Twitterユーザー名</label>
									<input type="text" name="target_screen_name" class="form-control"  value="<?= $appuser["target_screen_name"] ?>" >
								</div>

								<div class="form-group character_mode2">
									<script>
										$(function(){
											$('#target_character').selectpicker('val', <?= $appuser["target_character_id"] ?>);
										});
									</script>
									<select name="target_character_id" id="target_character" class="form-control selectpicker" data-style="btn btn-link">
										<?php foreach($characters as $character) {
											$characterCheck = "";
											if ($character["id"] == $appuser["target_character_id"]) {
												$characterCheck = " selected";
											}
											?>
											<option value="<?= $character["id"] ?>" <?= $characterCheck ?>"><?= $character["name"] ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="col-md-12">
								<div class="togglebutton">
									<label>
										<input type="hidden" name="search_mode" value="2">
										<input type="checkbox" name="is_search" value="1" id="input_trendsearch" <?php if($appuser["is_search"] == 1) echo "checked" ?> >
										<span class="toggle"></span>
										トレンドサーチ
										<span id="input_trendsearch_text"></span>
									</label>
								</div>
							</div>


							<div class="col-md-6 trendsearch_box">
								<div class="form-group">
									<label for="exampleInputPassword1">実行確率</label>
									<input type="number" name="search_rate" class="form-control"  min="0" max="100" value="<?= $appuser["search_rate"] ?>">
								</div>
							</div>

							<div class="col-md-6 trendsearch_box">
								<div class="form-group">
									<label for="exampleInputPassword1">実行量</label>
									<input type="number" name="search_count" class="form-control"  min="0" max="100" value="<?= $appuser["search_count"] ?>">
								</div>
							</div>

							<div class="col-md-6 trendsearch_box">
								<div class="form-group">
									<label for="exampleInputPassword1">トレンドキーワード</label>
									<input type="text" name="search_keyword" class="form-control" value="<?= $appuser["search_keyword"] ?>" placeholder="fate fgo">
								</div>
							</div>

							<div class="col-md-6 trendsearch_box">
								<div class="form-group">
									<label for="exampleInputPassword1">サーチオプション</label>
									<input type="text" name="search_option" class="form-control" value="<?= $appuser["search_option"] ?>" placeholder="min_faves:3">
								</div>
							</div>

							<div class="col-md-12">
								<div class="togglebutton">
									<label>
										<input type="checkbox" value="1" name="is_news" id="input_news" <?php if($appuser["is_news"] == 1) echo "checked" ?> >
										<span class="toggle"></span>
										ニュースをツイート
										<span id="input_news_text"></span>
									</label>
								</div>
							</div>

							<div class="col-md-12">
								<div class="togglebutton">
									<label>
										<input type="checkbox" value="1" name="is_reply" id="input_replyaction" <?php if($appuser["is_reply"] == 1) echo "checked" ?> >
										<span class="toggle"></span>
										リプライに対しにアクションを実行
										<span id="input_replyaction_text"></span>
									</label>
								</div>
							</div>

							<div class="col-md-12 replyaction_box">
								<div class="togglebutton">
									<label>
										<input type="checkbox" value="1" name="reply_retweet" id="input_replyretweet" <?php if($appuser["reply_retweet"] == 1) echo "checked" ?> >
										<span class="toggle"></span>
										リプライをリツイート
										<span id="input_replyretweet_text"></span>
									</label>
								</div>
							</div>

							<div class="col-md-12 replyaction_box">
								<div class="togglebutton">
									<label>
										<input type="checkbox" value="1" name="is_replyreply" id="input_replyreply" <?php if($appuser["is_replyreply"] == 1) echo "checked" ?> >
										<span class="toggle"></span>
										リプライにお返事
										<span id="input_replyreply_text"></span>
									</label>
								</div>
							</div>


							<div class="col-md-12">
								<div class="togglebutton">
									<label>
										<input type="checkbox" value="1" name="followback" id="input_followback" <?php if($appuser["followback"] == 1) echo "checked" ?> >
										<span class="toggle"></span>
										フォローバック
										<span id="input_followback_text"></span>
									</label>
								</div>
							</div>
						</div>




					</div>
					<button type="submit" class="btn btn-primary pull-right">Update Profile</button>
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
