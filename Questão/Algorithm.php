<?php

class Algorithm {

    public const LIMIT_MIN = 0;
    public const LIMIT_MAX = 300;
    public const TAMANHO_POPULCAO = 8;
    public const TOTAL_APTOS = 4;

    public static function functionfit($x, $expression = '') {
        if ($expression) {
            $var = null;
            @eval('$var ='. str_replace('{x}', $x, $expression).';');
            return $var;
        }
    }

    public static function ceratePopulation() {
        $list = [];
        for ($i=0; $i < self::TAMANHO_POPULCAO; $i++) { 
            $list[$i] = self::cerateElement();
        }

        return $list;
    }

    public static function fit($lista_elementos, $expression = '') {
        $fits = [];
        for ($i=0; $i < count($lista_elementos); $i++) { 
            $fits[$lista_elementos[$i]] = self::functionfit($lista_elementos[$i], $expression);
        }

        sort($fits); // ordena pelo menor valor do fit($value)

        return array_slice($fits, 0, self::TOTAL_APTOS);
    }

    public static function mutation($list_elements, $mutation_rate) {
        
        $random_number = random_int(0, 100) * 0.1;
        $result = [];

        $count = count($list_elements);
        $index = 1;
        $total = $count-1;

        while ($count > 0) {
            $register = true;
            $binary = $list_elements[self::issetIndex($index, $total)];

            for ($i = 1; $i < strlen($binary); $i++) {

				if ($mutation_rate <=> $random_number) {

					$str1 = substr($binary ,0, ($i - 1));
					$str2 = $binary[($i - 1)];
					$str3 = substr($binary, $i, strlen($binary));

					if ($str2 === '0') {
						$str2 = '1';
					} else if ($str2 === '1') {
						$str2 = '0';
					}

					$result[] = $str1 . $str2 . $str3;
					$register = false;
				}
			}

            if ($register === true) {
				$result[] = $binary;
			}
            
            $index++;
            $count--;
        }

        return $result;
    }

    public static function twoPointCrossover($list_elements) {
        $result = [];
        $list = self::getPoits(self::createBinary($list_elements));

        while (!empty($list)) {
            $result[] = $list[0] . $list[4] . $list[2];
            $result[] = $list[3] . $list[1] . $list[5];

            for ($j = 0; $j < 6; $j++) array_shift($list);
        }
        return $result;        
    }

    public static function fuorPointCrossover($list_elements) {
        $result = [];
        $list = self::getPoits(self::createBinary($list_elements), 4);
        while (!empty($list)) {

            $result[] = $list[0] . $list[6] . $list[2] . $list[8] . $list[4];
            $result[] = $list[5] . $list[1] . $list[7] . $list[3] . $list[9];

            for ($j = 0; $j < 10; $j++) array_shift($list);
        }
        return $result;        
    }





    // ----------------------------------------------------------------------------

    public static function cerateElement() {
        return random_int(self::LIMIT_MIN, self::LIMIT_MAX);
    }

    public static function createBinary($lista_elementos) {
        $result = [];
        foreach ($lista_elementos as $value) {
            $result[] = str_pad((string)decbin($value), 8, "0", STR_PAD_LEFT);
        }
        return $result;
    }

    public static function getPoits($lista_binary, $cross_salt = 2) {
        $result = [];
        foreach ($lista_binary as $value) {
            array_push($result, ...self::separatePoints($value, $cross_salt));
        }
        return $result;
    }

    public static function separatePoints($binary, $cross_salt) {   
        if ($cross_salt === 2) {
            return [
                substr($binary, 0, 2), 
                substr($binary, 2, 4), 
                substr($binary, 6, 2)
            ];
        }
        if ($cross_salt === 4) {
            return [
                substr($binary, 0, 1), 
                substr($binary, 1, 2), 
                substr($binary, 3, 2), 
                substr($binary, 5, 2), 
                substr($binary, 7, 1)
            ];
        }
    }

    public static function convertToDecimal($list_elements) {
        $result = [];
        foreach ($list_elements as $value) {
            $result[] = bindec($value);
        }
        return $result;
    }

    public static function issetIndex($index, $total) {
        if ($index <= $total) {
            return $index;
        }
        return 0;
    }

}