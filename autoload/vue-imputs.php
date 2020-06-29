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
        emit(value) {
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
        <codemirror v-model="value" :options="computedSettings" @input="emit($event);"></codemirror>
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
        <div class="input-group form-control p-0" style="padding:0px !important;">
        	<slot name="prepend" :value="value">
				<div class="input-group-prepend" style="border:none;">
					<div class="input-group-text" style="border:none;" :style="'background:'+value+'!important'"> &nbsp; </div>
				</div>
        	</slot>
            <input type="text" class="form-control border-0 bg-transparent" v-model="value" @focus="pickerShow=true;" style="border:none;" />
            <slot name="append" :value="value"></slot>
        </div>
        <div v-if="pickerShow" style="position:absolute; z-index:3;">
            <color :value="value" @input="emit($event.hex8);"></color>
        </div>
    </div>`,
});
</script>

<!--
<input-select v-model="opt" :options='[]' option-id="id" option-label="name">
	<template #option="{option, value}">{{ option.name }}</template>
</input-select>
-->
<script>Vue.component('input-select', {
	props: {
		value: {default: false},
		optionId: {default: ''},
		optionLabel: {default: ''},
		options: {default: () => ([])},
		search: {default: ''},
		shown: {default: false},
		placeholder: {default: ''},
	},

	watch: {
		value(nvalue, ovalue) {
			for(var i in this.options) {
				var opt = this.options[i];
				var value = this.optionId? (opt[this.optionId]||false): opt;
				if (value==nvalue) {
					this.setValue(opt);
					break;
				}
			}
		},
	},

	methods: {
		setValue(option) {
			this.value = this.optionId?
				(option[this.optionId] || false):
				option;

			this.placeholder = this.optionLabel?
				(option[this.optionLabel] || false):
				option;

			this.$emit('input', this.value);
			this.shown = false;
			this.search = '';
		},

		selected(opt) {
			var value = this.optionId? (opt[this.optionId]||false): opt;
			return this.value==value;
		},
	},

	computed: {
		compOptions() {
			var search = this.search.toLowerCase();
			return this.options.filter((opt) => {
				var line = Object.values(opt).join('').toLowerCase().replace(/\s/g, '');
				return line.includes(search);
			});
		},

		compPlaceholder() {
			var placeholder = this.placeholder;

			if (this.optionId && this.optionLabel) {
				for(var i in this.options) {
					var option = this.options[i];

					if (option[this.optionId]==this.value && option[this.optionLabel]) {
						placeholder = option[this.optionLabel];
						break;
					}
				}
			}

			return placeholder;
		},
	},

	mounted() {
		document.addEventListener('click', (ev) => {
			this.shown = this.$el.contains(ev.target);
		});

		document.addEventListener('keyup', (ev) => {
			if (ev.keyCode != 9) return;
			setTimeout(() => {
				this.shown = this.$el.contains(document.activeElement);
			}, 10);
		});
	},

	template: `<div class="input-group" style="position:relative;">
		<input type="search"
			class="form-control"
			v-model="search"
			:placeholder="compPlaceholder"
		/>
		<div class="bg-white shadow" style="position:absolute; top:100%; left:0px; width:100%; max-height:300px; overflow:auto; z-index:3;" v-if="shown">
			<div v-for="o in compOptions" @click="setValue(o);" class="p-2" :class="{'bg-primary text-light':selected(o)}">
				<slot name="option" :option="o" :value="value">
					<div>{{ o }}</div>
				</slot>
			</div>
		</div>
	</div>`,
});</script>
<?php });