<?php 
namespace machapisho\api;
use Yii;
/*
*  @author Ananda Douglas
*/
class Tokenize{

    public static function passKey($length = 8, $add_dashes = false, $available_sets = 'luds')
    {
        $sets = array();
        if(strpos($available_sets, 'l') !== false)
        $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        if(strpos($available_sets, 'u') !== false)
        $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        if(strpos($available_sets, 'd') !== false)
        $sets[] = '0123456789';
        if(strpos($available_sets, 's') !== false)
        $sets[] = '!@#$%&*?';

        $all = '';
        $password = '';
        foreach($sets as $set)
        {
        $password .= $set[array_rand(str_split($set))];
        $all .= $set;
        }

        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++)
        $password .= $all[array_rand($all)];

        $password = str_shuffle($password);

        if(!$add_dashes)
        return $password;

        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while(strlen($password) > $dash_len)
        {
        $dash_str .= substr($password, 0, $dash_len) . '-';
        $password = substr($password, $dash_len);
        }
        $dash_str .= $password;
        return $dash_str;
    }
    public static function keyGen($numerical=FALSE) 
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 12; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $s= uniqid($randomString,true);
        $hex = substr($s, 0, 16);
        $dec = $s[13] . substr($s, 18); // skip the dot
        $unique = base_convert($hex, 16, 36) . base_convert($dec, 10, 36);
        if($numerical==1){$string=ltrim(crc32($unique.date('dmyhis')),'-');}else{$string=$unique;}
        
        if($string == null || $string == ''){
        return 'RAND'.time();
        }else{
        return $string;
        } 
    }
    public static function tagGen() 
    {
        
    }
    public function toAlpha($data){
        $alphabet =   array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
        $alpha_flip = array_flip($alphabet);
        if($data <= 25){
          return $alphabet[$data];
        }
        elseif($data > 25){
          $dividend = ($data + 1);
          $alpha = '';
          $modulo;
          while ($dividend > 0){
            $modulo = ($dividend - 1) % 26;
            $alpha = $alphabet[$modulo] . $alpha;
            $dividend = floor((($dividend - $modulo) / 26));
          } 
          return $alpha;
        }
    }
    public function toNum($data) {
        $alphabet = array( 'a', 'b', 'c', 'd', 'e',
                           'f', 'g', 'h', 'i', 'j',
                           'k', 'l', 'm', 'n', 'o',
                           'p', 'q', 'r', 's', 't',
                           'u', 'v', 'w', 'x', 'y',
                           'z'
                           );
        $alpha_flip = array_flip($alphabet);
        $return_value = -1;
        $length = strlen($data);
        for ($i = 0; $i < $length; $i++) {
            $return_value +=
                ($alpha_flip[$data[$i]] + 1) * pow(26, ($length - $i - 1));
        }
        return $return_value;
    }

}
