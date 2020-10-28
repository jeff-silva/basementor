<?php

/* Inspirations:
https://piano.io/
https://evernote.com/intl/pt-br/
https://www.columbiasportswear.com.br/
*/

\Basementor\Elementor::register([
    'class' => 'Elementor_Auth_User_Dropdown',
    'title' => 'Auth User Dropdown',
    'icon' => 'eicon-editor-code',
    'categories' => ['general'],
    'controls' => [
        'section_post' => [
            'label' => 'Auth User Dropdown',
            'fields' => [
                'nav_items' => [
                    'label' => 'Itens do menu',
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'title_field' => '{{{ title }}}',
                    'default' => [],
                    'fields' => [
                        'title' => [
                            'label' => 'Título',
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => '',
                            'label_block' => true,
                        ],

                        'link' => [
                            'label' => 'Link',
                            'type' => \Elementor\Controls_Manager::URL,
                            'default' => ['url'=>''],
                            'label_block' => true,
                        ],

                        'icon' => [
                            'label' => 'Link',
                            'type' => \Elementor\Controls_Manager::ICONS,
                            'default' => [],
                            'label_block' => true,
                        ],
                    ],
                ],

                'test_auth' => [
                    'label' => 'Testar logado/deslogado',
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'default' => '',
                ],

                'test_dropdown' => [
                    'label' => 'Testar dropdown aberto/fechado',
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'default' => '',
                ],
            ],
        ],

        'section_unlogged' => [
            'label' => 'Usuário deslogado',
            'fields' => [
                'unlogged_html' => [
                    'label' => 'Conteúdo',
                    'type' => \Elementor\Controls_Manager::CODE,
                    'default' => '<a href="'. site_url('/wp-admin/') .'">Fazer login</a>',
                ],
            ],
        ],
    ],
    'render' => function($set, $me) {
        global $current_user;

        $data = new stdClass;
        $data->user = $current_user->data;
        $data->user->photo = null;

        if ($data->user->ID) {
            $data->user->photo = get_avatar_url($data->user->ID);
        }

        if ($set->is_edit AND $set->test_auth) {
            $data->user = (object) [
                'ID' => false,
                'user_login' => false,
                'user_pass' => false,
                'user_nicename' => false,
                'user_email' => false,
                'display_name' => false,
            ];
        }

        $data->display = 'none';
        if ($set->test_dropdown AND $set->is_edit) {
            $data->display = 'block';
        }

        $style = [];

        echo '<style>'. implode(' ', $style) .'</style>';
        
        if ($data->user->ID): ?>
        <div style="position:relative;">
            <div class="d-flex align-items-center p-1 elementor-auth-user-dropdown-dropdown-card" style="cursor:pointer;" onclick="jQuery('#<?php echo $set->id; ?>_dropdown').fadeToggle(200);">
                <div class="flex-grow-1">
                    <?php echo $data->user->display_name; ?>
                </div>
                <div><img src="<?php echo $data->user->photo; ?>" alt="" style="height:40px; border-radius:50%;"></div>
            </div>

            <div class="bg-white shadow-sm elementor-auth-user-dropdown-dropdown" id="<?php echo $set->id; ?>_dropdown" style="position:absolute; top:100%; left:0px; width:100%; display:<?php echo $data->display; ?>;">
                <div class="nav flex-column nav-pills">
                    <?php foreach($set->nav_items as $item):
                        echo $me->htmlLink($item->link, ['class'=>'nav-link'], "<i class='{$item->icon->value}'></i> &nbsp; {$item->title}");
                    endforeach; ?>
                </div>
            </div>
        </div>
        
        <?php else: ?>
        <div><?php echo $set->unlogged_html; ?></div>
        <?php endif;
    },
]);