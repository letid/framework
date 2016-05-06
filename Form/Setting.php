<?php
namespace Letid\Form;
trait Setting
{
	public function setting()
	{
		$this->scoop_set(func_get_args()[0]);
		$this->formMethod();
		$formSubmit			= $this->issetPost($this->formName);
		$formPost			= array();
		$formStatus			= array();
		$formRequired 		= '*';
		$setting 			= $this->scoop_get($this->formName.'_setting');
		$this->formTable	= $this->scoop_get($this->formName.'_table');
		$this->formMessage	= $this->scoop_get($this->formName.'_message');
		$this->messageName 	= $this->formName.'_form_message';
		foreach ($setting as $fillName => $is) {
			$valueName = $this->formName.'_value_'.$fillName;
			$maskName = $this->formName.'_mask_'.$fillName;
			$setting[$fillName]['maskName'] = $maskName;
			/*
				'value'=>'default',
			*/
			if ($formSubmit) {
				if ($this->issetPost($fillName)) {
					$setting[$fillName]['value'] = $this->hasValue($valueName,$this->hasPost($fillName));
					$fillValue = $setting[$fillName]['value'];
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
						if (!forward_static_call_array(array(Validate::class, $isValidate['task']), array($fillValue))) {
							$formStatus[] = $this->hasStatus($fillName,$isValidate);
							$this->hasMask($maskName,$isValidate);
						}
					}
				}
			}
		}
		// NOTE: registration start
		if ($formSubmit){
			if ($formStatus) {
				$this->formMessage = $this->lang('required VALUE',
					array('value'=>$this->array_sentence($formStatus))
				);
			} else {
				// NOTE: success validation, and begin custom Methods
				foreach ($setting as $fillName => $is) {
					if ($formStatus) {
						break;
					}
					$formPost[$fillName]=$is['value'];
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
								// TODO: check user class has callable, if not use here
								$customValidation = $this->hasCustom($customMethod);
								if ($customValidation) {
									$isCustomResponse=call_user_func_array($customValidation, $isCustom['task']);
									if ($isCustomResponse) {
										if (isset($isCustom['modify'])) {
											$formPost[$fillName]=$isCustomResponse;
										}
									} else {
										$formStatus = $this->hasStatus('Error',$isCustom);
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
				if ($formStatus) {
					// NOTE: feil custom Methods
					$this->formMessage = $formStatus;
					// $this->responseError($Message);
				} else {
					// NOTE: success validation and custom Methods
					$this->formPost = $formPost;
				}
			}
		} else {
			// NOTE: default
			// $this->responseDefault($Message);
			// $this->hasMessage($this->messageName, 'form default', array('default'));
		}
		return $this;
	}
}