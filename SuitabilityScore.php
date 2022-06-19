<?php
class SuitabilityScore {

    private $addresses = [];
    private $drivers = [];
    private $commonFactors = [];
    private $scores = [];
    public $result = [];
    
    function __construct($addresses, $drivers) {
        $this->addresses = $addresses;
        $this->drivers = $drivers;
        $this->calculateDriverShipmentScore();
    }

    private function hasCommonFactors($num1, $num2)
    {
        $key = $num1 .'-'.$num2;
        $key2 = $num2 .'-'.$num1;
        if(array_key_exists($key, $this->commonFactors)) {
            return $this->commonFactors[$key];
        }
        if(array_key_exists($key2,$this->commonFactors)) {
            return $this->commonFactors[$key2];
        }
        $value = gmp_gcd($num1, $num2) > 1;
        $this->commonFactors[$key] = $value;
        return $value;
    }

    private function calculateDriverShipmentScore()
    {

        foreach ($this->drivers as $keyD => $driver) {
            $driverVowels = preg_match_all('/[AEIOU]/i', $driver);
            $driverConsonants = preg_match_all('/[BCDFGJKLMNPQSTVXZHRWY]/i', $driver);
            foreach ($this->addresses as $keyA => $address) {
                $score = 0;
                if(strlen($address) & 1) {
                    $score = $driverConsonants;
                } else {
                    $score = $driverVowels * 1.5;
                }

                if($this->hasCommonFactors(strlen($address), strlen($driver))) {
                    $score *= 1.5;
                }
                if(!array_key_exists($driver, $this->scores)) {
                    $this->scores[$driver] = [];
                }
                $this->scores[$driver][$address] = $score;
            }
        }
    }

    function getMaxScore($score = 0, $usedDrivers = [], $usedAddresses = [], $result = []) {
        $max = $score;
        foreach ($this->scores as $driver => $driveScore) {
            if(array_key_exists($driver, $usedDrivers)) {
                continue;
            }
            foreach ($driveScore as $address => $value) {
                if(array_key_exists($address, $usedAddresses)) {
                    continue;
                }
                $newUsedDriver = $usedDrivers;
                $newUsedDriver[$driver] = 1;
                $newUsedAddress = $usedAddresses;
                $newUsedAddress[$address] = 1;
                $newResult = $result;
                $newResult[$driver] = $address;
                $current = $this->getMaxScore($score + $value, $newUsedDriver, $newUsedAddress, $newResult);
                if($current > $max) {
                    $max = $current;
                    $this->result = $newResult;
                }
            }
        }
        return $max;
    }
}
?>