<?php

namespace Basementor;

class ApiWc
{
	static function cartAdd($input=['product_id'=>'']) {
		WC()->cart->add_to_cart($input['product_id'], $input['quantity'], $input['variation_id']);
		return self::cartData();
	}


	static function cartUpdate($input=['key'=>'', 'quantity'=>'']) {
		WC()->cart->set_quantity($input->key, $input->quantity);
		return self::cartData();
	}


	static function cartRemove($data=['key'=>'']) {
		return ['?'];
	}


	static function cartData($data=[]) {
		$cart = WC()->cart;
		if (! $cart) { throw new \Exception('Variable $cart not defined'); }

		$data = new \stdClass;
		$data->total = $cart->total;
		$data->total_html = $cart->get_total();

		$data->items = [];
		foreach ($cart->get_cart() as $key=>$item) {
			$prod = wc_get_product($item['product_id']);
			$data->items[] = [
				'product_id' => $item['product_id'],
				'quantity' => $item['quantity'],
				'price' => get_post_meta($item['product_id'], '_price', true),
				'price_html' => $cart->get_product_price($item['data']),
				'permalink' => get_the_permalink($item['product_id']),
				'thumbnail' => wp_get_attachment_image_url($prod->get_image_id(), 'full'),
			];
		}

		return $data;
	}
}
