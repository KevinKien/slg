<?php

namespace App\Libraries\ZingCredits;

interface ZC2_EncryptIF {
	public function encode($key, $value);
	//with expires_in_secs <= 0 : no need check expires
	public function decode($key, $cipherText);
}
