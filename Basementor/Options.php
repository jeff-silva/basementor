<?php

namespace Basementor;

class Options
{
	static $defaults = [
		'color_dark' => '-30',
		'color_light' => '30',
		'colors' => [
			'primary' => '#007bff',
			'secondary' => '#6c757d',
			'success' => '#28a745',
			'danger' => '#dc3545',
			'warning' => '#ffc107',
			'info' => '#17a2b8',
			'facebook' => '#3b5999',
			'twitter' => '#55acee',
			'linkedin' => '#0077b5',
			'skype' => '#00aff0',
			'dropbox' => '#007ee5',
			'wordpress' => '#21759b',
			'vimeo' => '#1ab7ea',
			'vk' => '#4c75a3',
			'tumblr' => '#34465d',
			'yahoo' => '#410093',
			'pinterest' => '#bd081c',
			'youtube' => '#cd201f',
			'reddit' => '#ff5700',
			'quora' => '#b92b27',
			'soundcloud' => '#ff3300',
			'whatsapp' => '#25d366',
			'instagram' => '#e4405f',
		],
	];

	static $options = false;

	static function defaults() {
		$options = get_option('basementor-options');
		$options = json_decode($options, true);
		$options = is_array($options)? $options: [];
		return array_merge(self::$defaults, $options);
	}

	static function get($key) {
		if (self::$options==false) {
			self::$options = self::defaults();
		}

		return isset(self::$options[$key])? self::$options[$key]: null;
	}

	static function set($key, $value) {
		if (self::$options==false) {
			self::$options = self::defaults();
		}

		self::$options[$key] = $value;
	}

	static function save() {
		update_option('basementor-options', json_encode(self::$options));
	}


	static function color($hex, $add=null) {
		if ($add!==null) {
			$hex = preg_replace('/[^0-9a-zA-Z]/', '', $hex);
			$hex = str_split($hex, 2);
			$hex = array_map(function($val) use($add) {
				$val = hexdec($val);
				$val += intval($add);
				$val = min(max($val, 0), 255);
				return str_pad(dechex($val), 2, '0', STR_PAD_LEFT);
			}, $hex);
			$hex = '#'. implode('', $hex);
		}
		return $hex;
	}
}