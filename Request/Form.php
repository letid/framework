<?php
namespace Letid\Request;
class Form extends StaticConstructor
{
	/*
	local required method(Common):
		lang
		template
		html
		config
		data
	*/
	// static $custom;
	static function request()
	{
		return new self('formName',func_get_args()[0]);
	}
	public function setting()
	{
		self::$iClass = func_get_args()[0];
		$formName 		= $this->formName;
		$formSubmit		= isset($_POST[$formName]);
		$formData		= array();
		$formStatus		= array();
		$formRequired 	= '*';
		$setting 		= $this->settingConfiguration($formName.'_setting');
		$messageName	= $this->messageName = $formName.'_form_message';
		// $this->data('registration_value_username','Ok');
		// $this->registration_value_username = 'what';
		// Config::$data['registration_value_username']= 'what';
		foreach ($setting as $fillName => $is) {
			$valueName = $formName.'_value_'.$fillName;
			$maskName = $formName.'_mask_'.$fillName;
			$setting[$fillName]['maskName'] = $maskName;
			/*
				'value'=>'abc',
			*/
			if ($formSubmit) {
				if (isset($_POST[$fillName])) {
					$setting[$fillName]['value'] = $this->hasValue($valueName,$_POST[$fillName]);
					$fillValue = $setting[$fillName]['value'];
					// $setting[$fillName]['value'] = $_POST[$fillName];
					// $this->hasValue($valueName,$setting[$fillName]);
				}
			} else {
				$fillValue = $this->hasValue($valueName,$is);
			}
			/*
				'require'=>array(
					'mask'=>'Required',
					'status'=>'Username'
				)
			*/
			if (isset($is['require']) && is_array($isRequire=$is['require'])) {
				$this->hasMask($maskName,$formRequired);
				if ($formSubmit) {
					if (!$fillValue) {
						$formStatus[] = $this->hasStatus($fillName,$isRequire);
						$this->hasMask($maskName,$isRequire);
					}
				}
			}
			/*
				'validate'=>array(
					'task'=>'email',
					'mask'=>'Email is not valid.',
					'status'=>'a valid Email'
				)
			*/
			if (isset($is['validate']) && is_array($isValidate=$is['validate'])) {
				if ($formSubmit && $fillValue) {
					if (isset($isValidate['task'])) {
						// call_user_func_array(array(Validate::class, $isValidate['task']), array($fillValue))
						// !forward_static_call_array(array(Validate, $isValidate['task']), array($fillValue))
						if (!forward_static_call_array(array(Validate::class, $isValidate['task']), array($fillValue))) {
							$formStatus[] = $this->hasStatus($fillName,$isValidate);
							$this->hasMask($maskName,$isValidate);
						}
					}
				}
			}
		}
		// print_r($setting);
		// NOTE: registration start
		if ($formSubmit){
			if ($formStatus) {
				$Message = $this->lang('required VALUE',
					array('value'=>$this->array_sentence($formStatus))
				);
				$this->hasMessage($messageName, $Message, array('default'));
			} else {
				// NOTE: success validation, and begin custom Methods
				foreach ($setting as $fillName => $is) {
					if ($formStatus) {
						$Message = $formStatus;
						break;
					}
					$formData[$fillName]=$is['value'];
					/*
						'custom'=>array(
							'customEmail'=>array(
								'task'=>'existsCheck',
								'mask'=>'Exists',
								'status'=>'Email is already exists.'
							),
							'customPasswordEncrypt'=>array(
		                        'modify'=>true
		                    )
						)
					*/
					if (isset($is['custom']) && is_array($is['custom'])) {
						foreach ($is['custom'] as $customMethod => $isCustom) {
							if (is_array($isCustom)) {
								if (isset($isCustom['task'])) {
									if (is_array($isCustom['task'])) {
										array_push($isCustom['task'], $is['value'], $fillName);
									} else {
										$isCustom['task'] = array($isCustom['task'], $is['value'], $fillName);
									}
								} else {
									$isCustom['task'] = array($is['value'], $fillName);
								}
								$isCustomResponse=call_user_func_array(array(self::$iClass, $customMethod), $isCustom['task']);
								if ($isCustomResponse) {
									if (isset($isCustom['modify'])) {
										$formData[$fillName]=$isCustomResponse;
									}
								} else {
									$formStatus = $this->hasStatus('Error',$isCustom);
									if ($isCustom['mask']) {
										$this->hasMask($is['maskName'],$isCustom);
									}
									break;
								}
							}
						}
					}
				}
				if ($formStatus) {
					// NOTE: feil custom Methods
					$this->hasMessage($messageName, $Message, array('error'));
				} else {
					// NOTE: success validation and custom Methods
					$this->formData = $formData;
					// $this->messageName = $messageName;
					// $this->hasMessage($messageName, $this->lang('Done'), array('success'));
					// echo $this->array_key_join_value($formData);
					// echo Utilities::array_key_join_value($formData);
					// return $formData;
				}
			}
		} else {
			// NOTE: default
			// $this->hasMessage($messageName, 'form default', array('default'));
		}
		return $this;
	}
	public function response()
	{
		// NOTE: insert, update, delete, select
		// $this->responseValue = 'what';
		// print_r(get_class_methods($this));
		// $this->responseTest = $this->data('test');
		// $this->hasMessage($this->messageName, $this->lang('Done'), array('success'));
		// print_r($this);
		if (isset($this->formData)) {
			// echo $this->array_key_join_value($this->formData);

			$this->hasMessage($this->messageName, $this->lang('Done'), array('success'));
			// $abc = Database::insert('userTable')->set($this->formData)->build();
			$abc = array_keys($this->formData);
			print_r($abc);
			// print_r($this);
		}
		return $this;
	}
	public function insert()
	{
		$rowsData = implode(', ', array_map(
            function ($v, $k) { return sprintf("%s='%s'", $k, $v); },
            $d, array_keys($d)
        ));
        // print_r($rowsData);
        $db=Database::load("INSERT INTO {$this->users_table} SET $rowsData, created = NOW(), modified = NOW()");
        print_r($db);
		// return $this;
	}
	public function select()
	{
		return $this;
	}
	public function update()
	{
		return $this;
	}
	public function delete()
	{
		return $this;
	}
	private function hasValue($valueName,$has) {
		if (is_scalar($has)) {
			return $this->data($valueName,$has);
		} else if (isset($has['value'])) {
			return $this->data($valueName,$has['value']);
		}
	}
	private function hasStatus($fillName,$has) {
		if (isset($has['status'])) {
			return $this->lang($has['status']);
		} elseif ($fillName) {
			return $fillName;
		}
	}
	private function hasMask($maskName,$has) {
		if (is_scalar($has)) {
			$this->data($maskName,$this->lang($has));
		} else if (isset($has['mask'])) {
			$this->data($maskName,$this->lang($has['mask']));
		}
	}
	private function hasMessage($messageName, $msg, $value) {
		$this->data($messageName,
			$this->hasMessageHtml(
				$msg, $value
			)
		);
	}
	private function hasMessageHtml($msg,$attr='message') {
		return $this->html(
		    array(
		    	'p'=>array(
		    		'text'=>$msg,
		    		'attr'=>array(
		    			'class'=>$attr
		    		)
		    	)
		    )
		);
	}
}