<?php
include(APPPATH.'libraries/libphonenumber/vendor/autoload.php');

class Libphonenumber
{
	public function format($phoneNumberObject) {
		$phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
		
		try {
			$phoneNumberObj = $phoneNumberUtil->format($phoneNumberObject, \libphonenumber\PhoneNumberFormat::INTERNATIONAL);
		} catch (Exception $e) {
			return ['status'=>FALSE,'message'=>$e->getMessage()];
		}

		return $phoneNumberObj;
	}

	public function parse($phoneNumber,$countryCode) {
		$phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();

		try {
			$phoneNumberObj = $phoneNumberUtil->parse($phoneNumber,$countryCode);
		} catch (Exception $e) {
			return ['status'=>FALSE,'message'=>$e->getMessage()];
		}

		return $phoneNumberObj;
	}

	public function isValidNumber($phoneNumberObj) {
		$phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
		
		try {
			$isValid = $phoneNumberUtil->isValidNumber($phoneNumberObj);
		} catch (Exception $e) {
			return ['status'=>FALSE,'message'=>$e->getMessage()];
		}

		return $isValid;
	}

	public function isPossibleNumber($phoneNumberObj) {
		$phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
		
		try {
			$isValid = $phoneNumberUtil->isPossibleNumber($phoneNumberObj);
		} catch (Exception $e) {
			return ['status'=>FALSE,'message'=>$e->getMessage()];
		}

		return $isValid;
	}

	public function isValidNumberForRegion($phoneNumberObj,$countryCode) {
		$phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
		
		try {
			$isValid = $phoneNumberUtil->isValidNumber($phoneNumberObj,$countryCode);
		} catch (Exception $e) {
			return ['status'=>FALSE,'message'=>$e->getMessage()];
		}
		
		return $isValid;
	}

	public function getRegionCodeForNumber($phoneNumberObj) {
		$phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
		
		try {
			$isValid = $phoneNumberUtil->getRegionCodeForNumber($phoneNumberObj);
		} catch (Exception $e) {
			return ['status'=>FALSE,'message'=>$e->getMessage()];
		}

		return $isValid;
    }
    
    public function getSupportedRegions() {
        $phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        
		try {
			$countryCodes = $phoneNumberUtil->getSupportedRegions();
		} catch (Exception $e) {
			return ['status'=>FALSE,'message'=>$e->getMessage()];
		}

        return $countryCodes;
	}
	
	public function getExampleNumberForType($code) {
		$phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        
		try {
			$message = '';
			$exampleNumber = $phoneNumberUtil->getExampleNumberForType($code,\libphonenumber\PhoneNumberType::MOBILE);
		} catch (Exception $e) {
			return ['status'=>FALSE,'message'=>$e->getMessage()];
		}

        return $exampleNumber;
	}

	public function getCountryCodeForRegion($code) {
		$phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        
		try {
			$message = '';
			$phoneCode = $phoneNumberUtil->getCountryCodeForRegion($code);
		} catch (Exception $e) {
			return ['status'=>FALSE,'message'=>$e->getMessage()];
		}

        return $phoneCode;
	}

	public function getRegionCodesForCountryCode($code) {
		$phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        
		try {
			$message = '';
			$phoneCode = $phoneNumberUtil->getRegionCodesForCountryCode($code);
		} catch (Exception $e) {
			return ['status'=>FALSE,'message'=>$e->getMessage()];
		}

        return $phoneCode;
	}
}