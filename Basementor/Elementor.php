<?php

namespace Basementor;


class Elementor
{
    static $elements = [];
    
    static function register($metaparams=[]) {
        $metaparams = array_merge([
            'class' => '',
            'title' => '',
            'icon' => 'eicon-editor-code',
            'categories' => ['general'],
            'controls' => [],
            'render' => function($data=[]) {},
        ], $metaparams);

        if (!$metaparams['class']) return;

        self::$elements[ $metaparams['class'] ] = $metaparams;

        add_action('elementor/widgets/widgets_registered', function($manager) use($metaparams) {

            
            $metaparams['controls']['section_style'] = [
                'label' => 'Customizações',
                'fields' => [
                    'style' => [
                        'type' => \Elementor\Controls_Manager::CODE,
                        'label' => 'CSS customizado',
                        'language' => 'css',
                        'default' => ':id {background:transparent;}',
                    ],
                    'style_note' => [
                        'type' => \Elementor\Controls_Manager::RAW_HTML,
                        'label' => 'Observações',
                        'raw' => "A constante <code>:id</code> representa a raiz do elemento atual. Esse valor será trocado
                        pelo ID dinâmico do elemento no momento da renderização do componente, trocando o <code>:id</code>
                        por algo como #".uniqid($metaparams['class'].'_').". <br>
                        O ID do elemento precisa ser dinâmico para que este elemento possa ser inserido multiplas vezes no tema
                        sem que o ID dele se repita.",
                    ],
                    'act_refresh' => [
                        'label' => 'Atualizar',
                        'type' => \Elementor\Controls_Manager::BUTTON,
                        'separator' => 'before',
                        'button_type' => 'success',
                        'text' => 'Atualizar',
                        'event' => 'namespace:editor:refresh',
                    ],
                ],
            ];
            
            $metaparams_controls = var_export($metaparams['controls'], true);
            
            $code = <<<EOF
            class {$metaparams['class']} extends \Elementor\Widget_Base {

                public function __construct(\$data=[], \$args=null) {
                    parent::__construct(\$data, \$args);
                    \$this->globalSettings = (new \Elementor\Core\Kits\Manager)->get_current_settings();
                }
                
                public function get_name() {
                    return __CLASS__;
                }

                public function get_title() {
                    return '{$metaparams['title']}';
                }

                // https://ecomfe.github.io/eicons/demo/demo.html
                public function get_icon() {
                    return '{$metaparams['icon']}';
                }

                public function get_categories() {
                    //return \$this->metaparams['categories'];
                    return ['categories'];
                }

                public function get_script_depends() {
                    return [];
                }

                public function get_style_depends() {
                    return [];
                }

                public function get_global_color(\$id) {
                    \$id = str_replace('globals/colors?id=', '', \$id);
                    if (!\$id) return;

                    foreach(\$this->globalSettings['system_colors'] as \$color) {
                        if (\$id==\$color['_id']) {
                            return \$color['color'];
                        }
                    }

                    foreach(\$this->globalSettings['custom_colors'] as \$color) {
                        if (\$id==\$color['_id']) {
                            return \$color['color'];
                        }
                    }

                    return null;
                }

                public function parsed_settings_globals(\$sets) {
                    if (isset(\$sets['__globals__'])) {
                        \$sets['__globals__'] = (array) \$sets['__globals__'];
                        foreach(\$sets['__globals__'] as \$key=>\$value) {
                            if (!\$value) continue;
                            \$sets[\$key] = \$this->get_global_color(\$value);
                        }
                    }

                    foreach(\$sets as \$key=>\$value) {
                        \$sets[\$key] = \$this->parsed_settings_globals(\$value);
                    }
                    
                    return \$sets;
                }

                public function parsed_settings() {
                    \$sets = \$this->get_settings_for_display();
                    \$sets = \$this->parsed_settings_globals(\$sets);
                    // dd(\$sets);
                    return json_decode(json_encode(\$sets));
                }

                public function link() {
                    return 'Helper to generate link with follow and target attributes';
                }

                public function _register_controls() {
                    foreach($metaparams_controls as \$section_id=>\$section) {
                        \$this->start_controls_section(\$section_id, \$section);
                        foreach(\$section['fields'] as \$field_id=>\$field) {
                            if (\$field['type']==\Elementor\Controls_Manager::REPEATER) {
                                \$repeater = new \Elementor\Repeater();
                                foreach(\$field['fields'] as \$ffield_id=>\$ffield) {
                                    \$repeater->add_control(\$ffield_id, \$ffield);
                                }
                                \$field['fields'] = \$repeater->get_controls();
                                \$field['prevent_empty'] = false;
                                \$this->add_control(\$field_id, \$field);
                                continue;
                            }
                            \$this->add_control(\$field_id, \$field);
                        }
                        \$this->end_controls_section();
                    }
                }

                public function render() {
                    \$set = \$this->parsed_settings();
                    \$set->id = uniqid(__CLASS__.'_');
                    \$set->is_edit = \Elementor\Plugin::\$instance->editor->is_edit_mode();
                    \$set->is_preview = \Elementor\Plugin::\$instance->preview->is_preview_mode();
                    echo '<!-- Elementor: {$metaparams['class']} -->';
                    echo \\Basementor\\Elementor::template('{$metaparams['class']}', \$set);
                }
            }
EOF;

            eval($code);
            $class = "\\{$metaparams['class']}";
            $manager->register_widget_type(new $class);
        });
    }


    static function template($class, $set=[]) {
        if (isset(self::$elements[$class]) AND is_callable(self::$elements[$class]['render'])) {
            ob_start();
            $set->style = str_replace(':id', "#{$set->id}", $set->style);
            echo "<style>{$set->style}</style><div id=\"{$set->id}\">";
            call_user_func(self::$elements[$class]['render'], $set);
            echo '</div>';
            $content = ob_get_clean();

            return $content;
        }
    }


    static function get_navs() {
        $nav_options = [];
        foreach(wp_get_nav_menus() as $menu) {
            $nav_options[ $menu->slug ] = $menu->name;
        }
        return $nav_options;
    }

    static function get_post_types() {
        $return = ['any'=>'Qualquer um'];
        foreach(get_post_types(['public'=>true], 'objects') as $item) {
            $return[ $item->name ] = $item->label;
        }
        return $return;
    }
}
