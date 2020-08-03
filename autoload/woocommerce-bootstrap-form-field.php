<?php

if (! BASEMENTOR_ELEMENTOR) { return; }
if (! BASEMENTOR_WOOCOMMERCE) { return; }

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

		// masks
		if ($key=='billing_cpf') { $field->mask = '999.999.999-99'; }
		else if ($key=='billing_cnpj') { $field->mask = '99.999.999/9999-99'; }
		else if ($key=='billing_postcode') { $field->mask = '99999-999'; }
		else if ($key=='billing_phone') { $field->mask = '(99) 99999-9999'; }
		else if ($key=='billing_cellphone') { $field->mask = '(99) 99999-9999'; }
		else if ($key=='shipping_postcode') { $field->mask = '99999-999'; }

		if ($key=='shipping_country' OR $key=='billing_country'): ?>
		<div class="form-group <?php echo implode(' ', $field->class); ?>">
			<label><?php echo $field->label; ?></label>
			<select name="<?php echo $key; ?>" class="form-control"><option>Selecione o país</option>
				<option value="AF" <?php echo $value=='AF'? 'selected': null; ?> >Afghanistan</option><option value="AX" <?php echo $value=='AX'? 'selected': null; ?> >Åland Islands</option><option value="AL" <?php echo $value=='AL'? 'selected': null; ?> >Albania</option><option value="DZ" <?php echo $value=='DZ'? 'selected': null; ?> >Algeria</option><option value="AS" <?php echo $value=='AS'? 'selected': null; ?> >American Samoa</option><option value="AD" <?php echo $value=='AD'? 'selected': null; ?> >Andorra</option><option value="AO" <?php echo $value=='AO'? 'selected': null; ?> >Angola</option><option value="AI" <?php echo $value=='AI'? 'selected': null; ?> >Anguilla</option><option value="AQ" <?php echo $value=='AQ'? 'selected': null; ?> >Antarctica</option>
				<option value="AG" <?php echo $value=='AG'? 'selected': null; ?> >Antigua and Barbuda</option><option value="AR" <?php echo $value=='AR'? 'selected': null; ?> >Argentina</option><option value="AM" <?php echo $value=='AM'? 'selected': null; ?> >Armenia</option><option value="AW" <?php echo $value=='AW'? 'selected': null; ?> >Aruba</option><option value="AU" <?php echo $value=='AU'? 'selected': null; ?> >Australia</option><option value="AT" <?php echo $value=='AT'? 'selected': null; ?> >Austria</option><option value="AZ" <?php echo $value=='AZ'? 'selected': null; ?> >Azerbaijan</option><option value="BS" <?php echo $value=='BS'? 'selected': null; ?> >Bahamas</option><option value="BH" <?php echo $value=='BH'? 'selected': null; ?> >Bahrain</option><option value="BD" <?php echo $value=='BD'? 'selected': null; ?> >Bangladesh</option><option value="BB" <?php echo $value=='BB'? 'selected': null; ?> >Barbados</option>
				<option value="BY" <?php echo $value=='BY'? 'selected': null; ?> >Belarus</option><option value="BE" <?php echo $value=='BE'? 'selected': null; ?> >Belgium</option><option value="BZ" <?php echo $value=='BZ'? 'selected': null; ?> >Belize</option><option value="BJ" <?php echo $value=='BJ'? 'selected': null; ?> >Benin</option><option value="BM" <?php echo $value=='BM'? 'selected': null; ?> >Bermuda</option><option value="BT" <?php echo $value=='BT'? 'selected': null; ?> >Bhutan</option><option value="BO" <?php echo $value=='BO'? 'selected': null; ?> >Bolivia, Plurinational State of</option><option value="BQ" <?php echo $value=='BQ'? 'selected': null; ?> >Bonaire, Sint Eustatius and Saba</option><option value="BA" <?php echo $value=='BA'? 'selected': null; ?> >Bosnia and Herzegovina</option><option value="BW" <?php echo $value=='BW'? 'selected': null; ?> >Botswana</option><option value="BV" <?php echo $value=='BV'? 'selected': null; ?> >Bouvet Island</option>
				<option value="BR" <?php echo $value=='BR'? 'selected': null; ?> >Brasil</option><option value="IO" <?php echo $value=='IO'? 'selected': null; ?> >British Indian Ocean Territory</option><option value="BN" <?php echo $value=='BN'? 'selected': null; ?> >Brunei Darussalam</option><option value="BG" <?php echo $value=='BG'? 'selected': null; ?> >Bulgaria</option><option value="BF" <?php echo $value=='BF'? 'selected': null; ?> >Burkina Faso</option><option value="BI" <?php echo $value=='BI'? 'selected': null; ?> >Burundi</option><option value="KH" <?php echo $value=='KH'? 'selected': null; ?> >Cambodia</option><option value="CM" <?php echo $value=='CM'? 'selected': null; ?> >Cameroon</option><option value="CA" <?php echo $value=='CA'? 'selected': null; ?> >Canada</option><option value="CV" <?php echo $value=='CV'? 'selected': null; ?> >Cape Verde</option><option value="KY" <?php echo $value=='KY'? 'selected': null; ?> >Cayman Islands</option>
				<option value="CF" <?php echo $value=='CF'? 'selected': null; ?> >Central African Republic</option><option value="TD" <?php echo $value=='TD'? 'selected': null; ?> >Chad</option><option value="CL" <?php echo $value=='CL'? 'selected': null; ?> >Chile</option><option value="CN" <?php echo $value=='CN'? 'selected': null; ?> >China</option><option value="CX" <?php echo $value=='CX'? 'selected': null; ?> >Christmas Island</option><option value="CC" <?php echo $value=='CC'? 'selected': null; ?> >Cocos (Keeling) Islands</option><option value="CO" <?php echo $value=='CO'? 'selected': null; ?> >Colombia</option><option value="KM" <?php echo $value=='KM'? 'selected': null; ?> >Comoros</option><option value="CG" <?php echo $value=='CG'? 'selected': null; ?> >Congo</option><option value="CD" <?php echo $value=='CD'? 'selected': null; ?> >Congo, the Democratic Republic of the</option><option value="CK" <?php echo $value=='CK'? 'selected': null; ?> >Cook Islands</option>
				<option value="CR" <?php echo $value=='CR'? 'selected': null; ?> >Costa Rica</option><option value="CI" <?php echo $value=='CI'? 'selected': null; ?> >Côte d'Ivoire</option><option value="HR" <?php echo $value=='HR'? 'selected': null; ?> >Croatia</option><option value="CU" <?php echo $value=='CU'? 'selected': null; ?> >Cuba</option><option value="CW" <?php echo $value=='CW'? 'selected': null; ?> >Curaçao</option><option value="CY" <?php echo $value=='CY'? 'selected': null; ?> >Cyprus</option><option value="CZ" <?php echo $value=='CZ'? 'selected': null; ?> >Czech Republic</option><option value="DK" <?php echo $value=='DK'? 'selected': null; ?> >Denmark</option><option value="DJ" <?php echo $value=='DJ'? 'selected': null; ?> >Djibouti</option><option value="DM" <?php echo $value=='DM'? 'selected': null; ?> >Dominica</option><option value="DO" <?php echo $value=='DO'? 'selected': null; ?> >Dominican Republic</option>
				<option value="EC" <?php echo $value=='EC'? 'selected': null; ?> >Ecuador</option><option value="EG" <?php echo $value=='EG'? 'selected': null; ?> >Egypt</option><option value="SV" <?php echo $value=='SV'? 'selected': null; ?> >El Salvador</option><option value="GQ" <?php echo $value=='GQ'? 'selected': null; ?> >Equatorial Guinea</option><option value="ER" <?php echo $value=='ER'? 'selected': null; ?> >Eritrea</option><option value="EE" <?php echo $value=='EE'? 'selected': null; ?> >Estonia</option><option value="ET" <?php echo $value=='ET'? 'selected': null; ?> >Ethiopia</option><option value="FK" <?php echo $value=='FK'? 'selected': null; ?> >Falkland Islands (Malvinas)</option><option value="FO" <?php echo $value=='FO'? 'selected': null; ?> >Faroe Islands</option><option value="FJ" <?php echo $value=='FJ'? 'selected': null; ?> >Fiji</option><option value="FI" <?php echo $value=='FI'? 'selected': null; ?> >Finland</option>
				<option value="FR" <?php echo $value=='FR'? 'selected': null; ?> >France</option><option value="GF" <?php echo $value=='GF'? 'selected': null; ?> >French Guiana</option><option value="PF" <?php echo $value=='PF'? 'selected': null; ?> >French Polynesia</option><option value="TF" <?php echo $value=='TF'? 'selected': null; ?> >French Southern Territories</option><option value="GA" <?php echo $value=='GA'? 'selected': null; ?> >Gabon</option><option value="GM" <?php echo $value=='GM'? 'selected': null; ?> >Gambia</option><option value="GE" <?php echo $value=='GE'? 'selected': null; ?> >Georgia</option><option value="DE" <?php echo $value=='DE'? 'selected': null; ?> >Germany</option><option value="GH" <?php echo $value=='GH'? 'selected': null; ?> >Ghana</option><option value="GI" <?php echo $value=='GI'? 'selected': null; ?> >Gibraltar</option><option value="GR" <?php echo $value=='GR'? 'selected': null; ?> >Greece</option>
				<option value="GL" <?php echo $value=='GL'? 'selected': null; ?> >Greenland</option><option value="GD" <?php echo $value=='GD'? 'selected': null; ?> >Grenada</option<option value="GP" <?php echo $value=='GP'? 'selected': null; ?> >Guadeloupe</option><option value="GU" <?php echo $value=='GU'? 'selected': null; ?> >Guam</option><option value="GT" <?php echo $value=='GT'? 'selected': null; ?> >Guatemala</option><option value="GG" <?php echo $value=='GG'? 'selected': null; ?> >Guernsey</option><option value="GN" <?php echo $value=='GN'? 'selected': null; ?> >Guinea</option><option value="GW" <?php echo $value=='GW'? 'selected': null; ?> >Guinea-Bissau</option><option value="GY" <?php echo $value=='GY'? 'selected': null; ?> >Guyana</option><option value="HT" <?php echo $value=='HT'? 'selected': null; ?> >Haiti</option>
				<option value="HM" <?php echo $value=='HM'? 'selected': null; ?> >Heard Island and McDonald Islands</option><option value="VA" <?php echo $value=='VA'? 'selected': null; ?> >Holy See (Vatican City State)</option><option value="HN" <?php echo $value=='HN'? 'selected': null; ?> >Honduras</option><option value="HK" <?php echo $value=='HK'? 'selected': null; ?> >Hong Kong</option><option value="HU" <?php echo $value=='HU'? 'selected': null; ?> >Hungary</option><option value="IS" <?php echo $value=='IS'? 'selected': null; ?> >Iceland</option><option value="IN" <?php echo $value=='IN'? 'selected': null; ?> >India</option><option value="ID" <?php echo $value=='ID'? 'selected': null; ?> >Indonesia</option><option value="IR" <?php echo $value=='IR'? 'selected': null; ?> >Iran, Islamic Republic of</option><option value="IQ" <?php echo $value=='IQ'? 'selected': null; ?> >Iraq</option><option value="IE" <?php echo $value=='IE'? 'selected': null; ?> >Ireland</option>
				<option value="IM" <?php echo $value=='IM'? 'selected': null; ?> >Isle of Man</option><option value="IL" <?php echo $value=='IL'? 'selected': null; ?> >Israel</option><option value="IT" <?php echo $value=='IT'? 'selected': null; ?> >Italy</option><option value="JM" <?php echo $value=='JM'? 'selected': null; ?> >Jamaica</option><option value="JP" <?php echo $value=='JP'? 'selected': null; ?> >Japan</option><option value="JE" <?php echo $value=='JE'? 'selected': null; ?> >Jersey</option><option value="JO" <?php echo $value=='JO'? 'selected': null; ?> >Jordan</option><option value="KZ" <?php echo $value=='KZ'? 'selected': null; ?> >Kazakhstan</option><option value="KE" <?php echo $value=='KE'? 'selected': null; ?> >Kenya</option><option value="KI" <?php echo $value=='KI'? 'selected': null; ?> >Kiribati</option><option value="KP" <?php echo $value=='KP'? 'selected': null; ?> >Korea, Democratic People's Republic of</option>
				<option value="KR" <?php echo $value=='KR'? 'selected': null; ?> >Korea, Republic of</option><option value="KW" <?php echo $value=='KW'? 'selected': null; ?> >Kuwait</option><option value="KG" <?php echo $value=='KG'? 'selected': null; ?> >Kyrgyzstan</option><option value="LA" <?php echo $value=='LA'? 'selected': null; ?> >Lao People's Democratic Republic</option><option value="LV" <?php echo $value=='LV'? 'selected': null; ?> >Latvia</option><option value="LB" <?php echo $value=='LB'? 'selected': null; ?> >Lebanon</option><option value="LS" <?php echo $value=='LS'? 'selected': null; ?> >Lesotho</option><option value="LR" <?php echo $value=='LR'? 'selected': null; ?> >Liberia</option><option value="LY" <?php echo $value=='LY'? 'selected': null; ?> >Libya</option><option value="LI" <?php echo $value=='LI'? 'selected': null; ?> >Liechtenstein</option><option value="LT" <?php echo $value=='LT'? 'selected': null; ?> >Lithuania</option>
				<option value="LU" <?php echo $value=='LU'? 'selected': null; ?> >Luxembourg</option><option value="MO" <?php echo $value=='MO'? 'selected': null; ?> >Macao</option><option value="MK" <?php echo $value=='MK'? 'selected': null; ?> >Macedonia, the former Yugoslav Republic of</option><option value="MG" <?php echo $value=='MG'? 'selected': null; ?> >Madagascar</option><option value="MW" <?php echo $value=='MW'? 'selected': null; ?> >Malawi</option><option value="MY" <?php echo $value=='MY'? 'selected': null; ?> >Malaysia</option><option value="MV" <?php echo $value=='MV'? 'selected': null; ?> >Maldives</option><option value="ML" <?php echo $value=='ML'? 'selected': null; ?> >Mali</option><option value="MT" <?php echo $value=='MT'? 'selected': null; ?> >Malta</option><option value="MH" <?php echo $value=='MH'? 'selected': null; ?> >Marshall Islands</option><option value="MQ" <?php echo $value=='MQ'? 'selected': null; ?> >Martinique</option>
				<option value="MR" <?php echo $value=='MR'? 'selected': null; ?> >Mauritania</option><option value="MU" <?php echo $value=='MU'? 'selected': null; ?> >Mauritius</option><option value="YT" <?php echo $value=='YT'? 'selected': null; ?> >Mayotte</option><option value="MX" <?php echo $value=='MX'? 'selected': null; ?> >Mexico</option><option value="FM" <?php echo $value=='FM'? 'selected': null; ?> >Micronesia, Federated States of</option><option value="MD" <?php echo $value=='MD'? 'selected': null; ?> >Moldova, Republic of</option><option value="MC" <?php echo $value=='MC'? 'selected': null; ?> >Monaco</option><option value="MN" <?php echo $value=='MN'? 'selected': null; ?> >Mongolia</option><option value="ME" <?php echo $value=='ME'? 'selected': null; ?> >Montenegro</option><option value="MS" <?php echo $value=='MS'? 'selected': null; ?> >Montserrat</option><option value="MA" <?php echo $value=='MA'? 'selected': null; ?> >Morocco</option>
				<option value="MZ" <?php echo $value=='MZ'? 'selected': null; ?> >Mozambique</option><option value="MM" <?php echo $value=='MM'? 'selected': null; ?> >Myanmar</option><option value="NA" <?php echo $value=='NA'? 'selected': null; ?> >Namibia</option><option value="NR" <?php echo $value=='NR'? 'selected': null; ?> >Nauru</option><option value="NP" <?php echo $value=='NP'? 'selected': null; ?> >Nepal</option><option value="NL" <?php echo $value=='NL'? 'selected': null; ?> >Netherlands</option><option value="NC" <?php echo $value=='NC'? 'selected': null; ?> >New Caledonia</option><option value="NZ" <?php echo $value=='NZ'? 'selected': null; ?> >New Zealand</option><option value="NI" <?php echo $value=='NI'? 'selected': null; ?> >Nicaragua</option><option value="NE" <?php echo $value=='NE'? 'selected': null; ?> >Niger</option><option value="NG" <?php echo $value=='NG'? 'selected': null; ?> >Nigeria</option>
				<option value="NU" <?php echo $value=='NU'? 'selected': null; ?> >Niue</option><option value="NF" <?php echo $value=='NF'? 'selected': null; ?> >Norfolk Island</option><option value="MP" <?php echo $value=='MP'? 'selected': null; ?> >Northern Mariana Islands</option><option value="NO" <?php echo $value=='NO'? 'selected': null; ?> >Norway</option><option value="OM" <?php echo $value=='OM'? 'selected': null; ?> >Oman</option><option value="PK" <?php echo $value=='PK'? 'selected': null; ?> >Pakistan</option><option value="PW" <?php echo $value=='PW'? 'selected': null; ?> >Palau</option><option value="PS" <?php echo $value=='PS'? 'selected': null; ?> >Palestinian Territory, Occupied</option><option value="PA" <?php echo $value=='PA'? 'selected': null; ?> >Panama</option><option value="PG" <?php echo $value=='PG'? 'selected': null; ?> >Papua New Guinea</option><option value="PY" <?php echo $value=='PY'? 'selected': null; ?> >Paraguay</option>
				<option value="PE" <?php echo $value=='PE'? 'selected': null; ?> >Peru</option><option value="PH" <?php echo $value=='PH'? 'selected': null; ?> >Philippines</option><option value="PN" <?php echo $value=='PN'? 'selected': null; ?> >Pitcairn</option><option value="PL" <?php echo $value=='PL'? 'selected': null; ?> >Poland</option><option value="PT" <?php echo $value=='PT'? 'selected': null; ?> >Portugal</option><option value="PR" <?php echo $value=='PR'? 'selected': null; ?> >Puerto Rico</option><option value="QA" <?php echo $value=='QA'? 'selected': null; ?> >Qatar</option><option value="RE" <?php echo $value=='RE'? 'selected': null; ?> >Réunion</option><option value="RO" <?php echo $value=='RO'? 'selected': null; ?> >Romania</option><option value="RU" <?php echo $value=='RU'? 'selected': null; ?> >Russian Federation</option><option value="RW" <?php echo $value=='RW'? 'selected': null; ?> >Rwanda</option>
				<option value="BL" <?php echo $value=='BL'? 'selected': null; ?> >Saint Barthélemy</option><option value="SH" <?php echo $value=='SH'? 'selected': null; ?> >Saint Helena, Ascension and Tristan da Cunha</option><option value="KN" <?php echo $value=='KN'? 'selected': null; ?> >Saint Kitts and Nevis</option><option value="LC" <?php echo $value=='LC'? 'selected': null; ?> >Saint Lucia</option><option value="MF" <?php echo $value=='MF'? 'selected': null; ?> >Saint Martin (French part)</option><option value="PM" <?php echo $value=='PM'? 'selected': null; ?> >Saint Pierre and Miquelon</option><option value="VC" <?php echo $value=='VC'? 'selected': null; ?> >Saint Vincent and the Grenadines</option><option value="WS" <?php echo $value=='WS'? 'selected': null; ?> >Samoa</option><option value="SM" <?php echo $value=='SM'? 'selected': null; ?> >San Marino</option><option value="ST" <?php echo $value=='ST'? 'selected': null; ?> >Sao Tome and Principe</option><option value="SA" <?php echo $value=='SA'? 'selected': null; ?> >Saudi Arabia</option>
				<option value="SN" <?php echo $value=='SN'? 'selected': null; ?> >Senegal</option><option value="RS" <?php echo $value=='RS'? 'selected': null; ?> >Serbia</option><option value="SC" <?php echo $value=='SC'? 'selected': null; ?> >Seychelles</option><option value="SL" <?php echo $value=='SL'? 'selected': null; ?> >Sierra Leone</option><option value="SG" <?php echo $value=='SG'? 'selected': null; ?> >Singapore</option><option value="SX" <?php echo $value=='SX'? 'selected': null; ?> >Sint Maarten (Dutch part)</option><option value="SK" <?php echo $value=='SK'? 'selected': null; ?> >Slovakia</option><option value="SI" <?php echo $value=='SI'? 'selected': null; ?> >Slovenia</option><option value="SB" <?php echo $value=='SB'? 'selected': null; ?> >Solomon Islands</option><option value="SO" <?php echo $value=='SO'? 'selected': null; ?> >Somalia</option><option value="ZA" <?php echo $value=='ZA'? 'selected': null; ?> >South Africa</option>
				<option value="GS" <?php echo $value=='GS'? 'selected': null; ?> >South Georgia and the South Sandwich Islands</option><option value="SS" <?php echo $value=='SS'? 'selected': null; ?> >South Sudan</option><option value="ES" <?php echo $value=='ES'? 'selected': null; ?> >Spain</option><option value="LK" <?php echo $value=='LK'? 'selected': null; ?> >Sri Lanka</option><option value="SD" <?php echo $value=='SD'? 'selected': null; ?> >Sudan</option><option value="SR" <?php echo $value=='SR'? 'selected': null; ?> >Suriname</option><option value="SJ" <?php echo $value=='SJ'? 'selected': null; ?> >Svalbard and Jan Mayen</option><option value="SZ" <?php echo $value=='SZ'? 'selected': null; ?> >Swaziland</option><option value="SE" <?php echo $value=='SE'? 'selected': null; ?> >Sweden</option><option value="CH" <?php echo $value=='CH'? 'selected': null; ?> >Switzerland</option><option value="SY" <?php echo $value=='SY'? 'selected': null; ?> >Syrian Arab Republic</option>
				<option value="TW" <?php echo $value=='TW'? 'selected': null; ?> >Taiwan, Province of China</option><option value="TJ" <?php echo $value=='TJ'? 'selected': null; ?> >Tajikistan</option><option value="TZ" <?php echo $value=='TZ'? 'selected': null; ?> >Tanzania, United Republic of</option><option value="TH" <?php echo $value=='TH'? 'selected': null; ?> >Thailand</option<option value="TL" <?php echo $value=='TL'? 'selected': null; ?> >Timor-Leste</option><option value="TG" <?php echo $value=='TG'? 'selected': null; ?> >Togo</option><option value="TK" <?php echo $value=='TK'? 'selected': null; ?> >Tokelau</option><option value="TO" <?php echo $value=='TO'? 'selected': null; ?> >Tonga</option><option value="TT" <?php echo $value=='TT'? 'selected': null; ?> >Trinidad and Tobago</option><option value="TN" <?php echo $value=='TN'? 'selected': null; ?> >Tunisia</option>
				<option value="TR" <?php echo $value=='TR'? 'selected': null; ?> >Turkey</option><option value="TM" <?php echo $value=='TM'? 'selected': null; ?> >Turkmenistan</option><option value="TC" <?php echo $value=='TC'? 'selected': null; ?> >Turks and Caicos Islands</option><option value="TV" <?php echo $value=='TV'? 'selected': null; ?> >Tuvalu</option><option value="UG" <?php echo $value=='UG'? 'selected': null; ?> >Uganda</option><option value="UA" <?php echo $value=='UA'? 'selected': null; ?> >Ukraine</option><option value="AE" <?php echo $value=='AE'? 'selected': null; ?> >United Arab Emirates</option><option value="GB" <?php echo $value=='GB'? 'selected': null; ?> >United Kingdom</option><option value="US" <?php echo $value=='US'? 'selected': null; ?> >United States</option><option value="UM" <?php echo $value=='UM'? 'selected': null; ?> >United States Minor Outlying Islands</option><option value="UY" <?php echo $value=='UY'? 'selected': null; ?> >Uruguay</option>
				<option value="UZ" <?php echo $value=='UZ'? 'selected': null; ?> >Uzbekistan</option><option value="VU" <?php echo $value=='VU'? 'selected': null; ?> >Vanuatu</option><option value="VE" <?php echo $value=='VE'? 'selected': null; ?> >Venezuela, Bolivarian Republic of</option><option value="VN" <?php echo $value=='VN'? 'selected': null; ?> >Viet Nam</option><option value="VG" <?php echo $value=='VG'? 'selected': null; ?> >Virgin Islands, British</option><option value="VI" <?php echo $value=='VI'? 'selected': null; ?> >Virgin Islands, U.S.</option><option value="WF" <?php echo $value=='WF'? 'selected': null; ?> >Wallis and Futuna</option><option value="EH" <?php echo $value=='EH'? 'selected': null; ?> >Western Sahara</option><option value="YE" <?php echo $value=='YE'? 'selected': null; ?> >Yemen</option><option value="ZM" <?php echo $value=='ZM'? 'selected': null; ?> >Zambia</option><option value="ZW" <?php echo $value=='ZW'? 'selected': null; ?> >Zimbabwe</option>
			</select>
		</div>
		<?php

		elseif ($field->type=='state'): ?>
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

		<?php elseif ($field->type=='select'): ?>
		<div class="form-group <?php echo implode(' ', $field->class); ?>">
			<label><?php echo $field->label; ?></label>
			<select name="<?php echo $key; ?>" class="form-control">
				<?php foreach($field->options as $opt_value=>$opt_label): ?>
				<option value="<?php echo $opt_value; ?>" <?php if ($opt_value==$value) { echo 'selected'; } ?>><?php echo $opt_label; ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<?php else: ?>
		<div class="form-group <?php echo implode(' ', $field->class); ?>">
			<label><?php echo $field->label; ?></label>
			<input type="<?php echo $field->type; ?>"
				class="form-control"
				placeholder="<?php echo $field->placeholder; ?>"
				name="<?php echo $key; ?>" value="<?php echo $value; ?>"
				<?php if ($field->mask) { echo "data-mask='{$field->mask}'"; } ?>
			>
		</div>
		<?php endif;
	}
}

foreach(['woocommerce_checkout_after_customer_details', 'woocommerce_after_edit_address_form_billing', 'woocommerce_after_edit_address_form_shipping'] as $action):
	add_action($action, function() { ?>
	<script>jQuery(document).ready(function($) {
		var _handle = function() {
			var persontype = $("[name=billing_persontype]").val();

			if (persontype==1) {
				$("[name=billing_cpf]").closest(".form-group").fadeIn(200);
				$("[name=billing_cnpj], [name=billing_company]").closest(".form-group").hide();
			}
			else if (persontype==2) {
				$("[name=billing_cpf]").closest(".form-group").hide();
				$("[name=billing_cnpj], [name=billing_company]").closest(".form-group").fadeIn(200);
			}
		}

		$("[name=billing_persontype]").on("change", _handle);
		_handle();


		["billing_postcode", "shipping_postcode"].forEach(function(input_name) {
			$(`input[name=${input_name}]`).on("keyup blue", function(ev) {
				if (ev.target.value.length < 8) return;
				$.get(`https://viacep.com.br/ws/${ev.target.value.replace(/[^0-9]/g, '')}/json/`, function(resp) {
					if (input_name=="billing_postcode") {
						$("input[name=billing_address_1]").val(resp.logradouro);
						$("input[name=billing_neighborhood]").val(resp.bairro);
						$("input[name=billing_city]").val(resp.localidade);
						$("select[name=billing_state]").val(resp.uf);
					}
					else if (input_name=="shipping_postcode") {
						$("input[name=shipping_address_1]").val(resp.logradouro);
						$("input[name=shipping_neighborhood]").val(resp.bairro);
						$("input[name=shipping_city]").val(resp.localidade);
						$("select[name=shipping_state]").val(resp.uf);
					}
				}, "json");
			});
		});
	});</script><?php });
endforeach;


