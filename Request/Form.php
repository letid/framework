<?php
namespace Letid\Request;
use Letid\Id\Validate;
class Form
{
	public function request($formName,$setting=array())
	{
		$formSubmit=isset($_POST[$formName]);
		$formData=array();
		$formStatus=array();
		$Star = '*';
		$messageName=$formName.'_form_message';
		foreach ($setting as $fillName => $is) {
			$valueName = $formName.'_value_'.$fillName;
			$maskName = $formName.'_mask_'.$fillName;
			$setting[$fillName]['maskName'] = $maskName;
			/*
				'value'=>'abc',
			*/
			if ($formSubmit) {
				if (isset($_POST[$fillName])) {
					$setting[$fillName]['value'] = $_POST[$fillName];
					self::hasValue($setting[$fillName],$valueName);
				}
			} else {
				self::hasValue($is,$valueName);
			}
			/*
				'require'=>array(
					'mask'=>'Required',
					'status'=>'Username'
				)
			*/
			if (is_array($is['require'])) {
				$isRequire = $is['require'];
				$this->{$maskName} = $Star;
				if ($formSubmit) {
					if (!$this->{$valueName}) {
						$formStatus[] = self::hasStatus($isRequire,$fillName);
						self::hasMask($isRequire,$maskName);
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
			if (is_array($is['validate'])) {
				$isValidate = $is['validate'];
				if ($formSubmit && $this->{$valueName}) {
					if ($isValidate['task']) {
						if (!forward_static_call_array(array(Validate::class, $isValidate['task']), array($this->{$valueName}))) {
							$formStatus[] = self::hasStatus($isValidate,$fillName);
							self::hasMask($isValidate,$maskName);
						}
					}
				}
			}
		}
		// NOTE: registration start
		// print_r($setting);
		if ($formSubmit){
			if ($formStatus) {
				// NOTE: fail validation
				$Message = $this->lang('required VALUE.',
					array('value'=>$this->joinAndOr($formStatus))
				);
				$this->{$messageName} = self::hasMessage(
					$Message, array('invalid')
				);
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
					if (is_array($is['custom'])) {
						foreach ($is['custom'] as $userFunction => $isCustom) {
							if (is_array($isCustom)) {
								if (is_array($isCustom['task'])) {
									array_push($isCustom['task'], $is['value'], $fillName);
								} else {
									if ($isCustom['task']) {
										$isCustom['task'] = array($isCustom['task'], $is['value'], $fillName);
									} else {
										$isCustom['task'] = array($is['value'], $fillName);
									}
								}
								$isCustomResponse=call_user_func_array(array($this, $userFunction), $isCustom['task']);
								if ($isCustomResponse) {
									if (isset($isCustom['modify'])) {
										$formData[$fillName]=$isCustomResponse;
									}
								} else {
									$formStatus = self::hasStatus($isCustom,'Error');
									if ($isCustom['mask']) {
										$this->{$is['maskName']} = $this->lang($isCustom['mask']);
									}
									break;
								}
							}
						}
					}
				}
				if ($formStatus) {
					// NOTE: feil custom Methods
					$this->{$messageName} = self::hasMessage(
						$Message, array('error')
					);
				} else {
					// NOTE: success validation and custom Methods
					$this->{$messageName} = self::hasMessage(
						$this->lang('Done'), array('success')
					);
					return $formData;
				}
			}
		} else {
			// NOTE: default
			$this->{$messageName} = self::hasMessage(
				$this->lang('form default'), array('default')
			);
		}
	}
	private function hasValue($has,$valueName) {
		if (isset($has['value'])) {
			$this->{$valueName} = $has['value'];
		}
	}
	private function hasStatus($has,$fillName=null) {
		if (isset($has['status'])) {
			return $this->lang($has['status']);
		} elseif ($fillName) {
			return $fillName;
		}
	}
	private function hasMask($has,$maskName) {
		if ($has['mask']) {
			$this->{$maskName} = $this->lang($has['mask']);
		}
	}
	private function hasMessage($msg,$attr='message') {
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
/*
if (is_array($is['require'])) {
	if ($formSubmit) {
		if ($_POST[$fillName]) {
		} else {
		}
	} else {
	}
}
*/
