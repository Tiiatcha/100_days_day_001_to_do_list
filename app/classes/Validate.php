<?php
	class Validate {
		private	$_passed = false,
				$_errors = array(),
				$db = null;
		public function __construct() {
			$this->_db = DB::getInstance();
		}
		
		public function check($source, $items = array()) {
			foreach($items as $item => $rules) {
				foreach($rules as $rule => $rule_value) {	
                    
					$value = trim($source[$item]);
                  //echo $item. " - ". $rules. " - ".$rule." - ".$rule_value." - ".$value."<br>";
					if($rule === 'required' && strlen($value) == 0) {
						$this->addError('required',"{$item} is required");
					} else if (!empty($value)) {
						
						switch($rule) {
							case 'min':
								if(strlen($value) < $rule_value) {
									$this->addError('min',"{$item} must be a minimum of {$rule_value} characters");
								}
							break;
							case 'max':
							if(strlen($value) > $rule_value) {
									$this->addError('max',"{$item} must be no more than {$rule_value} characters");
								}
							break;
                            case 'num_equal':
							if($value == $rule_value) {
									$this->addError('equal',"{$item} must equal to {$rule_value}");
								}
							break;
							case 'mindate':
							if($value > $rule_value && strlen($value) != 0) {
									$this->addError('mindate',"{$item} The 'To Date' must be after the 'From Date'!");
								}
							break;
							case 'matches':
							if($value != $source[$rule_value]) {
									$this->addError('matches',"{$rule_value} must match {$item}");
								}
							break;
							case 'unique':
								$check = $this->_db->get($rule_value, array($item, '=', $value));
								if($check->count()) {
									$this->addError('unique',"{$item} already exists");
								}
							break;
                            case 'exists':
                            $check = $this->_db->get($rule_value['table'], array($rule_value['field'], '=', $value));
                            if(!$check->count()) {
                                $this->addError('exists',"Your Payroll number ({$item}) does not exist does not exist!");
                            }
							break;
						}
					}
				}
			}
			if(empty($this->_errors)) {
				$this->_passed = true;
			}
			return $this;
		}
		private function addError($key,$error) {
			$this->_errors[] = array($key => $error);
		}
		public function errors() {
			return $this->_errors;
		}
		public function passed() {
			return $this->_passed;
		}
	}
?>