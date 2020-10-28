<?php

namespace Basementor;

class Post
{
    
    public $singular = 'Post';
    public $plural = 'Posts';
    public $post_type = 'post';
    public $menu_position = 10;
    public $public = true;
    public $publicly_queryable = true;
    public $show_ui = true;
    public $show_in_menu = true;
    public $menu_icon = 'none'; // https://developer.wordpress.org/resource/dashicons/
    public $supports = ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'elementor'];
    public $thumbnail_default = 'https://archive.org/download/no-photo-available/no-photo-available.png';
    public $has_thumbnail = false;

    /* menu_position:
    5 – below Posts
    10 – below Media
    15 – below Links
    20 – below Pages
    25 – below comments
    60 – below first separator
    65 – below Plugins
    70 – below Users
    75 – below Tools
    80 – below Settings
    100 – below second separator
    */

    static function register()
    {
        $self = new static; 
        register_post_type($self->post_type, [
            'public'             => $self->public,
            'publicly_queryable' => $self->publicly_queryable,
            'show_ui'            => $self->show_ui,
            'show_in_menu'       => $self->show_in_menu,
            'query_var'          => true,
            'menu_icon'          => $self->menu_icon,
            'rewrite'            => array('slug' => $self->post_type),
            // 'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => $self->menu_position,
            'supports'           => $self->supports,
            'labels'             => [
                'name'                  => $self->plural,
                'singular_name'         => $self->singular,
                'menu_name'             => $self->plural,
                'name_admin_bar'        => $self->singular,
                'add_new'               => "Novo(a) {$self->singular}",
                'add_new_item'          => "Novo(a) {$self->singular}",
                'new_item'              => "Novo(a) {$self->singular}",
                'edit_item'             => "Editar {$self->singular}",
                'view_item'             => "Ver {$self->singular}",
                'all_items'             => "Todos os {$self->plural}",
                'search_items'          => "Pesquisar {$self->plural}",
                'parent_item_colon'     => "Pai {$self->plural}:",
                'not_found'             => "Nenhum {$self->plural} encontrado.",
                'not_found_in_trash'    => "Nenhum {$self->plural} encontrado na lixeira.",
                'featured_image'        => "Imagem de capa de {$self->singular}",
                'set_featured_image'    => "Alterar como imagem de capa",
                'remove_featured_image' => "Remover imagem de capa",
                'use_featured_image'    => "Usar como imagem de capa",
                'archives'              => "Arquivos de {$self->singular}",
                'insert_into_item'      => "Inserir dentro de {$self->singular}",
                'uploaded_to_this_item' => "Enviado para {$self->singular}",
                'filter_items_list'     => "Filtrar lista de {$self->plural}",
                'items_list_navigation' => "Lista de navegação de {$self->plural}",
                'items_list'            => "Lista de {$self->plural}",
            ],
        ]);
	}
	

	static function get_posts($params=[])
    {
        $me = new self;

        $params = array_merge([
            'post_type' => $me->post_type,
        ], $params);

        return array_map(function($post) {
            return new self($post);
        }, get_posts($params));
    }


    
    public function __construct($post=null)
    {
        // dd($post); die;
        if (is_integer($post) OR is_string($post)) {
            $post = get_post($post);
        }

        $post = array_merge([
            'ID' => '',
            'post_type' => $this->post_type,
            'post_title' => '',
            'post_content' => '',
            'post_author' => '',
            'post_excerpt' => '',
            'post_status' => '',
            'post_name' => '',
        ], (array) $post);

        foreach($post as $key=>$value) {
            $this->{$key} = $value;
        }
    }

    public function __get($name)
    {
		if ($this->{$name}) {
			return $this->{$name};
		}
		
		if ('thumbnail'==$name) {
            $thumbnail_url =  wp_get_attachment_url(get_post_thumbnail_id($this->ID));
            if ($thumbnail_url) { $this->has_thumbnail = true; }
			return $this->{$name} = $thumbnail_url? $thumbnail_url: $this->thumbnail_default;
        }

        if ('date'==$name) {
			return $this->{$name} = get_the_date('', $this->ID);
        }

        if ('permalink'==$name) {
			return $this->{$name} = get_the_permalink($this->ID);
        }

        if ('excerpt'==$name) {
			return $this->{$name} = get_the_excerpt($this->ID);
        }

        if ($value = get_post_meta($this->ID, $name, true)) {
			return $this->{$name} = $value;
		}

        if (function_exists('get_field')) {
            return $this->{$name} = get_field($name, $this->ID);
        }
		
		return null;
    }

    public function the_date($format='') { return get_the_date($format, $this->ID); }

    public function import($data=[]) {
        foreach($data as $key=>$value) {
            $this->{$key} = $value;
        }
    }

    public function save($data=[]) {
        $save = [
            'ID' => $this->ID,
            'post_type' => $this->post_type,
            'post_title' => $this->post_title,
            'post_content' => $this->post_content,
            'post_author' => $this->post_author,
            'post_status' => $this->post_status,
            'meta_input' => [],
        ];

        foreach($data as $key=>$value) {
            if (isset($save[$key])) {
                $save[$key] = $value;
            }
            else {
                $save['meta_input'][$key] = $value;
            }
        }

        if (!$save['ID']) unset($save['ID']);
        $id = wp_insert_post($save);
        return $id;
    }

    public function delete($forced=false) {}

    public function excerpt($max=20, $ellipsis='') {
        if (strlen($this->excerpt)>$max) {
            $excerpt = wordwrap($this->excerpt, $max);
            return substr($excerpt, 0, strpos($excerpt, "\n")) .$ellipsis;
        }
        return $this->excerpt;
    }

    public function shareLinks($params=[]) {
        $post_title = urlencode($this->post_title);
        $permalink = urlencode($this->permalink);
        $excerpt = urlencode($this->excerpt);

        $share['whatsapp'] = ['icon'=>'fab fa-whatsapp', 'title'=>'Whatsapp', 'url'=>"https://api.whatsapp.com/send?text={$post_title}%0A{$permalink}"];
        $share['instagram'] = ['icon'=>'fab fa-instagram', 'title'=>'Instagram', 'url'=>"https://www.instagram.com/?url={$permalink}"];
        $share['twitter'] = ['icon'=>'fab fa-twitter', 'title'=>'Twitter', 'url'=>"https://twitter.com/share?url={$parmalink}&text={$post_title}"];
        $share['facebook'] = ['icon'=>'fab fa-facebook-f', 'title'=>'Facebook', 'url'=>"https://www.facebook.com/sharer.php?u={$permalink}"];
        $share['linkedin'] = ['icon'=>'fab fa-linkedin', 'title'=>'LinkedIn', 'url'=>"http://www.linkedin.com/shareArticle?mini=true&url={$permalink}"];
        $share['email'] = ['icon'=>'fas fa-envelope', 'title'=>'E-mail', 'url'=>"mailto:friend@mail.com?body={$this->excerpt} - Leia mais em {$this->permalink}"];
        return $share;
    }
}
