

<div class="sidebar" data-color="purple" data-background-color="white" data-image="/assets/img/sidebar-1.jpg">
	<!--
		Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

		Tip 2: you can also add an image using data-image tag
-->
	<div class="logo">
		<a href="/auth/index" class="simple-text logo-normal">
			管理画面
		</a>
	</div>
	<div class="sidebar-wrapper">
		<ul class="nav">
			<?php foreach($my_pages as $page) {
				$active = "";
				if(strpos($_SERVER['REQUEST_URI'],$page["url"]) === 0) {
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
<!--			<li class="nav-item  ">-->
<!--				<a class="nav-link" href="./dashboard.html">-->
<!--					<i class="material-icons">dashboard</i>-->
<!--					<p>Dashboard</p>-->
<!--				</a>-->
<!--			</li>-->
<!--			<li class="nav-item active ">-->
<!--				<a class="nav-link" href="./user.html">-->
<!--					<i class="material-icons">person</i>-->
<!--					<p>User Profile</p>-->
<!--				</a>-->
<!--			</li>-->
<!--			<li class="nav-item ">-->
<!--				<a class="nav-link" href="./tables.html">-->
<!--					<i class="material-icons">content_paste</i>-->
<!--					<p>Table List</p>-->
<!--				</a>-->
<!--			</li>-->
<!--			<li class="nav-item ">-->
<!--				<a class="nav-link" href="./typography.html">-->
<!--					<i class="material-icons">library_books</i>-->
<!--					<p>Typography</p>-->
<!--				</a>-->
<!--			</li>-->
<!--			<li class="nav-item ">-->
<!--				<a class="nav-link" href="./icons.html">-->
<!--					<i class="material-icons">bubble_chart</i>-->
<!--					<p>Icons</p>-->
<!--				</a>-->
<!--			</li>-->
<!--			<li class="nav-item ">-->
<!--				<a class="nav-link" href="./map.html">-->
<!--					<i class="material-icons">location_ons</i>-->
<!--					<p>Maps</p>-->
<!--				</a>-->
<!--			</li>-->
<!--			<li class="nav-item ">-->
<!--				<a class="nav-link" href="./notifications.html">-->
<!--					<i class="material-icons">notifications</i>-->
<!--					<p>Notifications</p>-->
<!--				</a>-->
<!--			</li>-->
<!--			<li class="nav-item ">-->
<!--				<a class="nav-link" href="./rtl.html">-->
<!--					<i class="material-icons">language</i>-->
<!--					<p>RTL Support</p>-->
<!--				</a>-->
<!--			</li>-->
<!--			<li class="nav-item active-pro ">-->
<!--				<a class="nav-link" href="./upgrade.html">-->
<!--					<i class="material-icons">unarchive</i>-->
<!--					<p>Upgrade to PRO</p>-->
<!--				</a>-->
<!--			</li>-->

				<li class="nav-item active-pro ">
					<?php if( !empty($login)) { ?>
						<a class="nav-link" href="/auth/logout">
							<i class="material-icons">logout</i>
							<p>logout</p>
						</a>
					<?php } else { ?>
						<a class="nav-link" href="/mypage/index">
							<i class="material-icons">mood</i>
							<p>ログイン</p>
						</a>
					<?php } ?>

				</li>
		</ul>
	</div>
</div>
