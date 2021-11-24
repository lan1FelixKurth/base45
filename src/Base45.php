<?php

namespace LAN1;

/**
 * Class Base45
 * 
 * @package LAN1\Base45
 * @author Felix Kurth <f.kurth@lan1.de>
 */
class Base45 {

    const CHARSET = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ $%*+-./:";

    /**
     * Returns the Base45 representation of a string
     * 
     * @param string $string
     * @return string
     */
    public static function encode($string){
        $buffer = [];
        $tmp_split = str_split($string, 2);
        foreach($tmp_split as $x){
            if(strlen($x) == 2){
                $buffer[] = (ord($x[0]) << 8) + ord($x[1]);
            }else{
                $buffer[] = ord($x[0]);
            }
        }
        //
        $response = '';
        foreach($buffer as $x){
            $tmp = [];
            do{
                $tmp[] = @self::CHARSET[$x % 45];
                $x = floor( $x / 45);
            }while($x > 45);
            $tmp[] = @self::CHARSET[$x];
            $response .= implode('',$tmp);
        }
        return $response;
    }

    /**
     * Decodes an Base45 String
     * 
     * @param string $base45
     * @return string
     * @throws \Exception
     */
    public static function decode($base45) {
        $buffer = [];
        $tmp_split = str_split($base45);
        foreach($tmp_split as $x){
            $pos = strpos(self::CHARSET, $x);
            if ($pos === false){
                throw new \Exception("Invalid base45 character");
            }else{
                $buffer[] = $pos;
            }
        }

        $response = '';
        foreach(array_chunk($buffer,3) as $chunk){
            $x = 0;
            foreach($chunk as $i => $v){
                $x +=  ( $v * pow(45,$i)) ;
            }
            if($x > 256){
                $q = str_pad(decbin($x), 16, "0", STR_PAD_LEFT);
                $response.= chr(bindec(substr($q, 0,8))).chr(bindec(substr($q, -8,8)));
            }else{
                $response.= chr($x);
            }
        }
        return $response;
    }
}