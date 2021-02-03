<?php

$adminMode = true;
$readOnly = "";

if(!empty($general)) {
	$adminMode = false;
	$readOnly = " disabled='disabled' ";
}
?>

<style>

	.card {
		margin-bottom: 0px;
	}


</style>

<script>
	function DisplayProfileForm($elm) {
		if ($("#input_trendsearch").prop('checked')) {
			$(".trendsearch_box").show();
		} else {
			$(".trendsearch_box").hide();
		}

		if ($("#input_replyaction").prop('checked')) {
			$(".replyaction_box").show();
		} else {
			$(".replyaction_box").hide();
		}

		if ($("#input_news").prop('checked')) {
			$(".news_box").show();
		} else {
			$(".news_box").hide();
		}

		if ($("#input_main_status").prop('checked')) {
			$("#main_form").show();
			$("#status_off").hide();
		} else {
			$("#status_off").show();
			$("#main_form").hide();
		}



	}

	function ChangeRadio() {
		$charaMode = $('input[name="character_mode"]:checked');

		if( $charaMode.val() == 1) {
			$(".character_mode1").show();
			$(".character_mode2").hide();
			$(".serif_setting").show();
		} else if( $charaMode.val() == 2) {
			$(".character_mode1").hide();
			$(".character_mode2").show();
			$(".serif_setting").show();
		} else {
			$(".character_mode1").hide();
			$(".character_mode2").hide();
			$(".serif_setting").hide();
		}

		$searchMode = $('input[name="search_mode"]:checked');
		console.log($searchMode.val())
		if( $searchMode.val() == 2 || $searchMode.val() == 3) {
			$(".keyword_disp_box").show();
			$(".ranking_disp_box").hide();
		} else if ($searchMode.val() == 1 ) {
			//リアルタイムランキング
			$(".keyword_disp_box").hide();
			$(".ranking_disp_box").show();
		}

	}

	function ChangeExeRate(){
		$perHour = $('input[name="exe_rate"]').val() * 6 / 10 / 100 * 24;
		$body = "1日に約 " + $perHour.toFixed(1)  + "回実行される"
		$("#exe_rate_text").text($body);
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

	$(document).ready(function() {
		$('input[type="checkbox"]').each(function(){
			DisplayProfileForm($(this));
		});
		ChangeRadio();
		ChangeExeRate();


		$('input[type="checkbox"]').change(function(){
			DisplayProfileForm($(this));
		});
		$('input[type="radio"]').change(function(){
			ChangeRadio();
		});
		$('input[name="exe_rate"]').change(function(){
			ChangeExeRate();
		});
	});

</script>


<div class="container-fluid" >

	<?php if($adminMode) { ?>
		<form action="/mypage/userupdate" method="post">
			<input type="hidden" name="id" value="<?= $appuser["id"] ?>" >
	<?php } ?>




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
					<table width="100%">
						<tr >
							<td width="50%">
								<h4 class="card-title"><a href="https://twitter.com/<?= $appuser["account_name"] ?>" target="_blank"><?= $appuser["name"] ?>  @<?= $appuser["account_name"] ?></a> </h4>
<!--								<p class="card-category"><a href="https://twitter.com/--><?//= $appuser["account_name"] ?><!--">--><?//= $appuser["name"] ?><!--</a></p>-->
							</td>
							<td width="50%">
								<div class="togglebutton">
									<label>
										<input type="checkbox" name="main_status" value="1" id="input_main_status" <?php if($appuser["is_deleted"] == 0 || $appuser["is_deleted"]== 3) echo "checked" ?>  <?= $readOnly ?> >
										<span class="toggle toggle-main"></span>
										<span style="color: #ffffff">電源</span> <span id="input_main_status_text" style="color: #ffffff"></span>
									</label>
								</div>
							</td>
						</tr>
					</table>

				</div>
				<div class="card-body" id="status_off">
					<div class="card">
						<div class="card-body">
							<h4 class="card-title">電源OFF</h4>
							<p class="category"><?= $appuser["name"] ?>は実行されません</p>
						</div>
					</div>

				</div>
				<div class="card-body" id="main_form">


							<div class="card">
								<div class="card-body">
									<h4 class="card-title">概要</h4>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="exampleInputPassword1"></label>
												<textarea name="memo" class="form-control" rows="10" <?= $readOnly ?>><?= $appuser["memo"] ?></textarea>
											</div>
										</div>
									</div>
								</div>
							</div>


							<div class="card">
								<div class="card-body">
									<h4 class="card-title">実行間隔</h4>
									<p class="category">約100分間に一度、以下の確率(%)で実行します</p>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="exampleInputPassword1">実行確率(%) /100min</label>
												<input name="exe_rate" type="number" class="form-control"  min="1" max="100" value="<?= $appuser["exe_rate"] ?>" <?= $readOnly ?>>
											</div>
										</div>
										<div class="col-md-6">
											<blockquote class="blockquote" style="margin-top: 10px">
												<small class="text-muted" id="exe_rate_text">1日に約、X回行われる</small>
											</blockquote>
										</div>
									</div>
								</div>
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

<!--							セリフ		-->
							<div class="card">
								<div class="card-body">
									<h4 class="card-title">キャラクターの性格</h4>
									<p class="category">AIでこのキャラクターのセリフを生成してつぶやく</p>
									<div class="row">

										<div class="col-md-3">
											<div class="form-check form-check-radio">
												<label class="form-check-label">
													<input class="form-check-input" type="radio" name="character_mode" value="0" <?= $character_mode0 ?>  <?= $readOnly ?>>
													なし
													<span class="circle">
														<span class="check"></span>
													</span>
												</label>
											</div>

											<div class="form-check form-check-radio">
												<label class="form-check-label">
													<input class="form-check-input" type="radio" name="character_mode" value="1" <?= $character_mode1 ?> <?= $readOnly ?> >
													Twitterユーザー
													<span class="circle">
															<span class="check"></span>
													</span>
												</label>
											</div>

											<div class="form-check form-check-radio">
												<label class="form-check-label">
													<input class="form-check-input" type="radio" name="character_mode" id="exampleRadios2" value="2" <?= $character_mode2 ?> <?= $readOnly ?> >
													キャラクター
													<span class="circle">
															<span class="check"></span>
													</span>
												</label>
											</div>
										</div>

										<div class="col-md-9 serif_setting">

										<div class="form-group">
											<label for="exampleInputPassword1">セリフの実行確率(%)</label>
											<input name="serif_rate" type="number" class="form-control"  min="0" max="100" value="<?= $appuser["serif_rate"] ?>" <?= $readOnly ?>>
										</div>

										<div class="form-group character_mode1">
											<label for="exampleInputPassword1" style="margin-top: 10px;">Twitterユーザー名</label>
											<input type="text" name="target_screen_name" class="form-control"  value="<?= $appuser["target_screen_name"] ?>" <?= $readOnly ?> >
										</div>

										<div class="form-group character_mode2">
											<script>
												// selectのoptionの内容を書き換える
												function setCharacterOptions(characters) {
													characters.forEach((elem, index) => {
														console.log(`${index}: ${elem}`);
														$('#target_character').append($('<option>').html( characters[index]["name"]).val(characters[index]["id"]));
													});
												}

												$(function(){

													stories = <?php
														$stories = array();
														foreach ($characters as $character) {
															$stories[$character["story_id"]][] = $character;
														}
														echo ConvertJson($stories);
													?> ;

												});

												// selectの内容をinputへ反映
												$(function(){

													$("#target_character").change(function(){
														$("#target_character_id").val( $(this).val() );
													});

													$("#search_genre").change(function(){
														$('input[name="search_genre"]').val( $(this).val() );
													});
												});
											</script>


											<input type="hidden" name="target_character_id" id="target_character_id" value="<?= $appuser["target_character_id"] ?>" <?= $readOnly ?> >
											<select multiple id="target_character" class="form-control selectpicker" data-style="btn btn-link" <?= $readOnly ?>>
												<?php
												$target_character_ids = explode(",", $appuser["target_character_id"]);
												foreach($characters as $character) {	?>
													<option value="<?= $character["id"] ?>"
														<?php if (in_array($character["id"], $target_character_ids)) echo " selected"; ?> >
														<?= $character["name"] ?>
													</option>
												<?php } ?>
											</select>
										</div>


										<div class="togglebutton">
											<label>
												<input type="checkbox" value="1" name="serif_relation_retweet" id="input_serif_relation_retweet" <?php if($appuser["serif_relation_retweet"] == 1) echo "checked" ?> <?= $readOnly ?> >
												<span class="toggle"></span>
												関連したツイートをリツイート
												<span id="input_serif_relation_retweet_text"></span>
											</label>
										</div>

									</div>


									</div>
								</div>
							</div>





<!--							トレンド		-->
							<div class="card">
								<div class="card-body">
									<h4 class="card-title">みんなの反応検索</h4>
									<p class="category">指定したキーワードに関するみんなの反応を紹介</p>
									<div class="row">
										<div class="col-md-12">
											<div class="togglebutton">
												<label>
													<input type="checkbox" name="is_search" value="1" id="input_trendsearch" <?php if($appuser["is_search"] == 1) echo "checked" ?> <?= $readOnly ?> >
													<span class="toggle"></span>
													トレンドサーチ
													<span id="input_trendsearch_text"></span>
												</label>
											</div>
										</div>

										<div class="col-md-12 trendsearch_box">

											<?php
											$search_mode1 = "";
											$search_mode2 = "";
											$search_mode3 = ""; //デフォルトは3
											if(empty($appuser["search_mode"]) || $appuser["search_mode"] == 3) {
												$search_mode3 = " checked";
											} else if($appuser["search_mode"] == 2) {
												$search_mode2 = " checked";
											} else if($appuser["search_mode"] == 1) {
												$search_mode1 = " checked";
											}?>

											<div class="form-check form-check-radio">
												<label class="form-check-label">
													<input class="form-check-input" type="radio" name="search_mode" value="3" <?= $search_mode3 ?>  <?= $readOnly ?>>
													キーワードのみを検索
													<span class="circle">
														<span class="check"></span>
													</span>
												</label>
											</div>

											<div class="form-check form-check-radio">
												<label class="form-check-label">
													<input class="form-check-input" type="radio" name="search_mode" value="2" <?= $search_mode2 ?>  <?= $readOnly ?>>
													キーワードに関するトレンドを検索
													<span class="circle">
														<span class="check"></span>
													</span>
												</label>
											</div>

											<div class="form-check form-check-radio">
												<label class="form-check-label">
													<input class="form-check-input" type="radio" name="search_mode" value="1" <?= $search_mode1 ?>  <?= $readOnly ?>>
													トレンドランキング
													<span class="circle">
															<span class="check"></span>
														</span>
												</label>
											</div>

										</div>

										<div class="col-md-6 trendsearch_box">
											<div class="form-group">
												<label for="exampleInputPassword1">実行確率</label>
												<input type="number" name="search_rate" class="form-control"  min="0" max="100" value="<?= $appuser["search_rate"] ?>" <?= $readOnly ?>>
											</div>
										</div>

										<div class="col-md-6 trendsearch_box">
											<div class="form-group">
												<label for="exampleInputPassword1">ツイートするランキング数</label>
												<input type="number" name="search_count" class="form-control"  min="0" max="15" value="<?= $appuser["search_count"] ?>" <?= $readOnly ?>>
											</div>
										</div>

										<!--		キーワード検索								-->
										<div class="col-md-6 trendsearch_box keyword_disp_box">
											<div class="form-group">
												<label for="exampleInputPassword1">トレンド検索キーワード</label>
												<input type="text" name="search_keyword" class="form-control" value="<?= $appuser["search_keyword"] ?>" placeholder="fate fgo" <?= $readOnly ?>>
											</div>
										</div>

										<div class="col-md-6 trendsearch_box ranking_disp_box">
											<div class="form-group">
												<input type="hidden" name="search_genre" value="<?= $appuser["search_genre"]; ?>">
												<select class="form-control selectpicker" data-style="btn btn-link" id="search_genre" <?= $readOnly ?>>
													<option value="">ジャンルを選択</option>
													<?php
													if (!empty($trend_genre)) { ?>
														<?php foreach($trend_genre as $key=>$genre) {	?>
															<option value="<?= $key ?>"
																<?php if($appuser["search_genre"] == $key) { echo " selected"; } ?>
															>
																<?= $genre ?>
															</option>
														<?php } ?>
													<?php	} 	?>
												</select>
											</div>
										</div>

										<div class="col-md-6 trendsearch_box">
											<div class="form-group">
												<label for="exampleInputPassword1">リアクション数</label>
												<input type="number" name="fire_lv" class="form-control" min="0" max="20" value="<?= $appuser["fire_lv"] ?>" placeholder="min_faves:3" <?= $readOnly ?>>
											</div>
										</div>

										<?php if ($adminMode) {  //編集モードのときのみ表示  ?>
										<div class="col-md-12">
											<div class="form-group">
												<label for="exampleInputPassword1">パンダ検索キーワード</label>
												<input type="text" name="panda_keyword" class="form-control" value="<?= $appuser["panda_keyword"] ?>" placeholder="fate fgo" <?= $readOnly ?>>
											</div>
										</div>
										<?php } ?>


									</div>

								</div>
							</div>



<!--							ニュース		-->
							<div class="card">
								<div class="card-body">
									<h4 class="card-title">ニュースを検索</h4>
									<p class="category">指定したカテゴリ（キーワード）の最新ニュース記事があればつぶやく</p>
									<div class="row">

										<div class="col-md-12">
											<div class="togglebutton">
												<label>
													<input type="checkbox" value="1" name="is_news" id="input_news" <?php if($appuser["is_news"] == 1) echo "checked" ?> <?= $readOnly ?> >
													<span class="toggle"></span>
													ニュースをツイート
													<span id="input_news_text"></span>
												</label>
											</div>
										</div>

										<div class="col-md-12 news_box">
											<div class="form-group">
												<label for="exampleInputPassword1">ニュース検索キーワード</label>
												<input type="text" name="news_keyword" class="form-control"  value="<?= $appuser["news_keyword"] ?>" <?= $readOnly ?>>
											</div>
										</div>
									</div>
								</div>
							</div>




<!--							リアクション		-->
							<div class="card">
								<div class="card-body">
									<h4 class="card-title">＠リプライに対するアクション</h4>
									<p class="category">リプライがあった場合アクションを実行</p>
									<div class="row">

										<div class="col-md-12">
											<div class="togglebutton">
												<label>
													<input type="checkbox" value="1" name="is_reply" id="input_replyaction" <?php if($appuser["is_reply"] == 1) echo "checked" ?> <?= $readOnly ?> >
													<span class="toggle"></span>
													リプライアクションを実行
													<span id="input_replyaction_text"></span>
												</label>
											</div>
										</div>

										<div class="col-md-12 replyaction_box">
											<div class="togglebutton">
												<label>
													<input type="checkbox" value="1" name="reply_retweet" id="input_replyretweet" <?php if($appuser["reply_retweet"] == 1) echo "checked" ?> <?= $readOnly ?> >
													<span class="toggle"></span>
													リプライをリツイート
													<span id="input_replyretweet_text"></span>
												</label>
											</div>
										</div>

										<div class="col-md-12 replyaction_box">
											<div class="togglebutton">
												<label>
													<input type="checkbox" value="1" name="is_replyreply" id="input_replyreply" <?php if($appuser["is_replyreply"] == 1) echo "checked" ?> <?= $readOnly ?> >
													<span class="toggle"></span>
													リプライにお返事
													<span id="input_replyreply_text"></span>
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>



<!--							フォロバ		-->
							<div class="card">
								<div class="card-body">
									<h4 class="card-title">その他</h4>
<!--									<p class="category">リプライがあった場合アクションを実行</p>-->
									<div class="row">

										<div class="col-md-12">
											<div class="togglebutton">
												<label>
													<input type="checkbox" value="1" name="followback" id="input_followback" <?php if($appuser["followback"] == 1) echo "checked" ?> <?= $readOnly ?> >
													<span class="toggle"></span>
													フォローバック
													<span id="input_followback_text"></span>
												</label>
											</div>
										</div>

										<div class="col-md-12">
											<div class="togglebutton">
												<label>
													<input type="checkbox" value="1" name="is_wasshoi" id="input_is_wasshoi" <?php if($appuser["is_wasshoi"] == 1) echo "checked" ?> <?= $readOnly ?> >
													<span class="toggle"></span>
													相互わっしょい
													<span id="input_is_wasshoi_text"></span>
												</label>
											</div>
										</div>

									</div>
								</div>
							</div>


						</div>




					<?php if($adminMode) { ?>
						<button type="submit" class="btn btn-primary pull-right">Update Profile</button>
					<?php } ?>

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

<?php if($adminMode) {?>
		</form>
<?php  }  ?>

</div>
