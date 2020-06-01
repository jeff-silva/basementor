<?php

/*
Name: Wooxcel
*/


if (isset($_GET['woocommerce-excel-media'])) {
	add_action('init', function() {
		if (! $media_url = wp_get_attachment_url($_GET['woocommerce-excel-media'])) {
			$media_url = 'https://www.ice-shop.dk/media/catalog/product/cache/1/image/378x380/9df78eab33525d08d6e5fb8d27136e95/placeholder/default/no_image_placeholder_6.png';
		}
		wp_redirect($media_url); die;
	});
}


function elementor_excel_products() {
	$products = get_posts([
		'post_type' => 'product',
		'posts_per_page' => -1,
		'post_status' => 'any',
	]);

	$products = array_map(function($prod) {
		$prod->_permalink = get_the_permalink($prod);

		$prod->meta_input = new \stdClass;
		$prod->meta_input->_thumbnail_id = get_post_meta($prod->ID, '_thumbnail_id', true);
		$prod->meta_input->_sku = get_post_meta($prod->ID, '_sku', true);
		$prod->meta_input->_regular_price = get_post_meta($prod->ID, '_regular_price', true);
		$prod->meta_input->_sale_price = get_post_meta($prod->ID, '_sale_price', true);
		$prod->meta_input->_product_image_gallery = get_post_meta($prod->ID, '_product_image_gallery', true);
		$prod->meta_input->_width = get_post_meta($prod->ID, '_width', true);
		$prod->meta_input->_height = get_post_meta($prod->ID, '_height', true);
		$prod->meta_input->_length = get_post_meta($prod->ID, '_length', true);
		$prod->meta_input->_weight = get_post_meta($prod->ID, '_weight', true);

		$prod->tax_input = new \stdClass;
		foreach(get_object_taxonomies(['post_type' => 'product']) as $taxo) {
			$prod->tax_input->{$taxo} = [];
			if ($terms = get_the_terms($prod->ID, $taxo) AND is_array($terms)) {
				$prod->tax_input->{$taxo} = array_map(function($term) {
					return $term->term_id;
				}, $terms);
			}
		}

		return $prod;
	}, $products);

	return $products;
}


\Basementor\Basementor::action('wooxcel-save', function($post) {
	$post->meta_input['_price'] = $post->meta_input['_regular_price'];
	if (isset($post->meta_input['_sale_price']) AND !empty($post->meta_input['_sale_price'])) {
		$post->meta_input['_price'] = $post->meta_input['_sale_price'];
	}

	$post->meta_input['_width'] = isset($post->meta_input['_width'])? $post->meta_input['_width']: 2;
	$post->meta_input['_height'] = isset($post->meta_input['_height'])? $post->meta_input['_height']: 11;
	$post->meta_input['_length'] = isset($post->meta_input['_length'])? $post->meta_input['_length']: 16;
	$post->meta_input['_weight'] = isset($post->meta_input['_weight'])? $post->meta_input['_weight']: 1;

	return [
		'saved' => wp_insert_post($post),
		'products' => elementor_excel_products(),
	];
});



\Basementor\Basementor::action('wooxcel-faker', function($post) {

	$_rand = function($items) {
		return $items[ array_rand($items) ];
	};


	$_silab = function() use($_rand) {
		$return = $_rand(str_split('          abcdefghijklmnopqrstuvwxyz'));
		$return .= $_rand(str_split('aeiou'));
		$return .= $_rand(str_split('          lmrsxz'));
		return trim($return);
	};


	$_word = function($len=3) use($_silab) {
		$silabs = [];
		for($x=1; $x<=$len; $x++) {
			$silabs[] = $_silab(rand(1, 5));
		}
		return implode('', $silabs);
	};

	$_words = function($len=5) use($_word) {
		$words = [];
		for($x=1; $x<=$len; $x++) {
			$words[] = $_word();
		}
		return implode(' ', $words);
	};

	$_download_image = function($url) {
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		include_once( ABSPATH . 'wp-admin/includes/image.php' );

		$data = new \stdClass;
		$data->url = $url;
		$data->filename = md5($url) .'.jpg';
		$uploaddir = wp_upload_dir();
		$data->uploadfile = $uploaddir['path'] . '/' . $data->filename;
		file_put_contents($data->uploadfile, file_get_contents($url));
		// dd($data); die;

		$wp_filetype = wp_check_filetype(basename($data->filename), null );
		$attach_id = wp_insert_attachment([
			'post_mime_type' => $wp_filetype['type'],
			'post_title' => $data->filename,
			'post_content' => '',
			'post_status' => 'inherit'
		], $data->uploadfile);

		$imagenew = get_post( $attach_id );
		$fullsizepath = get_attached_file( $imagenew->ID );
		$attach_data = wp_generate_attachment_metadata( $attach_id, $fullsizepath );
		wp_update_attachment_metadata( $attach_id, $attach_data );
		update_post_meta($imagenew->ID, 'basementor-faker', 1);
		return $imagenew->ID;
	};


	$inserts = [];
	for($n=0; $n<=$post->quantity; $n++) {
		$prod = [
			'post_title' => $_words(rand(3, 7)),
			'post_type' => 'product',
			'post_status' => 'publish',
			'post_excerpt' => $_words(rand(30, 30)),
			'post_content' => $_words(rand(50, 200)),
			'meta_input' => [
				'basementor-faker' => 1,
				'_regular_price' => number_format(rand(10, 999), 2, '.', ''),
				'_sale_price' => number_format(rand(0, 999), 2, '.', ''),
				'_thumbnail_id' => $_download_image('https://picsum.photos/300/300?rand='.rand(0, 999)),
				'_width' => rand(11, 30),
				'_height' => rand(11, 30),
				'_weight' => rand(11, 30),
				'_length' => rand(11, 30),
			],
		];

		if ($prod['meta_input']['_sale_price'] >= $prod['meta_input']['_regular_price']) {
			unset($prod['meta_input']['_sale_price']);
		}

		$prod['meta_input']['_price'] = $prod['meta_input']['_regular_price'];

		if (isset($prod['meta_input']['_sale_price']) AND !empty($prod['meta_input']['_sale_price'])) {
			$prod['meta_input']['_price'] = $prod['meta_input']['_sale_price'];
		}

		wp_insert_post($prod);
		$inserts[] = $prod;
	}

	return [
		'inserts' => $inserts,
		'products' => elementor_excel_products(),
	];
});



\Basementor\Basementor::action('wooxcel-faker-delete', function($post) {
	global $wpdb;

	$deletes = [];

	$products = get_posts([
		'post_type' => ['product', 'attachment'],
		'post_status' => 'any',
		'posts_per_page' => -1,
		'meta_query' => [
			[
				'key' => 'basementor-faker',
				'compare' => 'EXISTS',
			],
		],
	]);

	foreach($products as $prod) {
		wp_delete_post($prod->ID, true);
		$deletes[] = $prod->ID;
		$deletes[] = "delete from {$wpdb->postmeta} where post_id='{$prod->ID}'; ";
		$deletes[] = "delete from {$wpdb->post} where post_parent='{$prod->ID}'; ";
	}

	return [
		'deletes' => $deletes,
		'products' => elementor_excel_products(),
	];
});



add_action('admin_menu', function() {
	// add_submenu_page('woocommerce', 'Woo Excel', 'Woo Excel', 'manage_options', 'wooxcel', 'wooxcel_search');
	add_submenu_page('edit.php?post_type=product', 'Woo Excel', 'Woo Excel', 'manage_options', 'wooxcel', function() {
		$data = new \stdClass;
		$data->id = uniqid('wooxcel-');
		$data->saving = false;
		$data->product = false;
		$data->products = elementor_excel_products();
		$data->faker = false;
		$data->wp_editor = '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>';

		?><br><div id="<?php echo $data->id; ?>" class="pr-2">
			
			<wp-editor v-model="wp_editor"></wp-editor>

			<div class="list-inline text-right">
				<div class="list-inline-item">
					<a href="javascript:;" class="btn btn-outline-primary btn-sm">Baixar modelo excel</a>
				</div>

				<div class="list-inline-item">
					<a href="javascript:;" class="btn btn-outline-primary btn-sm">Importar excel</a>
				</div>

				<div class="list-inline-item">
					<a href="javascript:;" class="btn btn-outline-primary btn-sm" @click="faker={};">Faker</a>
				</div>
			</div><br>

			<div v-if="faker" style="position:fixed; top:0px; left:0px; width:100%; height:100%; background:#00000044; z-index:9; display: flex; align-items: center; justify-content: center;" @click.self="faker=false;">
				<div class="card">
					<div class="card-header">Gerador de produtos fake</div>
					<div class="card-body">
						<div class="form-group">
							<label>Quantos produtos devem ser gerados?</label>
							<div class="input-group border border-primary">
								<input type="number" class="form-control" v-model="faker.quantity" @keyup.enter="fakerGenerate();">
								<div class="input-group-btn">
									<button type="button" class="btn btn-primary" @click="fakerGenerate();">
										<span v-if="faker.generating"><i class="fa fa-fw fa-spin fa-spinner"></i> Gerando</span>
										<span v-else>Gerar</span>
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer text-right">
						<button type="button" class="btn btn-danger pull-left" @click="fakerDelete();">
							<span v-if="faker.deleting"><i class="fa fa-fw fa-spin fa-spinner"></i> Deletando</span>
							<span v-else>Deletar Fakes</span>
						</button>
					</div>
				</div>
			</div>

			<form action="" @submit.prevent="productSave();">
				<table class="table table-bordered table-striped">
					<colgroup>
						<col width="50px">
						<col width="50px">
						<col width="*">
						<col width="50px">
						<col width="50px">
						<col width="50px">
						<col width="50px">
						<col width="50px">
						<col width="50px">
						<col width="50px">
						<col width="50px">
						<col width="50px">
						<col width="50px">
						<col width="50px">
					</colgroup>
					<thead>
						<tr>
							<th class="p-2">ID</th>
							<th class="p-2">Thumb.</th>
							<th class="p-2">Título</th>
							<th class="p-2">SKU</th>
							<th class="p-2">Status</th>
							<th class="p-2">Preço</th>
							<th class="p-2">Promo</th>
							<th class="p-2">P</th>
							<th class="p-2">L</th>
							<th class="p-2">A</th>
							<th class="p-2">C</th>
							<th class="p-2">Galeria</th>
							<th class="p-2">Save</th>
							<th class="p-2">URL</th>
						</tr>
					</thead>

					<tbody>
						<tr v-for="p in products" :key="p.ID" @click="product=p;" :class="{'table-primary':p==product}">
							<td class="p-1">
								{{ p.ID }}
							</td>
							<td class="p-0">
								<media-picker
									v-model="p.meta_input._thumbnail_id"
									:multiple="false"
								></media-picker>
							</td>
							<td class="p-0">
								<input type="text" class="form-control form-control-sm" v-model="p.post_title">
							</td>
							<td class="p-0">
								<input type="text" class="form-control form-control-sm" v-model="p.meta_input._sku">
							</td>
							<td class="p-0">
								<input type="text" class="form-control form-control-sm" v-model="p.post_status">
							</td>
							<td class="p-0">
								<input type="text" class="form-control form-control-sm" v-model="p.meta_input._regular_price">
							</td>
							<td class="p-0">
								<input type="text" class="form-control form-control-sm" v-model="p.meta_input._sale_price">
							</td>
							<td class="p-0">
								<input type="number" class="form-control form-control-sm" v-model="p.meta_input._weight">
							</td>
							<td class="p-0">
								<input type="number" class="form-control form-control-sm" v-model="p.meta_input._width">
							</td>
							<td class="p-0">
								<input type="number" class="form-control form-control-sm" v-model="p.meta_input._height">
							</td>
							<td class="p-0">
								<input type="number" class="form-control form-control-sm" v-model="p.meta_input._length">
							</td>
							<td class="p-0">
								<media-picker
									v-model="p.meta_input._product_image_gallery"
									:multiple="true"
								></media-picker>
							</td>
							<td class="p-0">
								<button type="button" class="btn btn-primary btn-sm btn-block" @click="productSave();">
									<i class="fa fa-fw fa-spin fa-spinner" v-if="saving"></i>
									<i class="fa fa-fw fa-save" v-else></i>
								</button>
							</td>
							<td class="p-0">
								<a :href="p._permalink" class="btn btn-primary btn-sm btn-block" target="_blank">
									<i class="fa fa-fw fa-eye"></i>
								</a>
							</td>
						</tr>
					</tbody>
				</table>
				<input type="submit" style="display:none;">
			</form>

			<!-- <pre>$data: {{ $data }}</pre> -->
		</div>
		
		<script>Vue.component("media-picker", {
			props: {
				value: {default:() => ({})},
				multiple: {default:false},
			},

			data() {
				var data = {};
				data.modal = false;
				data.ids = this.$props.value.split(',').filter((item) => {
					return !!item;
				});
				return data;
			},

			methods: {
				emit() {
					this.$emit('value', this.value);
					this.$emit('input', this.value);
					this.$emit('change', this.value);
				},

				mediaPicker() {
					var media = wp.media({
						title: 'Select a image to upload',
						button: {text: 'Use this image'},
						multiple: this.multiple,
					});

					media.on('select', () => {
						media.state().get('selection').toJSON().forEach((file) => {
							if (this.multiple) { this.ids.push(file.id); }
							else { this.ids = [file.id]; }
						});
						
						this.value = this.ids.join(',');
						this.emit();
					});

					media.open();
				},

				mediaRemove(id) {
					var index = this.ids.indexOf(id);
					this.ids.splice(index, 1);
					this.value = this.ids.join(',');
					this.emit();
				},
			},

			template: `<div>
				<button type="button" class="btn btn-block btn-sm" @click="modal=true;" style="background:none;">
					<i class="fa fa-fw fa-image"></i>
					<span v-if="multiple">{{ ids.length }}</span>
				</button>

				<div v-if="modal" style="position:fixed; top:0px; left:0px; width:100%; height:100%; background:#00000044; z-index:9; display: flex; align-items: center; justify-content: center;" @click.self="modal=false;">
					<div class="card" style="width:400px;">
						<div class="card-header">Media</div>
						<div class="card-body">
							<button type="button" class="btn btn-primary btn-sm btn-block" @click="mediaPicker();">
								<div v-if="multiple"><i class="fa fa-fw fa-plus"></i> Add. Media</div>
								<div v-else>Alterar</div>
							</button><br />

							<div class="row">
								<div v-for="id in ids" :key="id" class="col-3 text-center mb-3" style="position:relative; overflow:hidden;">
									<a href="javascript:;" class="btn btn-sm btn-danger" @click="mediaRemove(id);" style="position:absolute; top:5px; right:5px; padding:1px 3px !important;">
										<i class="fa fa-fw fa-remove"></i>
									</a>
									<img :src="'?woocommerce-excel-media='+id" alt="" style="height:100px;" />
								</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>`,
		});

		new Vue({
			el: "#<?php echo $data->id; ?>",
			data: <?php echo json_encode($data); ?>,
			methods: {
				productSave() {
					var $=jQuery;
					if (!this.product) return;
					this.saving == true;
					$.post('<?php echo \Basementor\Basementor::action('wooxcel-save'); ?>', this.product, (resp) => {
						this.saving == false;
						this.products = resp.products;
					}, "json");
				},

				fakerGenerate() {
					var $=jQuery;
					this.$set(this.faker, "generating", true);
					$.post("<?php echo \Basementor\Basementor::action('wooxcel-faker'); ?>", this.faker, (resp) => {
						this.$set(this.faker, "generating", false);
						this.products = resp.products;
					}, "json");
				},

				fakerDelete() {
					if (! confirm('Tem certeza que deseja deletar fakes?')) return;
					var $=jQuery;
					this.$set(this.faker, "deleting", true);
					$.post("<?php echo \Basementor\Basementor::action('wooxcel-faker-delete'); ?>", this.faker, (resp) => {
						this.$set(this.faker, "deleting", false);
						this.products = resp.products;
					}, "json");
				},
			},
		});</script>

		<style>
		.form-control {border:none !important; background:none !important;}
		</style><?php

		wp_enqueue_media();
	});
}, 99);

