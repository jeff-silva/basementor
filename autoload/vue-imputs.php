<?php

add_action('vue', function() { ?>
<!-- codemirror -->
<style>.CodeMirror {min-height:500px;}</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.50.2/codemirror.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.50.2/codemirror.min.css">
<script src="https://cdn.jsdelivr.net/npm/vue-codemirror@4.0.6/dist/vue-codemirror.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.50.2/theme/ambiance.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.50.2/mode/htmlmixed/htmlmixed.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.50.2/mode/xml/xml.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.50.2/mode/javascript/javascript.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.50.2/mode/css/css.js"></script>
<script>
Vue.use(window.VueCodemirror);
Vue.component("input-code", {
    props: {
        value: {default:''},
        settings: {default:() => ({})},
    },

    methods: {
        valueEmit(value) {
            this.value = value;
            this.$emit('input', this.value);
            this.$emit('value', this.value);
            this.$emit('change', this.value);
        },
    },

    computed: {
        computedSettings() {
            return Object.assign({}, {
                tabSize: 4,
                mode: 'application/x-httpd-php',
                theme: 'ambiance',
                lineNumbers: true,
                line: true,
                indentUnit: 4,
                indentWithTabs: true,
            }, this.options);
        },
    },

    template: `<div>
        <codemirror v-model="value" :options="computedSettings" @input="valueEmit($event);"></codemirror>
    </div>`,
});
</script>


<!-- input-color -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue-color/2.7.1/vue-color.min.js"></script>
<script>
Vue.component("input-color", {
    components: {color:VueColor.Chrome},

    props: {
        value: {default:''},
        pickerShow: {default:''},
    },

    methods: {
    	emit(value) {
    		this.value = value;
    		this.$emit('input', this.value);
    	},
    },

    mounted() {
        document.addEventListener('click', (ev) => {
            this.pickerShow = this.$el.contains(ev.target) && ev.target.classList.contains('form-control');
        });
    },

    template: `<div class="input-color">
        <div class="input-group form-control" style="padding:0px !important;">
        	<slot name="prepend" :value="value">
				<div class="input-group-prepend">
					<div class="input-group-text" style="border:none;" :style="'background:'+value+'!important'"> &nbsp; </div>
				</div>
        	</slot>
            <input type="text" class="form-control" v-model="value" @focus="pickerShow=true;" style="border:none;" />
            <slot name="append" :value="value"></slot>
        </div>
        <div v-if="pickerShow" style="position:absolute; z-index:3;">
            <color :value="value" @input="emit($event.hex8);"></color>
        </div>
    </div>`,
});
</script>
<?php });