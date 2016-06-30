<?php
namespace Letid\Form;
trait Initiate
{
	public function initiate($args=array())
	{
		self::$scope=(object) $args;
		return $this->setting();
	}
	private function setting()
	{
		$this->formData 		= $this->formSwitch();
		$this->formSubmit 		= $this->issetPost($this->formName);
		$formPost				= array();
		$formId					= array();
		$formError				= array();
		$maskDefault			= $this->scope('mask');
		$classDefault 			= $this->scope('class');
		$settingKey 			= $this->scope('setting');
		$settingValue			= $this->scope('settingValue');
		$this->formTable		= $this->scope('table');
		$this->formMessage		= $this->scope('message');
		$this->formMessageName	= $this->formName.'_form_message';
		if (!$this->formSubmit) {
			$settingValue = self::$database->select()->from($this->formTable)->where($settingValue)->execute()->toArray()->rows[0];
		}
		foreach ($settingKey as $fillName => $is) {
			$valueName = $this->formName.'_value_'.$fillName;
			$maskName = $this->formName.'_mask_'.$fillName;
			$className = $this->formName.'_class_'.$fillName;
			$visibilityName = $this->formName.'_visibility_'.$fillName;
			// resetpassword_visibility_code
			$settingKey[$fillName]['maskName'] = $maskName;
			// $settingKey[$fillName]['className'] = $className;
			$classValue = array($fillName);
			/*
				'value'=>'default',
			*/
			if ($this->formSubmit) {
				if ($this->issetPost($fillName)) {
					$settingKey[$fillName]['value'] = $this->hasValue($valueName,$this->hasPost($fillName));
					$fillValue = $settingKey[$fillName]['value'];
				}
				if ($_GET[$fillName]) {
					self::content($visibilityName)->set('readonly');
				}
				// if ($fillName =='code') {
				// 	echo $this->hasPost($fillName);
				// }
			} else {

				if ($_GET[$fillName]) {
					$fillValue = $this->hasValue($valueName,$_GET[$fillName]);
					self::content($visibilityName)->set('readonly');
				} elseif ($settingValue) {
					$fillValue = $this->hasValue($valueName,$settingValue[$fillName]);
				} else {
					$fillValue = $this->hasValue($valueName,$is);
				}
				// $fillValue = $this->hasValue($valueName,$is);
			}
			/*
				'require'=>array(
					'mask'=>'Required',
					'class'=>'required',
					'status'=>'Username'
				)
			*/
			if (isset($is['require']) && is_array($isRequire=$is['require'])) {
				if ($this->formSubmit) {
					if (!$fillValue) {
						$formError[] = $this->hasStatus($fillName,$isRequire);
						$this->hasMask($maskName,$isRequire);
						if ($classDefault) {
							array_push($classValue,$classDefault);
						}
						if (isset($isRequire['class'])) {
							array_push($classValue,$isRequire['class']);
						}
					}
				} else {
					$this->hasMask($maskName,$maskDefault);
				}
				// if ($this->formSubmit) {
				// 	if (!$fillValue) {
				// 		$formError[] = $this->hasStatus($fillName,$isRequire);
				// 		$this->hasMask($maskName,$isRequire);
				// 	}
				// } else {
				// 	$this->hasMask($maskName,$maskDefault);
				// }
				// if (isset($isRequire['class'])) {
				// 	if ($this->formSubmit) {
				// 		if (!$fillValue) {
				// 			$this->hasClass($className,array($fillName,$isRequire['class']));
				// 		}
				// 	} else {
				// 		$this->hasClass($className,$fillName);
				// 	}
				// }
			}
			/*
				'validate'=>array(
					'filter'=>FILTER_VALIDATE_EMAIL,
					'task'=>FILTER_FLAG_PATH_REQUIRED,
					'task'=>array(
					    'flags' => FILTER_NULL_ON_FAILURE
					),
					'task'=>array(
					    'flags'=>FILTER_FLAG_ALLOW_OCTAL
					    'options' => array(
					        'default' => 3,
					        'min_range' => 0
					    )
					),
					'mask'=>'Invalid',
					'status'=>'a valid Email'
				),
			*/
			if (isset($is['validate']) && is_array($isValidate=$is['validate'])) {
				if ($this->formSubmit && $fillValue) {
					if (isset($isValidate['filter'])) {
						// Application::filter(EMAIL)->email();
						// call_user_func_array(array($foo, "bar"), array("three", "four"));
						// call_user_func_array(array(self::filter(EMAIL), "bar"), array("three", "four"));
						// call_user_func_array(array(self::filter($fillValue), "bar"), array("three", "four"));
						if (self::filter($fillValue)->response($isValidate['filter'],@$isValidate['task']) == false) {
							$formError[] = $this->hasStatus($fillName,$isValidate);
							$this->hasMask($maskName,$isValidate);
							if ($classDefault) {
								array_push($classValue,$classDefault);
							}
							if (isset($isValidate['class'])) {
								array_push($classValue,$isValidate['class']);
							}
						}
					}
				}
			}
			$this->hasClass($className,$classValue);
		}

		// NOTE: registration start
		if ($this->formSubmit){
			// print_r($settingKey);
			if ($formError) {
				$this->formMessage = self::language('required VALUE')->get(array(
					'value'=>self::arrays($formError)->to_sentence()
				));
			} else {
				// NOTE: success validation, and begin custom Methods
				foreach ($settingKey as $fillName => $is) {
					if ($formError) {
						break;
					}
					if (isset($is['id'])) {
						// $formId[] = array($fillName,$is['value']);
						$formId[$fillName] = $is['value'];
					} else {
						$formPost[$fillName] = $is['value'];
					}
					/*
						'custom'=>array(
                            'Duplicate'=>array(
                                // 'task'=>array('a','b','c'),
								// 'task'=>'existsCheck',
                                'mask'=>'Exists',
                                'status'=>'* is already exists.'
                            ),
							'Encrypt'=>array(
                                'modify'=>true
                            )
                        )
					*/

					if (isset($is['custom']) && is_array($is['custom'])) {
						foreach ($is['custom'] as $customMethod => $isCustom) {
							if (is_array($isCustom)) {
								if (isset($isCustom['task'])) {
									$isCustom['task'] = array($is['value'], $fillName, $isCustom['task']);
									// if (is_array($isCustom['task'])) {
									// 	array_push($isCustom['task'], $is['value'], $fillName);
									// } else {
									// 	$isCustom['task'] = array($isCustom['task'], $is['value'], $fillName);
									// }
								} else {
									$isCustom['task'] = array($is['value'], $fillName);
								}
								// TODO: check user class has callable, if not use here
								$customValidation = $this->hasCustom($customMethod);
								if ($customValidation) {
									$isCustomResponse = call_user_func_array($customValidation, $isCustom['task']);
									if ($isCustomResponse) {
										if (isset($isCustom['modify'])) {
											$is['value'] = $isCustomResponse;
											if (is_string($isCustom['modify'])) {
												unset($formPost[$fillName]);
												unset($formId[$fillName]);
												$fillName = $isCustom['modify'];
											}
											if (isset($is['id'])) {
												$formId[$fillName] = $is['value'];
											} else {
												$formPost[$fillName] = $is['value'];
											}
										}
									} else {
										$formError = $this->hasStatus('Error',$isCustom);
										if (isset($isCustom['mask'])) {
											$this->hasMask($is['maskName'],$isCustom);
										}
										break;
									}
								}
							}
						}
					}

				}
				if ($formError) {
					// NOTE: feil custom Methods
					$this->formMessage = $formError;
				} else {
					// NOTE: success validation and custom Methods
					$this->formPost = $formPost;
					$this->formId = $formId;
				}
			}
		}
		$this->formError = $formError;
		return $this;
	}
}