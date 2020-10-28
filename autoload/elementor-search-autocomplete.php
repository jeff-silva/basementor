<?php

/* Inspirations:
https://piano.io/
https://evernote.com/intl/pt-br/
https://www.columbiasportswear.com.br/
*/

\Basementor\Basementor::action('elementor-search-autocomplete', function($input) {
    return array_map(function($post) {
        $post = new \Basementor\Post($post);
        $post->thumbnail = $post->thumbnail;
        $post->permalink = $post->permalink;
        $post->excerpt = $post->excerpt;
        return $post;
    }, get_posts((array) $input));
});

\Basementor\Elementor::register([
    'class' => 'Elementor_Search_Autocomplete',
    'title' => 'Search autocomplete',
    'icon' => 'eicon-editor-code',
    'categories' => ['general'],
    'controls' => [
        'section_post' => [
            'label' => 'Configurações de busca autocompletar',
            'fields' => [
                'post_type' => [
                    'label' => 'Tipo de menu',
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'default' => 'post',
                    'options' => \Basementor\Elementor::get_post_types(),
                ],

                'placeholder' => [
                    'label' => 'Placeholder',
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => 'Buscar',
                ],
            ],
        ],
    ],
    'render' => function($set=[]) {
        $style = [];

        echo '<style>'. implode(' ', $style) .'</style>';

        ?><div id="<?php echo $set->id; ?>_autocomplete"><form action="" method="get" style="position:relative;">
            <div class="input-group form-control p-0 bg-white">
                <input type="text" name="s" v-model="params.s" class="form-control bg-transparent border-0" placeholder="<?php echo $set->placeholder; ?>" @keyup="search()">
                <div class="input-group-append"><div class="input-group-btn bg-transparent border-0">
                    <button type="submit" class="btn bg-transparent">
                        <i class="fas fa-sync fa-spin" v-if="loading"></i>
                        <i class="fas fa-search" v-else></i>
                    </button>
                </div></div>
            </div>
            
            <div style="" v-if="posts.length>0">
                <a href="p.permalink" class="p-3" v-for="p in posts">
                    <div>{{ p.post_title }}</div>
                </a>
            </div>
        </form></div>
        
        <script>new Vue({
            el: "#<?php echo $set->id; ?>_autocomplete",
            data: {
                loading: false,
                params: {
                    post_type: "<?php echo $set->post_type; ?>",
                    s: "",
                    posts_per_page: 10,
                },
                posts: [],
            },

            methods: {
                search() {
                    this.loading = true;
                    if (this.$timeout) clearTimeout(this.$timeout);
                    this.$timeout = setTimeout(() => {
                        jQuery.post('<?php echo \Basementor\Basementor::action('elementor-search-autocomplete'); ?>', this.params, (resp) => {
                            this.loading = false;
                            this.posts = resp;
                        }, "json");
                    }, 1000);
                },
            },
        });</script><?php
    },
]);


