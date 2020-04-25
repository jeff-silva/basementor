<?php

namespace Basementor;

class Options
{
	static $defaults = [
		'test' => 1,
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
}