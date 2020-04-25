<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;
global $wpdb, $wp_query;


function build_query_url($merge=[], $excepts=[]) {
	$data = array_merge($_GET, $merge);
	foreach($excepts as $except) {
		if (isset($data[ $except ])) {
			unset($data[ $except ]);
		}
	}

	if (empty($data)) return '';
	return '?' . http_build_query($data);
}

function hierarchical_category_tree($cat=null, $level=0, $return=[]) {
	$params = ['hide_empty'=>false, 'orderby'=>'name', 'order'=>'ASC', 'parent'=>$cat];
	foreach(get_terms('product_cat', $params) as $item) {
		$return[] = children_default([
			'key' => 'product_cat',
			'value' => $item->slug,
			'title' => $item->name,
			'children' => hierarchical_category_tree($item->term_id, $level+1),
		]);
	}
	return $return;
}

function children_default($data=[]) {
	$item = (object) array_merge([
		'key' => '',
		'value' => '',
		'title' => '',
		'show' => false,
		'children' => [],
		'selecteds' => [],
	], $data);

	if ($item->key AND isset($_GET[$item->key])) {
		$selecteds = $_GET[$item->key];
		$selecteds = explode(',', $selecteds);
		$selecteds = array_filter($selecteds, 'strlen');
		$item->selecteds = array_values($selecteds);
	}

	return $item;
}

get_header( 'shop' ); ?>

<div class="container">
	<?php do_action( 'woocommerce_before_main_content' ); ?>
	
	<?php /*if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
	<h1 class="m-0 p-0"><?php woocommerce_page_title(); ?></h1>
	<?php endif;*/ ?>

	<?php // do_action( 'woocommerce_archive_description' ); ?>
	<br><br>

	<style>
	.wpt-product-search {}
	.wpt-product-search .card-header {font-weight:bold; text-transform:uppercase; background:#f5f5f5;}
	.wpt-product-search .card-header, .wpt-product-search .card-header * {color:#444; text-decoration:none !important;}
	.wpt-product-search .card-body {padding:10px 10px; border:none !important; max-height:400px; overflow:auto;}
	.wpt-product-search .card {border: solid 1px #eee !important;}

	.input-woocommerce-taxonomies {}
	.input-woocommerce-taxonomies a {color:#666;}
	.input-woocommerce-taxonomies-each {}
	.input-woocommerce-taxonomies-each-level-0 > 
	.input-woocommerce-taxonomies-each-title {background:#eee; text-transform:uppercase; font-weight:600;}
	.input-woocommerce-taxonomies-each-title {}
	</style>

	<form action="<?php echo get_permalink(woocommerce_get_page_id('shop')); ?>">
		<div class="row no-gutters">
			<div class="col-12 col-md-4 col-lg-3 wpt-product-search">
				<?php

				$input = array_merge([
					's' => '',
					'price' => [0, 1000],
				], $_GET);

				if (isset($_GET['min_price'])) { $input['price'][0] = $_GET['min_price']; }
				if (isset($_GET['max_price'])) { $input['price'][1] = $_GET['max_price']; }

				$data = new stdClass;
				$data->id = uniqid('wpt-product-search-');
				$data->show_filter = false;
				$data->max_value = $value = $wpdb->get_var("select max(cast(meta_value as unsigned)) as price from {$wpdb->prefix}postmeta where meta_key='_regular_price' limit 1");
				$data->input = $input;
				$data->sections = [];

				$data->input['product_cat'] = isset($_GET['product_cat'])? $_GET['product_cat']: '';
				$data->sections[] = children_default([
					'key' => 'product_cat',
					'title' => 'Categorias',
					'children' => hierarchical_category_tree(),
				]);

				foreach(wc_get_attribute_taxonomies() as $attr) {
					$sec = children_default([
						'key' => "pa_{$attr->attribute_name}",
						'title' => ucfirst($attr->attribute_label),
					]);

					$data->input[$sec->key] = isset($_GET[$sec->key])? $_GET[$sec->key]: '';

					foreach(get_terms(wc_attribute_taxonomy_name($attr->attribute_name), 'orderby=name&hide_empty=0') as $child) {
						$sec->children[] = children_default([
							'key' => $sec->key,
							'value' => $child->slug,
							'title' => $child->name,
						]);
					}

					$data->sections[] = $sec;
				}

				?>
				<div id="<?php echo $data->id; ?>">
					<div class="d-block d-md-none">
						<a href="javascript:;" class="btn btn-block btn-primary" @click="show_filter=!show_filter;">
							Filtrar
						</a><br>
					</div>
					<div class="d-md-block" :class="{'d-none':!show_filter}">
						<div class="card">
							<div class="card-header">Busca</div>
							<div class="card-body">
								<div class="input-group">
									<input type="text" name="s" v-model="input.s" class="form-control">
									<div class="input-group-btn">
										<button type="submit" class="btn btn-default">
											<i class="fa fa-fw fa-search"></i>
										</button>
									</div>
								</div>
							</div>


							<div class="card-header">Preço</div>
							<div class="card-body">
								<input type="hidden" name="min_price" :value="input.price[0]">
								<input type="hidden" name="max_price" :value="input.price[1]">
								<vue-slider v-model="input.price" style="margin:40px 35px 0px 25px;" :min="0" :max="max_value" :step="50">
									<template v-slot:tooltip="{ value, focus }">
										<div class="vue-slider-dot-tooltip vue-slider-dot-tooltip-top vue-slider-dot-tooltip-show">
											<div class="vue-slider-dot-tooltip-inner vue-slider-dot-tooltip-inner-top">
												<span class="vue-slider-dot-tooltip-text">R$ {{ value }},00</span>
											</div>
										</div>
									</template>
								</vue-slider>
							</div>

							<template v-for="sec in sections">
								<input type="hidden" :name="sec.key" :value="sec.selecteds.join(',')">
							</template>

							<input-woocommerce-taxonomies
								:items="sections"
								v-model="input"
								@change="input = $event;"
							></input-woocommerce-taxonomies>
						</div>

						<br>
						<div class="row">
							<div class="col-6">
								<a href="<?php echo get_permalink(woocommerce_get_page_id('shop')); ?>" class="btn btn-default btn-block">Limpar</a>
							</div>

							<div class="col-6">
								<button type="submit" class="btn btn-primary btn-block">Buscar</button>
							</div>
						</div>
						<br>
					</div>
				</div>

				<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vue-slider-component@latest/theme/default.css">
				<script src="https://cdn.jsdelivr.net/npm/vue-slider-component@latest/dist/vue-slider-component.umd.min.js"></script>

				<script>
				Vue.component('input-woocommerce-taxonomies', {
					props: {
						value: {default: () => ([])},
						items: {default: () => ([])},
						level: {default:0},
						name: {default:''},
					},

					data() {
						return {
							// props: Object.assign({}, this.$props),
						};
					},

					methods: {
						check(name, item) {
							if (! name) return;
							this.value[name] = item.value;
							this.emit(this.value);
						},

						emit(value) {
							console.log('emit', value);
						},
					},

					template: `<div class="input-woocommerce-taxonomies">
						<template v-if="level==0">
							<template v-for="item in items">
								<input type="hidden" :name="item.key" v-model="value[item.key]" />
							</template>
						</template>

						<div v-for="item in items" class="input-woocommerce-taxonomies-each" :class="'input-woocommerce-taxonomies-each-level-'+level">
							<div class="row no-gutters p-2 input-woocommerce-taxonomies-each-title">
								<div class="col-1"  v-if="level>0" @click="check(name, item);">
									<i class="fa fa-fw fa-check-square-o"
										v-if="value[item.key]==item.value"
									></i>

									<i class="fa fa-fw fa-square-o"
										v-else
									></i>
								</div>
								<div class="col pl-1">
									<a href="javascript:;"
										v-if="level==0"
										@click="item.show=!item.show;"
									>{{ item.title }}</a>

									<span v-else
										@click="check(name, item);"
									>{{ item.title }}</span>
								</div>
								<div class="col-1 text-right">
									<a href="javascript:;">
										<i class="fa fa-fw fa-chevron-left"
											:class="{'fa-rotate-270':item.show}"
											@click="item.show=!item.show;"
											v-if="item.children.length>0"
										></i>
									</a>
								</div>
							</div>
							<div class="pl-2">
								<input-woocommerce-taxonomies
									v-model="value"
									:items="item.children"
									:level="level+1"
									:name="item.key"
									v-if="item.show"
									@change="emit(item);"
								></input-woocommerce-taxonomies>
							</div>
						</div>
					</div>`,
				});

				new Vue({
					el: "#<?php echo $data->id; ?>",
					data: <?php echo json_encode($data); ?>,
					components: {
						vueSlider: window['vue-slider-component'],
					},
				});</script>

				<style>#<?php echo $data->id; ?> * {transition: all 300ms ease;}</style>
				<?php // do_action('woocommerce_sidebar'); ?>
			</div>

			<div class="col-12 col-md-8 col-lg-9 pt-3 pt-md-0 pl-md-3">
				<div><?php do_action( 'woocommerce_before_shop_loop' ); ?></div>
				<br><br>
				<div class="clearfix"></div>

				<?php
				if (woocommerce_product_loop()) {
					woocommerce_product_loop_start();
					if (wc_get_loop_prop('total')) {
						while(have_posts()) {
							the_post();
							do_action('woocommerce_shop_loop');
							wc_get_template_part('content', 'product');
						}
					}
					woocommerce_product_loop_end();
				}

				else {
					do_action('woocommerce_no_products_found');
				}
				?>

				<br>
				<div class="d-flex">
					<div class="mx-auto">
						<?php \Basementor\Ui::pagination($wp_query, get_permalink(wc_get_page_id('shop'))); ?>
					</div>
				</div>
			</div>
		</div>
	</form>

	<?php do_action( 'woocommerce_after_main_content' ); ?>
	<?php // dd($wp_query); ?>
</div>

<?php get_footer('shop'); ?>