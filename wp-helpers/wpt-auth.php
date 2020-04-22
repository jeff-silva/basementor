<?php

if (!function_exists('wpt_auth')): function wpt_auth() {

	$data = new stdClass;
	$data->id = uniqid('wpt-auth-');
	$data->tab = isset($_GET['tab'])? $_GET['tab']: 'login';

	$data->login = [
		'loading' => false,
		'resp' => false,
		'post' => [
			'email' => '',
			'pass' => '',
		],
	];

	?>
	<div id="<?php echo $data->id; ?>">
		<pre>$data: {{ $data }}</pre>
	</div>

	<script>new Vue({
		el: "#<?php echo $data->id; ?>",
		data: <?php echo json_encode($data); ?>,
	});</script>
	<?php

} endif;