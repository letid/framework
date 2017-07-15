<?php
namespace letId\form;
trait initiate
{
	public function setting($state=array())
	{
		$this->state			= $state;
		$this->form 			= $this->requestMethod();
		$this->submit 			= $this->requestPostset($this->formName);
		$this->table			= $this->requestState('table');
		$this->message			= $this->requestState('msg');

		$maskDefault			= $this->requestState('mask');
		$classDefault 			= $this->requestState('class');

		$setting 				= $this->requestState('row'); // rows vals rowrows ssets rows input data key, val, rows vals sets gets post
		$support				= $this->requestSupport();

		foreach ($setting as $fillName => $is)
		{
			$valueName = $this->formName.'.value.'.$fillName;
			$selectName = $this->formName.'.select.'.$fillName;
			$maskName = $this->formName.'.mask.'.$fillName;
			$setting[$fillName]['maskName'] = $maskName;
			$className = $this->formName.'.class.'.$fillName;
			// $setting[$fillName]['className'] = $className;
			$visibilityName = $this->formName.'.visibility.'.$fillName;
			$classValue = array($fillName);

			if ($this->submit) {
				if ($this->requestPostset($fillName)) {
					$fillValue = $this->requestPostvalue($valueName,$this->requestPosthas($fillName));
				} elseif (isset($is['select'])) {
					$fillValue = $this->requestPostvalue($valueName,array());
				} else {
					$fillValue = $this->requestPostvalue($valueName,$is['value']);
				}
				$setting[$fillName]['value'] = $fillValue;
			} else {
				if (isset($_GET[$fillName])) {
					$fillValue = $this->requestPostvalue($valueName,$_GET[$fillName]);
				} elseif (isset($support[$fillName])) {
					$fillValue = $this->requestPostvalue($valueName,$support[$fillName]);
				} elseif (isset($is['value'])) {
					$fillValue = $this->requestPostvalue($valueName,$is['value']);
				}
			}
			/*
				'value'=>array(),
				'type'=>'radio',
				'select'=>array(
					'MA'=>'Male',
					'FE'=>'Female'
				)
			*/
			if (isset($is['select'])) {
				if (isset($is['type'])) {
					$typeValue = $is['type'];
					if ($typeValue == 'option') {
						$selectValue = $this->requestSelectOption($is['select'],$fillName,$fillValue);
					} elseif ($typeValue =='radio') {
						$selectValue = $this->requestInputRadio($is['select'],$fillName,$fillValue);
					} elseif ($typeValue == 'checkbox') {
						$selectValue = $this->requestInputCheckbox($is['select'],$fillName,$fillValue);
					}
				}
				// $selectValue = avail::html('option')->text('Ok')->attr(array('value'=>'abc','checked'))->response();
				// $selectValue = \letId\lethil\avail::html('b')->selectOption($is['select']);
				// $selectValue = avail::html($fillValue)->selectOption($is['select']);
				// TODO: this has to improved
				if (isset($selectValue)) {
					avail::content($selectName)->set(avail::html($selectValue));
				}
			}
			/*
				'visibility'=>'readonly',
				'visibility'=>array(
					'readonly'
				),
				'visibility'=>array(
					'email'=>'readonly'
				)
			*/
			if (isset($is['visibility'])) {
				$this->requestVisibility($visibilityName,$is['visibility'],$fillName);
			}
			/*
				'require'=>array(
					'mask'=>'*',
					'class'=>'required',
					'status'=>'Username'
				)
			*/
			if (isset($is['require']) && is_array($requireValue=$is['require'])) {
				if ($this->submit) {
					if (!$fillValue) {
						$this->error[] = $this->requestStatushas($fillName,$requireValue);
						$this->requestMaskhas($maskName,$requireValue);
						if ($classDefault) {
							array_push($classValue,$classDefault);
						}
						if (isset($requireValue['class'])) {
							array_push($classValue,$requireValue['class']);
						}
					} else {
						// print_r($fillValue);
						// echo $fillName;
					}
				} else {
					$this->requestMaskhas($maskName,$maskDefault);
				}
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
				'validate'=>array(
					'task'=>'method from app\validation',
					'mask'=>'Invalid',
					'status'=>'a valid Email'
				),
			*/
			if (isset($is['validate']) && is_array($validateValue=$is['validate'])) {
				if ($this->submit && $fillValue) {
					if (isset($validateValue['filter'])) {
						// avail::filter(EMAIL)->email();
						// call_user_func_array(array($foo, "bar"), array("three", "four"));
						// call_user_func_array(array(avail::filter(EMAIL), "bar"), array("three", "four"));
						// call_user_func_array(array(avail::filter($fillValue), "bar"), array("three", "four"));
						// TODO: need to do smarter
						if (!isset($validateValue['task'])) {
							$validateValue['task'] = array();
						}
						if (avail::filter($fillValue)->response($validateValue['filter'],$validateValue['task']) == false) {
							$this->error[] = $this->requestStatushas($fillName,$validateValue);
							$this->requestMaskhas($maskName,$validateValue);
							if ($classDefault) {
								array_push($classValue,$classDefault);
							}
							if (isset($validateValue['class'])) {
								array_push($classValue,$validateValue['class']);
							}
						}
					} elseif (isset($validateValue['task'])) {
						$validateTaskValue = $validateValue['task'];
						if (s_scalar($validateTaskValue)) {
							if ($validateTaskObject = avail::assist(avail::validation())->is_callable($validateTaskValue)) {
								if (call_user_func_array($validateTaskObject, array($is['value'], $fillName)) == false) {
									$this->error[] = $this->requestStatushas($fillName,$validateValue);
									$this->requestMaskhas($maskName,$validateValue);
									if ($classDefault) {
										array_push($classValue,$classDefault);
									}
									if (isset($validateValue['class'])) {
										array_push($classValue,$validateValue['class']);
									}
								}
							}
						}
					}
				}
			}
			$this->requestClasshas($className,$classValue);
		}
		// NOTE: registration start
		if ($this->submit) {
			if ($this->error) {
				$this->message = avail::language('require VALUE')->get(array(
					'value'=>avail::arrays($this->error)->to_sentence()
				));
			} else {
				// NOTE: success validation, and begin custom Methods
				foreach ($setting as $fillName => $is)
				{
					if ($this->error) {
						break;
					}
					if (isset($is['id'])) {
						$this->formId[$fillName] = $is['value'];
					} else {
						$this->formPost[$fillName] = $is['value'];
					}
					/*
						'custom'=>array(
                            'Duplicate'=>array(
                                'mask'=>'Exists',
                                'status'=>'* is already exists.'
                            ),
							'Encrypt'=>array(
                                'modify'=>true
                            )
                        )
						'custom'=>array(
                            'Exists'=>array(
                                'task'=>array(
                                    array('userid',1)
                                ),
                                'mask'=>'!',
                                'status'=>'Password is not correct.'
                            ),
                            'Encrypt'=>array(
                                'modify'=>true
                            )
                        )
					*/
					if (isset($is['custom']) && is_array($is['custom'])) {
						// echo 'checking custom';
						foreach ($is['custom'] as $customMethod => $customValue) {
							if (is_array($customValue)) {
								if (isset($customValue['task'])) {
									$customValue['task'] = array($is['value'], $fillName, $customValue['task']);
								} else {
									$customValue['task'] = array($is['value'], $fillName);
								}
								// TODO: check user class has callable, if not use here
								$customValidation = $this->requestCustomhas($customMethod);
								if ($customValidation) {
									$customValidationResponse = call_user_func_array($customValidation, $customValue['task']);
									if ($customValidationResponse) {
										if (isset($customValue['modify'])) {
											$is['value'] = $customValidationResponse;
											if (is_string($customValue['modify'])) {
												unset($this->formPost[$fillName]);
												unset($this->formId[$fillName]);
												$fillName = $customValue['modify'];
											}
											if (isset($is['id'])) {
												$this->formId[$fillName] = $is['value'];
											} else {
												$this->formPost[$fillName] = $is['value'];
											}
										}
									} else {
										$this->error = $this->requestStatushas($customMethod.' returned null',$customValue);
										if (isset($customValue['mask'])) {
											$this->requestMaskhas($is['maskName'],$customValue);
										}
										break;
									}
								}
							}
						}
					}
				}
				if ($this->error) {
					// NOTE: feil custom Methods
					$this->message = $this->error;
					$this->formPost = array();
					$this->formId = array();
				}
			}
		}
		return $this;
	}
}