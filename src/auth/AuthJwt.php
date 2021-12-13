<?php
namespace vaultke\foundation\auth;

use Yii;
use Firebase\JWT\JWT;

trait AuthJwt
{
    protected static $ciphering = "AES-128-CTR";

    /**
     * Store JWT token header items.
     * @var array
     */
    protected static $decodedToken;

    /**
     * Getter for secret key that's used for generation of JWT
     * @return string secret key used to generate JWT
     */
    protected static function getSecretKey()
    {
        return isset(Yii::$app->params['secretKey']) ? Yii::$app->params['secretKey'] : sha1(md5('53cr3tk3y'));
    }

    /**
     * Getter for expIn token that's used for generation of JWT
     * @return integer time to add expIn token used to generate JWT
     */
    protected static function getExpireIn()
    {
        return isset(Yii::$app->params['expiresIn']) ? strtotime(Yii::$app->params['expiresIn']) : 0;
    }

    /**
     * Getter for "header" array that's used for generation of JWT
     * @return array JWT Header Token param, see http://jwt.io/ for details
     */
    protected static function getHeaderToken()
    {
        return [];
    }
 
    /**
     * Logins user by given JWT encoded string. If string is correctly decoded
     * - array (token) must contain 'jti' param - the id of existing user
     * @param  string $accessToken access token to decode
     * @return mixed|null          User model or null if there's no user
     * @throws \yii\web\ForbiddenHttpException if anything went wrong
     */    
    public static function findIdentityByAccessToken($token, $type = null)
    {
        try {
            $decoded = JWT::decode($token, static::publicKey(), [static::getAlgo()]);
        } catch (\Exception $e) {
            return false;
        }
        static::$decodedToken = (array)$decoded;
        if (!isset(static::$decodedToken['jti'])) {
            return false;
        }else{
            return new static(static::$decodedToken['usr']);
        }
        return false;
    }


    /**
     * Getter for encryption algorytm used in JWT generation and decoding
     * Override this method to set up other algorytm.
     * @return string needed algorytm
     */
    public static function getAlgo()
    {
        return 'RS512';
    }

    /**
     * Returns some 'id' to encode to token. By default is current model id.
     * If you override this method, be sure that findByJTI is updated too
     * @return integer any unique integer identifier of user
     */
    public function getJTI()
    {
        return $this->getAuthKey(); 
    }

    /**
     * Encodes model data to create custom JWT with model.id set in it
     * @return string encoded JWT
     */
    public function getJWT($claims=[])
    {
        // Collect all the data
        $currentTime = time();

        // Merge token with presets not to miss any params in custom
        // configuration
        $token = array_merge([
            'iss' => $claims['req'],
            'aud' => $claims['req'],
            'iat' => $currentTime,
            'nbf' => $currentTime,
            'exp' => static::getExpireIn(),
            'usr' => $claims['usr'],
        ], static::getHeaderToken());

        // Set up id
        $token['jti'] = static::encrypt($this->getJTI());

        return JWT::encode($token, static::privateKey(), static::getAlgo());
    }
    public static function encrypt($data,$options = FALSE) 
    {
        $iv_length = openssl_cipher_iv_length(static::$ciphering);
        $encryption_iv = 'tH15ia53cR3tKey!';
        $encryption_key = openssl_digest(php_uname(), 'MD5', TRUE);
        return openssl_encrypt($data, static::$ciphering, $encryption_key, $options, $encryption_iv);
    }
    
    public static function decrypt($data, $options = FALSE) 
    {
        $decryption_iv = 'tH15ia53cR3tKey!';
        $decryption_key = openssl_digest(php_uname(), 'MD5', TRUE);
        return openssl_decrypt ($data, static::$ciphering, $decryption_key, $options, $decryption_iv);
    }
    public static function privateKey(){
        if (isset($_ENV['privateKey'])) {
            $key = openssl_pkey_get_private(file_get_contents($_ENV['privateKey']));
        }else{
            $key = openssl_pkey_get_private(file_get_contents(Yii::getAlias('@app/vendor/vaultke/foundation/src/auth/keys/private.key')));
        }
        return $key;
    }

    public static function publicKey(){
        if (isset($_ENV['publicKey'])) {
            $key = openssl_pkey_get_public(file_get_contents($_ENV['publicKey']));
        }else{
            $key = openssl_pkey_get_public(file_get_contents(Yii::getAlias('@app/vendor/vaultke/foundation/src/auth/keys/public.key')));
        }
        return $key;
    }
}