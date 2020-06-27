<?php

// {user_login:'', user_password:''}
\Basementor\Basementor::action('login', function($post) {
	return wp_signon((array) $post);
});


add_shortcode('basementor-auth', function($data=[], $content=null) {
	$data = (object) shortcode_atts([
		'login_show' => true,
		'register_show' => true,
		'password_show' => true,
		'view' => 'login',
	], $data);

	$data->id = uniqid('basementor-auth-');

	$data->login = [
		'loading' => false,
		'post' => (object) [],
	];

	$data->register = [
		'loading' => false,
		'post' => (object) [],
	];

	$data->password = [
		'loading' => false,
		'post' => (object) [],
	];

	ob_start(); ?>

	<div id="<?php echo $data->id; ?>" style="max-width:400px; margin:0 auto;">
		<div class="card" v-if="view=='login'">
			<div class="card-header">Login</div>
			<div class="card-body">
				Aaa
			</div>
		</div>


		<div class="card" v-if="view=='register'">
			<div class="card-header">Cadastre-se</div>
			<div class="card-body">
				Aaa
			</div>
		</div>


		<div class="card" v-if="view=='password'">
			<div class="card-header">Esqueci minha senha</div>
			<div class="card-body">
				Aaa
			</div>
		</div>

		<br>
		<div class="row">
			<div class="col">
				<a href="javascript:;"
					class="btn btn-default btn-block"
					:class="{'btn-primary':view=='login'}"
					@click="view='login';"
				>Login</a>
			</div>

			<div class="col">
				<a href="javascript:;"
					class="btn btn-default btn-block"
					:class="{'btn-primary':view=='register'}"
					@click="view='register';"
				>Register</a>
			</div>

			<div class="col">
				<a href="javascript:;"
					class="btn btn-default btn-block"
					:class="{'btn-primary':view=='password'}"
					@click="view='password';"
				>Password</a>
			</div>
		</div>
		
		<pre>$data: {{ $data }}</pre>
	</div>

	<script>new Vue({
		el: "#<?php echo $data->id; ?>",
		data: <?php echo json_encode($data); ?>,
	});</script>

	<?php return ob_get_clean();
});