<?php
namespace vaultke\foundation;

/**
 * @author Ananda Douglas <douglasdaggs@gmail.com>
 * @since 1.0
 */
class Helpers {
    public function baseUrl()
    {
        return \yii\helpers\Url::base(true);
    }

    public static function cipherKey($numerical=false){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 15; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $s= uniqid($randomString,true);
        $hex = bin2hex(substr($s, 0, 5));
        $dec = substr($s, -6) + date('Ym');
        $unique = base_convert($hex, 16, 36) . base_convert($dec, 10, 36);

        if($numerical){
            $string=ltrim(crc32($unique.time()),'-');
        }else{
            $string = $unique;
            $i=0;
            $strlen = strlen($string);
            while($i<$strlen){
                $tmp=$string[$i];
                if(rand() % 2 ==0) $tmp=strtoupper($tmp);
                else $tmp=strtolower($tmp);
                if ($i == rand(0, $strlen)) {
                    $tmp = ($i % 2 == 0) ? '_' : '-';
                }
                $string[$i]=$tmp;
                $i++;
            }
        }
        return ($string);
    }
}