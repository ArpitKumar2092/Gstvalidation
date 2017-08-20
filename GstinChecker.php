<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');   
class Gstinchecker {
   
    public $GSTINFORMAT_REGEX = "/[0-9]{2}[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}[1-9A-Za-z]{1}[Z]{1}[0-9a-zA-Z]{1}/";
	public $GSTN_CODEPOINT_CHARS = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";


	/*
	* THIS IS PARENT METHOT TO CALL 
	* YOU JUST NEED TO PASS GST NUMBER IN IT
	* boolen check_my_gst_number ( String $param1)
	* Return boolen 1 if it is valid else 0 
	*/

    function check_my_gst_number($param1){
    	if($this->validategstin($param1)){
    		echo "1";
    		return(1);
    	}else{
    		echo "00;";
    		return(0);
    	}
    }

    function validategstin($gstNumber){
    	if(preg_match($this->GSTINFORMAT_REGEX, $gstNumber)){
    		if($this->gst_error_check($gstNumber) == $gstNumber){
    			return(1);
    		}else{
    			return(0);
    		}

    	}else{
    		echo "0";
    		return(0);
    	}
    	
    }

    function gst_error_check($gst_number){
    	$input =  str_split($gst_number);
    	$inputChars = $input;
    	unset($inputChars[14]); 
    	
    	$factor = 2;
		$sum = 0;
		$checkCodePoint = 0;
		$cpChars = str_split($this->GSTN_CODEPOINT_CHARS);
		$mod = count($cpChars);

		for ($i = count($inputChars) - 1; $i >= 0; $i--) {
				$codePoint = -1;
				for ($j = 0; $j < count($cpChars); $j++) {
					if ($cpChars[$j] == $inputChars[$i]) {
						$codePoint = $j;
					}
				}
				
				$digit = $factor * $codePoint;

				$factor = ($factor == 2) ? 1 : 2;
				$x =  ($digit % $mod).'<br>';
				#echo $x;
				$digit = (int) ($digit / $mod) + (int) ($digit % $mod);

				$sum += $digit;
			}
			$checkCodePoint = ($mod - ($sum % $mod)) % $mod;
			$inputChars = implode('', $inputChars);
    	return($inputChars.$cpChars[$checkCodePoint]);
    }
}
?>