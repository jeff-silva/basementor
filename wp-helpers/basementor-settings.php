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



	add_submenu_page('basementor-settings-default', 'Ajuda', 'Ajuda', 'manage_options', 'basementor-settings-help', function() {

		$_data_item = function($title, $content) {
			global $basementor_settings_default_items_index;
			$basementor_settings_default_items_index++;

			ob_start();
			call_user_func($content);
			$content = ob_get_clean();

			return [
				'id' => sanitize_title($title)."-{$basementor_settings_default_items_index}",
				'title' => $title,
				'content' => $content,
			];
		};

		$data = new \stdClass;
		$data->categories = [];
		$data->items = [];

		$data->items[] = $_data_item('Como encontar faixa de CEP?', function() { ?>
		Acesse a <a href="http://www.buscacep.correios.com.br/sistemas/buscacep/buscaFaixaCep.cfm" target="_blank">busca de faixa de CEP dos correios</a>
		<?php });

		$data->items[] = $_data_item('Mensagem "Não existem métodos de entrega disponíveis"', function() { ?>
		Talvez os correios tenham mudado NOVAMENTE seus códigos de entrega. <br>Mas isso pode ser configurado acessando as
		<a href="<?php echo admin_url('/admin.php?page=wc-settings&tab=shipping'); ?>">configurações de entrega</a> e alterando seus respectivos códigos.
		<br><a href="https://wordpress.org/support/topic/solucao-nao-existe-nenhum-metodo-de-entrega-disponivel/" target="_blank">Fonte</a>
		<?php });

		$data->item = $data->items[0];
		?><br>

		<div id="basementor-settings">
			<div class="accordion" id="accordionExample">
				<div class="card" v-for="i in items">
					<div class="card-header">
						<h2 class="mb-0">
							<button class="btn btn-link" type="button" @click="item=i;">
								{{ i.title }}
							</button>
						</h2>
					</div>

					<div class="collapse" :class="{'show':i.id==item.id}">
						<div class="card-body" v-html="i.content"></div>
					</div>
				</div>
			</div>
		</div>

		<script>new Vue({
			el: "#basementor-settings",
			data: <?php echo json_encode($data); ?>,
		});</script>
		<?php
	});
});
