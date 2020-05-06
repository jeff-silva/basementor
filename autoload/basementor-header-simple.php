<?php

if (! BASEMENTOR_ELEMENTOR) { return; }

add_action('elementor/widgets/widgets_registered', function($manager) {
	if (class_exists('Basementor_Header_Simple')) return;

	class Basementor_Header_Simple_Walker extends Walker_Nav_Menu {
	    private $dropdown = false;

	    public function start_lvl( &$output, $depth = 0, $args = array() ) {
	      if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
	        $t = '';
	        $n = '';
	      } else {
	        $t = "\t";
	        $n = "\n";
	      }

	      $this->dropdown = true;
	      $output         .= $n . str_repeat( $t, $depth ) . '<div class="dropdown-menu" role="menu">' . $n;
	    }

	    public function end_lvl( &$output, $depth = 0, $args = array() ) {
	      if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
	        $t = '';
	        $n = '';
	      } else {
	        $t = "\t";
	        $n = "\n";
	      }

	      $this->dropdown = false;
	      $output         .= $n . str_repeat( $t, $depth ) . '</div>' . $n;
	    }

	    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
	      if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
	        $t = '';
	        $n = '';
	      } else {
	        $t = "\t";
	        $n = "\n";
	      }

	      $indent = str_repeat( $t, $depth );

	      if ( 0 === strcasecmp( $item->attr_title, 'divider' ) && $this->dropdown ) {
	        $output .= $indent . '<div class="dropdown-divider"></div>' . $n;
	        return;
	      } elseif ( 0 === strcasecmp( $item->title, 'divider' ) && $this->dropdown ) {
	        $output .= $indent . '<div class="dropdown-divider"></div>' . $n;
	        return;
	      }

	      $classes   = empty( $item->classes ) ? array() : (array) $item->classes;
	      $classes[] = 'menu-item-' . $item->ID;
	      $classes[] = 'nav-item';

	      if ( $args->walker->has_children ) {
	        $classes[] = 'dropdown';
	      }

	      if ( 0 < $depth ) {
	        $classes[] = 'dropdown-menu';
	      }

	      $args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );
	      $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
	      $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
	      // $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
	      // $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

	      if ( !$this->dropdown ) {
	        $output .= $indent . '<li' . $class_names . '>' . $n . $indent . $t;
	      }

	      $atts           = array();
	      $atts['title']  = !empty( $item->attr_title ) ? $item->attr_title : '';
	      $atts['target'] = !empty( $item->target ) ? $item->target : '';
	      $atts['rel']    = !empty( $item->xfn ) ? $item->xfn : '';
	      $atts['href']   = !empty( $item->url ) ? $item->url : '';
	      $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

	      if ( $args->walker->has_children ) {
	        $atts['data-toggle']   = 'dropdown';
	        $atts['aria-haspopup'] = 'true';
	        $atts['aria-expanded'] = 'false';
	      }

	      $attributes = '';
	      foreach ( $atts as $attr => $value ) {
	        if ( !empty( $value ) ) {
	          $value      = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
	          $attributes .= ' ' . $attr . '="' . $value . '"';
	        }
	      }

	      $title = apply_filters( 'the_title', $item->title, $item->ID );
	      $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
	      $item_classes = array( 'nav-link' );

	      if ( $args->walker->has_children ) {
	        $item_classes[] = 'dropdown-toggle';
	      }

	      if ( 0 < $depth ) {
	        $item_classes = array_diff( $item_classes, [ 'nav-link' ] );
	        $item_classes[] = 'dropdown-item';
	      }

	      $item_output = $args->before;
	      $item_output .= '<a class="' . implode( ' ', $item_classes ) . '" ' . $attributes . '>';
	      $item_output .= $args->link_before . $title . $args->link_after;
	      $item_output .= '</a>';
	      $item_output .= $args->after;
	      $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	    }

	    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
	      if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
	        $t = '';
	        $n = '';
	      } else {
	        $t = "\t";
	        $n = "\n";
	      }

	      $output .= $this->dropdown ? '' : str_repeat( $t, $depth ) . '</li>' . $n;
	    }

	    public static function fallback( $args ) {
	      if ( current_user_can( 'edit_theme_options' ) ) {

	        $defaults = array(
	            'container'       => 'div',
	            'container_id'    => false,
	            'container_class' => false,
	            'menu_class'      => 'menu',
	            'menu_id'         => false,
	        );
	        $args     = wp_parse_args( $args, $defaults );
	        if ( !empty( $args['container'] ) ) {
	          echo sprintf( '<%s id="%s" class="%s">', $args['container'], $args['container_id'], $args['container_class'] );
	        }
	        echo sprintf( '<ul id="%s" class="%s">', $args['container_id'], $args['container_class'] ) .
	        '<li class="nav-item">' .
	        '<a href="' . admin_url( 'nav-menus.php' ) . '" class="nav-link">' . __( 'Add a menu' ) . '</a>' .
	        '</li></ul>';
	        if ( !empty( $args['container'] ) ) {
	          echo sprintf( '</%s>', $args['container'] );
	        }
	      }
	    }
	}



	class Basementor_Header_Simple extends \Elementor\Widget_Base {

		public function get_name() {
			return __CLASS__;
		}

		public function get_title() {
			return preg_replace('/[^a-zA-Z0-9]/', ' ', __CLASS__);
		}

		// https://ecomfe.github.io/eicons/demo/demo.html
		public function get_icon() {
			return 'eicon-editor-code';
		}

		public function get_categories() {
			return [ 'general' ];
		}

		public function get_script_depends() {
			return [];
		}

		public function get_style_depends() {
			return [];
		}

		protected function _register_controls() {
			$this->start_controls_section('Basementor_Header_Simple_css', [
				'label' => 'CSS',
			]);

			$this->add_control('logo', [
				'label' => 'Logo',
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => ['url'=>''],
				'label_block' => true,
			]);

			$this->add_control('nav', [
				'label' => 'Menu',
				'type' => \Elementor\Controls_Manager::SELECT2,
				'default' => '',
				'label_block' => true,
				'options' => $this->menus(),
			]);

			$this->add_control('css', [
				'label' => 'CSS',
				'type' => \Elementor\Controls_Manager::CODE,
				'default' => '$root {}',
				'label_block' => true,
			]);

			$this->end_controls_section();
		}

		protected function render() {
			$set = json_decode(json_encode($this->get_settings()));
			$set->id = uniqid('basementor-header-simple-');
			?>
			<nav class="<?php echo $set->id; ?> navbar navbar-expand-lg navbar-light bg-light shadow fixed-top">
				<div class="container">

					<?php if ($set->logo->url): ?>
					<a href="<?php echo site_url(); ?>" class="navbar-brand">
						<img src="<?php echo $set->logo->url; ?>" alt="" style="width:100%; max-width:200px;">
					</a>
					<?php endif; ?>

					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#<?php echo $set->id; ?>" aria-controls="<?php echo $set->id; ?>" style="outline:none!important; border:none;">
						<span class="navbar-toggler-icon"></span>
					</button>

					<div class="collapse navbar-collapse" id="<?php echo $set->id; ?>">
						<ul class="navbar-nav mr-auto"><?php wp_nav_menu([
							'menu' => $set->nav,
							'container' => '',
							'items_wrap' => '%3$s',
							'walker' => new Basementor_Header_Simple_Walker(),
						]); ?></ul>

						<div class="row no-gutters align-items-center">
							<div class="col">
								<form class="form-inline my-2 my-lg-0">
									<div class="input-group border border-primary" style="background:#fff;">
										<input class="form-control" type="search" placeholder="Pesquisar..." aria-label="Search" style="border:none; background:none;">
										<div class="input-group-btn">
											<button type="submit" class="btn btn-primary" style="border:none; border-radius:0px;">
												<i class="fa fa-fw fa-search"></i>
											</button>
										</div>
									</div>
								</form>
							</div>

							<?php $user = wp_get_current_user(); if ($user->ID):
							$user->data->avatar = get_avatar_url($user->ID);

							$options = [];

							if (in_array('administrator', $user->roles)) {
								$options[] = (object) ['title'=>'Admin', 'url'=>admin_url()];
							}

							if (BASEMENTOR_WOOCOMMERCE) {
								if ($page = wc_get_page_id('myaccount')) {
									$page = get_post($page);
									$options[] = (object) ['title'=>$page->post_title, 'url'=>get_the_permalink($page)];
								}
							}

							if (!empty($options)): ?>
							<div class="col-3 text-right">
								<div class="dropdown ml-3">
									<button class="dropdown-toggle" type="button" data-toggle="dropdown" style="background:none; border:none; box-shadow:none!important; outline:none!important; padding:0px;">
										<img src="<?php echo $user->data->avatar; ?>" alt="" style="height:40px;">
									</button>
									<div class="dropdown-menu dropdown-menu-right p-0">
										<div class="dropdown-item bg-primary"><?php echo $user->data->display_name; ?></div>

										<?php foreach($options as $opt): ?>
										<a class="dropdown-item" href="<?php echo $opt->url; ?>"><?php echo $opt->title; ?></a>
										<?php endforeach; ?>

										<a class="dropdown-item" href="<?php echo wp_logout_url(); ?>" onclick="return confirm('Tem certeza que deseja sair?');">Logout</a>
									</div>
								</div>
							</div>
							<?php endif; endif; ?>
						</div>

					</div>

				</div>
			</nav>
			<?php
		}

		protected function content_template() {}

		public function menus() {
			$return = [];
			foreach(wp_get_nav_menus() as $menu) {
				$return[ $menu->slug ] = $menu->name;
			}
			return $return;
		}
	}

	$manager->register_widget_type(new \Basementor_Header_Simple());
});
