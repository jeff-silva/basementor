<?php

\Basementor\Basementor::action('basementor-settings-save', function($post) {
	$post->colors = stripslashes($post->colors);
	$post->colors = json_decode($post->colors);

	\Basementor\Options::set('color_dark', $post->color_dark);
	\Basementor\Options::set('color_light', $post->color_light);
	\Basementor\Options::set('colors', $post->colors);
	\Basementor\Options::save();

	wp_redirect($_SERVER['HTTP_REFERER']);
	return $post;
});

add_action('admin_menu', function() {
	add_menu_page('Theme Settings', 'Theme Settings', 'manage_options', 'basementor-settings-default', function() { ?>
	<br><div>Configurações do tema</div>
	<?php }, 'dashicons-admin-generic', 10);
	
	add_submenu_page('basementor-settings-default', 'Cores', 'Cores', 'manage_options', 'basementor-settings-colors', function() {
		$data = new stdClass;
		$data->color_dark = \Basementor\Options::get('color_dark');
		$data->color_light = \Basementor\Options::get('color_light');
		$data->colors = \Basementor\Options::get('colors');
		$data->input_color = false;
		?><br>

		<div id="basementor-settings">
			<form method="post" action="<?php echo \Basementor\Basementor::action('basementor-settings-save'); ?>">
				<div class="row">
					<div class="col-3" v-for="(color, name) in colors">
						<div class="card">
							<div class="card-header">{{ name }}</div>
							<div class="card-body">
								<div class="input-group border" :style="{borderColor:colors[name]+'!important'}">
									<input type="text" class="form-control" v-model="colors[name]" style="border:none; background:none;" @focus="input_color=name;">
									<div class="input-group-text" :style="{background:colors[name]}" style="border:none; border-radius:0px;"> &nbsp; </div>
								</div>
								<div v-if="input_color==name" style="position:absolute; z-index:9;">
									<input-color :value="color" @input="colors[name]=$event.hex;"></input-color>
								</div>
							</div>
						</div>
					</div>

					<div class="col-12 text-right pt-3">
						<button type="submit" class="btn btn-primary">
							<i class="fa fa-fw fa-save"></i> Salvar
						</button>
					</div>
				</div>
				<textarea style="display:none;" name="color_dark">{{ color_dark }}</textarea>
				<textarea style="display:none;" name="color_light">{{ color_light }}</textarea>
				<textarea style="display:none;" name="colors">{{ colors }}</textarea>
			</form>
		</div>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/vue-color/2.7.1/vue-color.min.js"></script>
		<script>new Vue({
			el: "#basementor-settings",
			data: <?php echo json_encode($data); ?>,
			components: {
				'input-color': window.VueColor.Chrome,
			},
		});</script>
		<?php
	});
});
