<?php
namespace App\Validation;

class CustomRules{

    public function pass_Validation(string $str, string $fields, array $data){
    
        $r1='/[A-Z]/';  //Символ в верхнем регистре
		$r2='/[a-z]/';  //Символ в нижнем регистре
		$r3='/[!@#$%^&*()\-_=+{};:,<.>]/';  // Специальный символ
		$r4='/[0-9]/';  //Число
		$word=$data['password'];
		if(preg_match($r1,$word)){
			if(preg_match($r2,$word)){
				if(preg_match($r3,$word)){
					if(preg_match($r4,$word)){
						return true;
					}
					else{
						return false;
					}
				}
				else{
					return false;
				}
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
    }

    public function email_mpt_Validation(string $str, string $fields, array $data){
		$domen = substr($data['email'], strrpos($data['email'], '@')+1);
		if($domen=="mpt.ru"){
            return true;
        }
        else{
            return false;
        }
    }

	public function sex_Validation(string $str, string $fields, array $data){
		$sex = $data['sex'];
		if($sex){
            if($sex!="Мужской"&&$sex!="Женский"){
				return false;
			}
			else{
				return true;
			}
        }
        else{
            return true;
        }
    }

	public function date_birth_Validation(string $str, string $fields, array $data){
		if(!empty($data['date_birth'])){
			$test=strtotime($data['date_birth']);
			$year1=date('Y', $test);
			$year2=date('Y', time());
			$dif=$year2-$year1;
			if($dif>=17){
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return true;
		}
    }

	public function is_salary_Validation(string $str, string $fields, array $data){
		if($data['salary']){
			if(is_numeric($data['salary'])){
				if($data['salary']>=0){
					return true;
				}
				else{
					return false;
				}
			}
			else{
				return false;
			}
		}
		else{
			return true;
		}

    }
}