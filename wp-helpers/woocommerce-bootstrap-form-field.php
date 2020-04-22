<?php

if (! function_exists('woocommerce_bootstrap_form_field')) {
	function woocommerce_bootstrap_form_field($key, $field, $value=null) {
		$field = array_merge([
			'key' => $key,
			'type' => 'text',
			'label' => '',
			'description' => '',
			'placeholder' => '',
			'maxlength' => false,
			'required' => false,
			'autocomplete' => false,
			'id' => $key,
			'class' => [],
			'label_class' => [],
			'input_class' => [],
			'return' => false,
			'options' => [],
			'custom_attributes' => [],
			'validate' => [],
			'default' => '',
			'autofocus' => '',
			'priority' => '',
		], $field);

        $field = apply_filters( 'woocommerce_form_field_args', $field, $key, $value );
		$field = (object) $field;

		if ($field->required) { $field->label .= ' *'; }

		foreach($field->class as $i=>$class_value) {
			if ($class_value=='form-row-first') { $class_value = 'col-12 col-md-6'; }
			elseif ($class_value=='form-row-last') { $class_value = 'col-12 col-md-6'; }
			elseif ($class_value=='form-row-wide') { $class_value = 'col-12 col-md-12'; }
			$field->class[ $i ] = $class_value;
		}

		if ($field->type=='state'): ?>
		<div class="form-group <?php echo implode(' ', $field->class); ?>">
			<label><?php echo $field->label; ?></label>
			<select name="<?php echo $key; ?>" class="form-control"><option value="">Selecione um estado</option>
				<option value="AC" <?php echo $value=='AC'? 'selected': null; ?> >Acre</option>
				<option value="AL" <?php echo $value=='AL'? 'selected': null; ?> >Alagoas</option>
				<option value="AP" <?php echo $value=='AP'? 'selected': null; ?> >Amapá</option>
				<option value="AM" <?php echo $value=='AM'? 'selected': null; ?> >Amazonas</option>
				<option value="BA" <?php echo $value=='BA'? 'selected': null; ?> >Bahia</option>
				<option value="CE" <?php echo $value=='CE'? 'selected': null; ?> >Ceará</option>
				<option value="DF" <?php echo $value=='DF'? 'selected': null; ?> >Distrito Federal</option>
				<option value="ES" <?php echo $value=='ES'? 'selected': null; ?> >Espírito Santo</option>
				<option value="GO" <?php echo $value=='GO'? 'selected': null; ?> >Goiás</option>
				<option value="MA" <?php echo $value=='MA'? 'selected': null; ?> >Maranhão</option>
				<option value="MT" <?php echo $value=='MT'? 'selected': null; ?> >Mato Grosso</option>
				<option value="MS" <?php echo $value=='MS'? 'selected': null; ?> >Mato Grosso do Sul</option>
				<option value="MG" <?php echo $value=='MG'? 'selected': null; ?> >Minas Gerais</option>
				<option value="PA" <?php echo $value=='PA'? 'selected': null; ?> >Pará</option>
				<option value="PB" <?php echo $value=='PB'? 'selected': null; ?> >Paraíba</option>
				<option value="PR" <?php echo $value=='PR'? 'selected': null; ?> >Paraná</option>
				<option value="PE" <?php echo $value=='PE'? 'selected': null; ?> >Pernambuco</option>
				<option value="PI" <?php echo $value=='PI'? 'selected': null; ?> >Piauí</option>
				<option value="RJ" <?php echo $value=='RJ'? 'selected': null; ?> >Rio de Janeiro</option>
				<option value="RN" <?php echo $value=='RN'? 'selected': null; ?> >Rio Grande do Norte</option>
				<option value="RS" <?php echo $value=='RS'? 'selected': null; ?> >Rio Grande do Sul</option>
				<option value="RO" <?php echo $value=='RO'? 'selected': null; ?> >Rondônia</option>
				<option value="RR" <?php echo $value=='RR'? 'selected': null; ?> >Roraima</option>
				<option value="SC" <?php echo $value=='SC'? 'selected': null; ?> >Santa Catarina</option>
				<option value="SP" <?php echo $value=='SP'? 'selected': null; ?> >São Paulo</option>
				<option value="SE" <?php echo $value=='SE'? 'selected': null; ?> >Sergipe</option>
				<option value="TO" <?php echo $value=='TO'? 'selected': null; ?> >Tocantins</option>
			</select>
			<!-- <input type="text" class="form-control" name="<?php echo $key; ?>" value="<?php echo $value; ?>"> -->
		</div>

		<?php elseif ($field->type=='country'): ?>
		<input type="hidden" name="<?php echo $key; ?>" value="BR">

		<?php elseif ($field->type=='textarea'): ?>
		<div class="form-group <?php echo implode(' ', $field->class); ?>">
			<label><?php echo $field->label; ?></label>
			<textarea name="<?php echo $key; ?>"
				class="form-control"
				placeholder="<?php echo $field->placeholder; ?>"
			><?php echo $value; ?></textarea>
		</div>

		<?php elseif ($field->type=='email'): ?>
		<div class="form-group <?php echo implode(' ', $field->class); ?>">
			<label><?php echo $field->label; ?></label>
			<div class="input-group">
				<div class="input-group-prepend"><div class="input-group-text"><i class="fa fa-fw fa-envelope"></i></div></div>
				<input type="<?php echo $field->type; ?>"
					class="form-control"
					placeholder="<?php echo $field->placeholder; ?>"
					name="<?php echo $key; ?>"
					value="<?php echo $value; ?>">
			</div>
		</div>

		<?php elseif ($field->type=='tel'): ?>
		<div class="form-group <?php echo implode(' ', $field->class); ?>">
			<label><?php echo $field->label; ?></label>
			<div class="input-group">
				<div class="input-group-prepend"><div class="input-group-text"><i class="fa fa-fw fa-phone"></i></div></div>
				<input type="<?php echo $field->type; ?>"
					class="form-control"
					placeholder="<?php echo $field->placeholder; ?>"
					name="<?php echo $key; ?>"
					value="<?php echo $value; ?>">
			</div>
		</div>

		<?php else: ?>
		<div class="form-group <?php echo implode(' ', $field->class); ?>">
			<label><?php echo $field->label; ?></label>
			<input type="<?php echo $field->type; ?>"
				class="form-control"
				placeholder="<?php echo $field->placeholder; ?>"
				name="<?php echo $key; ?>" value="<?php echo $value; ?>">
		</div>
		<?php endif;

		echo '<!--', dd($field), '-->';
	}
}