<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
	<div class="container-fluid">
		<div class="navbar-wrapper">
			<?php
			$pageTitle = "";
			foreach($my_pages as $page) {
				if (strpos($_SERVER['REQUEST_URI'], $page["url"]) === 0) {
					$pageTitle = $page["title"];
				}
			}
			?>
			<a class="navbar-brand" href="/auth/index"><?= $pageTitle; ?></a>
		</div>


		<button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
			<span class="sr-only">Toggle navigation</span>
			<span class="navbar-toggler-icon icon-bar"></span>
			<span class="navbar-toggler-icon icon-bar"></span>
			<span class="navbar-toggler-icon icon-bar"></span>
		</button>

		<div class="collapse navbar-collapse justify-content-end">

			<ul class="navbar-nav">
				<li class="nav-item dropdown">

				</li>
			</ul>
		</div>

	</div>
</nav>
