<?php

/*
Name: Wooxcel
*/


if (isset($_GET['media'])) {
	add_action('init', function() {
		if (! $media_url = wp_get_attachment_url($_GET['media'])) {
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
	return [
		'saved' => wp_insert_post($post),
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


		/*
		public function taxonomyTerms($taxonomy, $parent=0, $prefix='•', $terms=[], $return=[], $level=0) {
			if (empty($terms)) {
				$terms = get_terms([
					'taxonomy' => $taxonomy,
					'orderby' => 'name',
					'hide_empty' => false,
					'hierarchical' => 1,
				]);
			}

			foreach($terms as $term) {
				if ($term->parent==$parent) {
					$term->name = ltrim(str_repeat($prefix, $level) ." {$term->name}");
					$return[] = $term;
					$return = $this->taxonomyTerms($taxonomy, $term->term_id, $prefix, $terms, $return, $level+1);
				}
			}

			return $return;
		}


		public function taxonomies() {
			$not = [
				'product_type',
				'product_visibility',
				'product_shipping_class',
				'product_tag',
			];

			$return = [];
			foreach(get_object_taxonomies(['post_type' => $this->post_type]) as $taxo) {
				if (in_array($taxo, $not)) continue;
				
				$taxo = get_taxonomy($taxo);
				$return[] = (object) [
					'slug' => $taxo->name,
					'name' => $taxo->label,
					'terms' => $this->taxonomyTerms($taxo->name),
				];
			}
			return $return;
		}
		*/



		?><br><div id="<?php echo $data->id; ?>">
			<div class="list-inline text-right">
				<div class="list-inline-item">
					<a href="javascript:;" class="btn btn-outline-primary btn-sm">Baixar modelo excel</a>
				</div>

				<div class="list-inline-item">
					<a href="javascript:;" class="btn btn-outline-primary btn-sm">Importar excel</a>
				</div>
			</div><br>

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
									<img :src="'?media='+id" alt="" style="height:100px;" />
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
						console.log(resp);
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

