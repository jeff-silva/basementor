<?php

add_filter('basementor-settings-menu', function($admin_bar) {
	$admin_bar->add_menu([
		'parent' => 'basementor',
		'id'    => 'basementor-settings-bootstrap',
		'title' => 'Bootstrap',
		'href'  => admin_url('admin.php?page=basementor-settings&tab=bootstrap'),
    ]);
    
    return $admin_bar;
});


add_action('basementor-settings-bootstrap', function($data) {
    $data->prefix = 'primary';
    $data->prefixes = [
        ['id'=>'primary', 'label'=>'Primary'],
        ['id'=>'secondary', 'label'=>'Secondary'],
        ['id'=>'success', 'label'=>'Success'],
        ['id'=>'danger', 'label'=>'Danger'],
        ['id'=>'warning', 'label'=>'Warning'],
        ['id'=>'info', 'label'=>'Info'],
        ['id'=>'facebook', 'label'=>'Facebook'],
        ['id'=>'twitter', 'label'=>'Twitter'],
        ['id'=>'linkedin', 'label'=>'Linkedin'],
        ['id'=>'skype', 'label'=>'Skype'],
        ['id'=>'dropbox', 'label'=>'Dropbox'],
        ['id'=>'wordpress', 'label'=>'Wordpress'],
        ['id'=>'vimeo', 'label'=>'Vimeo'],
        ['id'=>'vk', 'label'=>'Vk'],
        ['id'=>'tumblr', 'label'=>'Tumblr'],
        ['id'=>'yahoo', 'label'=>'Yahoo'],
        ['id'=>'pinterest', 'label'=>'Pinterest'],
        ['id'=>'youtube', 'label'=>'Youtube'],
        ['id'=>'reddit', 'label'=>'Reddit'],
        ['id'=>'quora', 'label'=>'Quora'],
        ['id'=>'soundcloud', 'label'=>'Soundcloud'],
        ['id'=>'whatsapp', 'label'=>'Whatsapp'],
        ['id'=>'instagram', 'label'=>'Instagram'],
    ];

    $data->bootswatch = [
        ['id'=>'', 'label'=>'Sem estilo'],
        ['id'=>'cerulean', 'label'=>'Cerulean'],
        ['id'=>'cosmo', 'label'=>'Cosmo'],
        ['id'=>'cyborg', 'label'=>'Cyborg'],
        ['id'=>'darkly', 'label'=>'Darkly'],
        ['id'=>'flatly', 'label'=>'Flatly'],
        ['id'=>'journal', 'label'=>'Journal'],
        ['id'=>'litera', 'label'=>'Litera'],
        ['id'=>'lumen', 'label'=>'Lumen'],
        ['id'=>'lux', 'label'=>'Lux'],
        ['id'=>'materia', 'label'=>'Materia'],
        ['id'=>'minty', 'label'=>'Minty'],
        ['id'=>'pulse', 'label'=>'Pulse'],
        ['id'=>'sandstone', 'label'=>'Sandstone'],
        ['id'=>'simplex', 'label'=>'Simplex'],
        ['id'=>'sketchy', 'label'=>'Sketchy'],
        ['id'=>'slate', 'label'=>'Slate'],
        ['id'=>'solar', 'label'=>'Solar'],
        ['id'=>'spacelab', 'label'=>'Spacelab'],
        ['id'=>'superhero', 'label'=>'Superhero'],
        ['id'=>'united', 'label'=>'United'],
        ['id'=>'yeti', 'label'=>'Yeti'],
    ];

    $data->loader_icon = [
        ['id'=>'', 'label'=>'Desativado'],
        ['id'=>'lds-default', 'label'=>'Default'],
        ['id'=>'lds-circle', 'label'=>'Circle'],
        ['id'=>'lds-dual-ring', 'label'=>'Dual Ring'],
        ['id'=>'lds-facebook', 'label'=>'Facebook'],
        ['id'=>'lds-heart', 'label'=>'Heart'],
        ['id'=>'lds-ring', 'label'=>'Ring'],
        ['id'=>'lds-roller', 'label'=>'Roller'],
        ['id'=>'lds-ellipsis', 'label'=>'Ellipsis'],
        ['id'=>'lds-grid', 'label'=>'Grid'],
        ['id'=>'lds-hourglass', 'label'=>'Hourglass'],
        ['id'=>'lds-ripple', 'label'=>'Ripple'],
        ['id'=>'lds-spinner', 'label'=>'Spinner'],
    ];

    ?>
    <div id="<?php echo $data->id; ?>">
        <form action="<?php echo \Basementor\Basementor::action('basementor-settings-save'); ?>" method="post">
            <div class="text-right mt-3">
                <textarea name="settings" style="display:none;">{{ settings }}</textarea>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-fw fa-save"></i> Salvar
                </button>
            </div><br>

            <div class="row">
                <div class="col-4 form-group">
                    <label>Estilo Bootswatch</label>
                    <input-select v-model="settings.basementor_bootstrap_bootswatch"
                        :options="bootswatch"
                        option-id="id"
                        option-label="label"
                        placeholder="Estilo Bootswatch"
                    >
                        <template #option="{option, value}">{{ option.label }}</template>
                    </input-select>
                </div>

                <div class="col-12"><br></div>

                <div class="col-4 form-group">
                    <label>Prefix</label>
                    <input-select v-model="prefix"
                        :options="prefixes"
                        option-id="id"
                        option-label="label"
                        placeholder="Prefixo"
                    >
                        <template #option="{option, value}">{{ option.label }}</template>
                    </input-select>
                </div>

                <?php foreach($data->prefixes as $pref): ?>
                <template v-if="prefix=='<?php echo $pref['id']; ?>'">
                    <div class="col-2 form-group">
                        <label><?php echo $pref['label']; ?> bg</label>
                        <input-color v-model="settings.basementor_bootstrap_<?php echo $pref['id']; ?>_bg">
                            <template #append><div class="input-group-append">
                                <div class="input-group-btn">
                                    <button type="button" class="btn border-0 bg-transparent" @click="basementorSettingsDefault('basementor_bootstrap_<?php echo $pref['id']; ?>_bg');">
                                        <i class="fa fa-fw fa-refresh"></i>
                                    </button>
                                </div>
                            </div></template>
                        </input-color>
                    </div>

                    <div class="col-2 form-group">
                        <label><?php echo $pref['label']; ?> text</label>
                        <input-color v-model="settings.basementor_bootstrap_<?php echo $pref['id']; ?>_text">
                            <template #append><div class="input-group-append">
                                <div class="input-group-btn">
                                    <button type="button" class="btn border-0 bg-transparent" @click="basementorSettingsDefault('basementor_bootstrap_<?php echo $pref['id']; ?>_text');">
                                        <i class="fa fa-fw fa-refresh"></i>
                                    </button>
                                </div>
                            </div></template>
                        </input-color>
                    </div>
                </template>
                <?php endforeach; ?>

                <div class="col-2 form-group">
                    <label>Dark percent</label>
                    <div class="input-group form-control" style="padding:0px !important;">
                        <input type="number" class="form-control border-0 bg-transparent" v-model="settings.basementor_bootstrap_dark_percent">
                        <div class="input-group-append"><div class="input-group-btn">
                            <button type="button" class="btn" @click="basementorSettingsDefault('basementor_bootstrap_dark_percent');">
                                <i class="fa fa-fw fa-refresh"></i>
                            </button>
                        </div></div>
                    </div>
                </div>

                <div class="col-2 form-group">
                    <label>Light percent</label>
                    <div class="input-group form-control" style="padding:0px !important;">
                        <input type="number" class="form-control border-0 bg-transparent" v-model="settings.basementor_bootstrap_light_percent">
                        <div class="input-group-append"><div class="input-group-btn">
                            <button type="button" class="btn" @click="basementorSettingsDefault('basementor_bootstrap_light_percent');">
                                <i class="fa fa-fw fa-refresh"></i>
                            </button>
                        </div></div>
                    </div>
                </div>
            </div><br>

            <div :class="`border border-${prefix} mr-2`">
                <nav class="navbar navbar-expand-lg navbar-dark mb-2" :class="`bg-${prefix}`">
                    <a href="#" class="navbar-brand">
                        <i :class="`fa fa-fw fa-${prefix}`"></i>
                    </a>
                    <button type="button" data-toggle="collapse" data-target="#navbar-color-primary" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"><span class="navbar-toggler-icon"></span></button>
                    <div id="navbar-color-primary" class="collapse navbar-collapse"><ul class="navbar-nav mr-auto"><li class="nav-item active"><a href="#" class="nav-link">Home <span class="sr-only">(current)</span></a></li> <li class="nav-item"><a href="#" class="nav-link">Features</a></li> <li class="nav-item"><a href="#" class="nav-link">Pricing</a></li> <li class="nav-item"><a href="#" class="nav-link">About</a></li></ul> 
                        <div class="input-group form-control" style="max-width:300px; padding:0px!important; border:none!important;">
                            <input type="text" placeholder="Search" class="form-control border-0 mr-sm-2">
                            <div class="input-group-btn" style="padding:0px!important; border:none!important;">
                                <button type="button" :class="`btn btn-${prefix}-light`" style="border:none!important;">
                                    <i class="fa fa-fw fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </nav>

                <div class="p-2">
                    <div class="alert alert-dismissible mb-2" :class="`alert-${prefix}`"><button type="button" data-dismiss="alert" class="close">×</button> <h4 class="alert-heading">Warning!</h4> <p class="mb-0">Lorem ipsum dolor sit amet, odit magni cum qui doloribus  <a href="#" class="alert-link">vel scelerisque nisl consectetur et</a>.</p></div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <h2>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</h2>
                            <p>Excepturi doloribus unde molestias laborum delectus adipisci, eos repellat in debitis cum impedit numquam, architecto, facilis. Blanditiis cum quas nam ut, eum?</p>
                            <p>Rem accusamus, ipsa asperiores earum natus nesciunt vitae maxime eum, fugit labore odio, animi sequi at excepturi itaque rerum sit eius. Minus?</p>

                            <div class="row mb-6">
                                <div class="col-6 form-group">
                                    <input type="text" class="form-control" placeholder="Placeholder">
                                </div>

                                <div class="col-6 form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Placeholder">
                                        <div class="input-group-append"><div class="input-group-text">
                                            Append
                                        </div></div>
                                    </div>
                                </div>

                                <div class="col-6 form-group">
                                    <select class="form-control">
                                        <option value="">Select</option>
                                        <option value="">Option A</option>
                                        <option value="">Option B</option>
                                        <option value="">Option C</option>
                                    </select>
                                </div>

                                <div class="col-6 form-group">
                                    <div class="input-group form-control" style="padding:0px!important;">
                                        <input type="text" class="form-control border-0 bg-transparent" placeholder="Placeholder">
                                        <div class="input-group-append"><div class="input-group-text border-0 bg-transparent">
                                            Append
                                        </div></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col"><button type="button" class="btn btn-block" :class="`btn-outline-${prefix}`">Cancel</button></div>
                                <div class="col"><button type="button" class="btn btn-block" :class="`btn-${prefix}`">Save</button></div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="list-inline text-right mb-2">
                                <div class="list-inline-item">
                                    <a href="javascript:;" :class="`btn btn-${prefix}`">
                                        <i :class="`fa fa-fw fa-plus text-white`"></i> Add
                                    </a>
                                </div>

                                <div class="list-inline-item">
                                    <a href="javascript:;" :class="`btn btn-outline-${prefix} text-${prefix}`">
                                        <i :class="`fa fa-fw fa-plus text-${prefix}`"></i> Add
                                    </a>
                                </div>
                            </div>
                            
                            <table class="table table-bordered table-striped">
                                <colgroup>
                                    <col width="50px">
                                    <col width="*">
                                    <col width="*">
                                </colgroup>

                                <thead>
                                    <tr>
                                        <th>ID</th><th>Bbb</th><th>Ccc</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php for($x=1; $x<=5; $x++): ?>
                                    <tr :class="`table-${prefix}`">
                                        <td><?php echo $x; ?></td>
                                        <td>
                                            <?php if ($x%2==0): ?>
                                            <span class="badge" :class="`badge-${prefix}`">{{ prefix }} <?php echo $x; ?></span>
                                            <?php endif; ?>

                                            <?php if ($x%1==0): ?>
                                            <span class="badge badge-pill" :class="`badge-${prefix}`">{{ prefix }} <?php echo $x; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>Ccc</td>
                                    </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>

                            <div class="progress mb-3"><div role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-striped progress-bar-animated" :class="`bg-${prefix}`" style="width: 75%;"></div></div>

                            <div class="d-flex">
                                <div class="mx-auto">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination">
                                            <li class="page-item"><a href="javascript:;" class="page-link">1</a></li>
                                            <li class="page-item"><a href="javascript:;" class="page-link bg-primary text-light">2</a></li>
                                            <li class="page-item"><a href="javascript:;" class="page-link">3</a></li>
                                            <li class="page-item"><a href="javascript:;" class="page-link">4</a></li>
                                            <li class="page-item"><a href="javascript:;" class="page-link">5</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col">
                            <div class="card text-white" :class="`bg-${prefix}`"><div class="card-header">Header</div> <div class="card-body"><h4 class="card-title">Primary card title</h4> <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p></div></div>
                        </div>
                        <div class="col">
                            <div class="card" :class="`border-${prefix}`"><div class="card-header">Header</div> <div class="card-body"><h4 class="card-title">Primary card title</h4> <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p></div></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>CSS customizado</label>
                <input-code v-model="settings.basementor_css" @change="updateCss($event);"></input-code>
            </div>

            <div class="row mt-4">
                <div class="col-4 form-group">
                    <label>Loader</label>
                    <input-select v-model="settings.basementor_loader_icon"
                        :options="loader_icon"
                        option-id="id"
                        option-label="label"
                        placeholder="Ícone do loader"
                    >
                        <template #option="{option, value}">{{ option.label }}</template>
                    </input-select>
                </div>

                <div class="col-4 form-group">
                    <label>Cor do ícone</label>
                    <input-color v-model="settings.basementor_loader_icon_color">
                        <template #append><div class="input-group-append">
                            <div class="input-group-btn">
                                <button type="button" class="btn border-0 bg-transparent" @click="basementorSettingsDefault('basementor_bootstrap_<?php echo $pref['id']; ?>_text');">
                                    <i class="fa fa-fw fa-refresh"></i>
                                </button>
                            </div>
                        </div></template>
                    </input-color>
                </div>

                <div class="col-4 form-group">
                    <label>Cor de fundo</label>
                    <input-color v-model="settings.basementor_loader_bg_color">
                        <template #append><div class="input-group-append">
                            <div class="input-group-btn">
                                <button type="button" class="btn border-0 bg-transparent" @click="basementorSettingsDefault('basementor_bootstrap_<?php echo $pref['id']; ?>_text');">
                                    <i class="fa fa-fw fa-refresh"></i>
                                </button>
                            </div>
                        </div></template>
                    </input-color>
                </div>
            </div>

            <div class="text-right mt-3">
                <textarea name="settings" style="display:none;">{{ settings }}</textarea>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-fw fa-save"></i> Salvar
                </button>
            </div>
        </form>
    </div>

    <?php do_action('vue'); ?>
    <script>new Vue({
        el: "#<?php echo $data->id; ?>",
        data: <?php echo json_encode($data); ?>,
        methods: {
            basementorSettingsDefault(kname) {
                this.$set(this.settings, kname, this.settingsDefault[kname]||false);
                this.$forceUpdate();
            },

            updateCss(css) {
                if (this.updateCssTimeout) clearTimeout(this.updateCssTimeout);
                this.updateCssTimeout = setTimeout(() => {
                    document.getElementById('basementor_css').innerHTML = css;
                }, 1000);
            },
        },
    });</script>
    <?php
});