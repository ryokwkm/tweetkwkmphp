

<div class="sidebar" data-color="purple" data-background-color="white" data-image="/assets/img/sidebar-1.jpg">
	<!--
		Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

		Tip 2: you can also add an image using data-image tag
-->
	<div class="logo">
		<a href="/" class="simple-text logo-normal">
			<?= $site_title ?>
		</a>
	</div>
	<div class="sidebar-wrapper">
		<ul class="nav">
			<?php foreach($my_pages as $page) {
				if(!empty($page["title_only"])) {
					continue;
				}
				$active = "";
				if(strpos($_SERVER['REQUEST_URI'],$page["url"]) === 0) {
					$active = " active";
				}
				// urlがデフォルトの時の対応
				if($_SERVER['REQUEST_URI'] == "/" && isset($page["default"]) && $page["default"] == true) {
					$active = " active";
				}
				?>
				<li class="nav-item  <?= $active ?>">
					<a class="nav-link" href="<?= $page["url"] ?>">
						<i class="material-icons"><?= $page["icon"] ?></i>
						<p><?= $page["title"] ?></p>
					</a>
				</li>
			<?php } ?>

					<?php
					if(IsAdminIP()) { ?>
						<?php if( !empty($login)) { ?>
							<li class="nav-item active-pro ">
								<a class="nav-link" href="/auth/logout">
									<i class="material-icons">logout</i>
									<p>logout</p>
								</a>
							</li>
						<?php } else { ?>

							<li class="nav-item ">
								<a class="nav-link" href="/mypage/index">
									<i class="material-icons">mood</i>
									<p>ログイン</p>
								</a>
							</li>
							<li class="nav-item ">
								<a class="nav-link" href="/auth/regist">
									<i class="material-icons">mood</i>
									<p>BOTを新規作成</p>
								</a>
							</li>
							<li class="nav-item ">
								<a class="nav-link" href="/auth/sisters_regist">
									<i class="material-icons">mood</i>
									<p>Sistersを追加</p>
								</a>
							</li>
						<?php } ?>
					<?php } ?>


				</li>
		</ul>
	</div>
</div>
