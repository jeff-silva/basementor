<?php

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


add_filter('basementor-settings-menu', function($admin_bar) {
	$admin_bar->add_menu([
		'parent' => 'basementor',
		'id'    => 'basementor-settings-plugins',
		'title' => 'Plugins',
		'href'  => admin_url('admin.php?page=basementor-settings&tab=plugins'),
    ]);
    
    return $admin_bar;
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