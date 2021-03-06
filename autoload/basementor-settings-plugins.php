<?php

if (! function_exists('basementor_plugins')) {
    function basementor_plugins() {
        return [
            'googleanalytics' => [
                'name'      => 'Google Analytics',
                'slug'      => 'googleanalytics',
                'required'  => false,
            ],
    
            'woocommerce' => [
                'name'      => 'Woocommerce',
                'slug'      => 'woocommerce',
                'required'  => false,
            ],
        ];
    }
}


add_filter('basementor-settings-menu', function($items) {

    $items['basementor']['children'][] = [
		'id'    => 'basementor-settings-plugins',
		'title' => 'Plugins',
		'href'  => admin_url('admin.php?page=basementor-settings&tab=plugins'),
    ];
    
    return $items;
});


add_action('basementor-settings-plugins', function($data) {
    $data->plugins = basementor_plugins();

    ?><div id="<?php echo $data->id; ?>">
        <pre>$data: {{ $data }}</pre>
    </div>
    
    <script>new Vue({
        el: "#<?php echo $data->id; ?>",
        data: <?php echo json_encode($data); ?>,
    });</script><?php
});