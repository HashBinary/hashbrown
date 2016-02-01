<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

/**
 * Encode in Base32 based on RFC 4648.
 * Requires 20% more space than base64  
 * Great for case-insensitive filesystems like Windows and URL's  (except for = char which can be excluded using the pad option for urls)
 *
 * @package default
 * @author Bryan Ruiz
 **/
class Base32 {

   private static $map = array(
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', //  7
        'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', // 15
        'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', // 23
        'Y', 'Z', '2', '3', '4', '5', '6', '7', // 31
        '='  // padding char
    );
    
   private static $flippedMap = array(
        'A'=>'0', 'B'=>'1', 'C'=>'2', 'D'=>'3', 'E'=>'4', 'F'=>'5', 'G'=>'6', 'H'=>'7',
        'I'=>'8', 'J'=>'9', 'K'=>'10', 'L'=>'11', 'M'=>'12', 'N'=>'13', 'O'=>'14', 'P'=>'15',
        'Q'=>'16', 'R'=>'17', 'S'=>'18', 'T'=>'19', 'U'=>'20', 'V'=>'21', 'W'=>'22', 'X'=>'23',
        'Y'=>'24', 'Z'=>'25', '2'=>'26', '3'=>'27', '4'=>'28', '5'=>'29', '6'=>'30', '7'=>'31'
    );
    
    /**
     *    Use padding false when encoding for urls
     *
     * @return base32 encoded string
     * @author Bryan Ruiz
     **/
    public static function encode($input, $padding = true) {
        if(empty($input)) return "";
        $input = str_split($input);
        $binaryString = "";
        for($i = 0; $i < count($input); $i++) {
            $binaryString .= str_pad(base_convert(ord($input[$i]), 10, 2), 8, '0', STR_PAD_LEFT);
        }
        $fiveBitBinaryArray = str_split($binaryString, 5);
        $base32 = "";
        $i=0;
        while($i < count($fiveBitBinaryArray)) {    
            $base32 .= self::$map[base_convert(str_pad($fiveBitBinaryArray[$i], 5,'0'), 2, 10)];
            $i++;
        }
        if($padding && ($x = strlen($binaryString) % 40) != 0) {
            if($x == 8) $base32 .= str_repeat(self::$map[32], 6);
            else if($x == 16) $base32 .= str_repeat(self::$map[32], 4);
            else if($x == 24) $base32 .= str_repeat(self::$map[32], 3);
            else if($x == 32) $base32 .= self::$map[32];
        }
        return $base32;
    }
    
    public static function decode($input) {
        if(empty($input)) return;
        $paddingCharCount = substr_count($input, self::$map[32]);
        $allowedValues = array(6,4,3,1,0);
        if(!in_array($paddingCharCount, $allowedValues)) return false;
        for($i=0; $i<4; $i++){ 
            if($paddingCharCount == $allowedValues[$i] && 
                substr($input, -($allowedValues[$i])) != str_repeat(self::$map[32], $allowedValues[$i])) return false;
        }
        $input = str_replace('=','', $input);
        $input = str_split($input);
        $binaryString = "";
        for($i=0; $i < count($input); $i = $i+8) {
            $x = "";
            if(!in_array($input[$i], self::$map)) return false;
            for($j=0; $j < 8; $j++) {
                $x .= str_pad(base_convert(@self::$flippedMap[@$input[$i + $j]], 10, 2), 5, '0', STR_PAD_LEFT);
            }
            $eightBits = str_split($x, 8);
            for($z = 0; $z < count($eightBits); $z++) {
                $binaryString .= ( ($y = chr(base_convert($eightBits[$z], 2, 10))) || ord($y) == 48 ) ? $y:"";
            }
        }
        return $binaryString;
    }
}


interface OTPInterface
{
    /**
     * @param int $input
     *
     * @return string Return the OTP at the specified input
     */
    public function at($input);

    /**
     * Verify that the OTP is valid with the specified input.
     *
     * @param string   $otp
     * @param int      $input
     * @param int|null $window
     *
     * @return bool
     */
    public function verify($otp, $input, $window = null);

    /**
     * @return string The secret of the OTP
     */
    public function getSecret();

    /**
     * @param string $secret
     *
     * @return $this
     */
    public function setSecret($secret);

    /**
     * @return string The label of the OTP
     */
    public function getLabel();

    /**
     * @param string $label
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function setLabel($label);

    /**
     * @return string The issuer
     */
    public function getIssuer();

    /**
     * @param string $issuer
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function setIssuer($issuer);

    /**
     * @return bool If true, the issuer will be added as a parameter in the provisioning URI
     */
    public function isIssuerIncludedAsParameter();

    /**
     * @param bool $issuer_included_as_parameter
     *
     * @return $this
     */
    public function setIssuerIncludedAsParameter($issuer_included_as_parameter);

    /**
     * @return int Number of digits in the OTP
     */
    public function getDigits();

    /**
     * @param int $digits
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function setDigits($digits);

    /**
     * @return string Digest algorithm used to calculate the OTP. Possible values are 'md5', 'sha1', 'sha256' and 'sha512'
     */
    public function getDigest();

    /**
     * @param string $digest
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function setDigest($digest);

    /**
     * @return string The URL of an image associated to the provisioning URI
     */
    public function getImage();

    /**
     * @param string $image
     *
     * @return $this
     */
    public function setImage($image);

    /**
     * @param string $parameter
     *
     * @return null|mixed
     */
    public function getParameter($parameter);

    /**
     * @return array
     */
    public function getParameters();

    /**
     * @param string $parameter
     * @param mixed  $value
     *
     * @return $this
     */
    public function setParameter($parameter, $value);

    /**
     * @param bool $google_compatible If true (default), will produce provisioning URI compatible with Google Authenticator. Only applicable if algorithm="sha1", period=30 and digits=6.
     *
     * @return string Get the provisioning URI
     */
    public function getProvisioningUri($google_compatible = true);
}

interface TOTPInterface extends OTPInterface
{
    /**
     * @return string Return the TOTP at the current time
     */
    public function now();

    /**
     * @return int Get the interval of time for OTP generation (a non-null positive integer, in second)
     */
    public function getInterval();

    /**
     * @param int $interval
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function setInterval($interval);
}
 
abstract class OTP implements OTPInterface
{
    /**
     * @var array
     */
    private $parameters = array();

    /**
     * @var string|null
     */
    private $issuer = null;

    /**
     * @var string|null
     */
    private $label = null;

    /**
     * @var bool
     */
    private $issuer_included_as_parameter = false;

    public function __construct()
    {
        $this->setDigest('sha1')
            ->setDigits(6);
    }

    /**
     * @param int $input
     *
     * @return string The OTP at the specified input
     */
    protected function generateOTP($input)
    {
        $hash = hash_hmac($this->getDigest(), $this->intToByteString($input), $this->getDecodedSecret());
        $hmac = array();
        foreach (str_split($hash, 2) as $hex) {
            $hmac[] = hexdec($hex);
        }
        $offset = $hmac[count($hmac) - 1] & 0xF;
        $code = ($hmac[$offset + 0] & 0x7F) << 24 |
            ($hmac[$offset + 1] & 0xFF) << 16 |
            ($hmac[$offset + 2] & 0xFF) << 8 |
            ($hmac[$offset + 3] & 0xFF);

        $otp = $code % pow(10, $this->getDigits());

        return str_pad((string) $otp, $this->getDigits(), '0', STR_PAD_LEFT);
    }

    /**
     * @return bool Return true is it must be included as parameter, else false
     */
    protected function issuerAsParameter()
    {
        if (null !== $this->getIssuer() && $this->isIssuerIncludedAsParameter() === true) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $options
     * @param bool  $google_compatible
     */
    protected function filterOptions(array &$options, $google_compatible)
    {
        if (true === $google_compatible) {
            foreach (array('algorithm' => 'sha1', 'period' => 30, 'digits' => 6) as $key => $default) {
                if (isset($options[$key]) && $default === $options[$key]) {
                    unset($options[$key]);
                }
            }
        }

        ksort($options);
    }

    /**
     * @param string $type
     * @param array  $options
     * @param bool   $google_compatible
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    protected function generateURI($type, array $options = array(), $google_compatible)
    {
        if (!is_set($this->getLabel())) {
            throw new \InvalidArgumentException('No label defined.');
        }

        $options = array_merge($options, $this->getParameters());
        if ($this->issuerAsParameter()) {
            $options['issuer'] = $this->getIssuer();
        }

        $this->filterOptions($options, $google_compatible);

        $params = str_replace(
            array('+', '%7E'),
            array('%20', '~'),
            http_build_query($options)
        );

        return sprintf(
            'otpauth://%s/%s?%s',
            $type,
            rawurlencode((null !== $this->getIssuer() ? $this->getIssuer().':' : '').$this->getLabel()),
            $params
        );
    }

    /**
     * {@inheritdoc}
     */
    public function at($input)
    {
        return $this->generateOTP($input);
    }

    /**
     * {@inheritdoc}
     */
    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    /**
     * {@inheritdoc}
     */
    public function setSecret($secret)
    {
        return $this->setParameter('secret', $secret);
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * {@inheritdoc}
     */
    public function setLabel($label)
    {
        if ($this->hasSemicolon($label)) {
            throw new \InvalidArgumentException('Label must not contain a semi-colon.');
        }

        $this->label = $label;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIssuer()
    {
        return $this->issuer;
    }

    /**
     * {@inheritdoc}
     */
    public function setIssuer($issuer)
    {
        if ($this->hasSemicolon($issuer)) {
            throw new \InvalidArgumentException('Issuer must not contain a semi-colon.');
        }

        $this->issuer = $issuer;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isIssuerIncludedAsParameter()
    {
        return $this->issuer_included_as_parameter;
    }

    /**
     * {@inheritdoc}
     */
    public function setIssuerIncludedAsParameter($issuer_included_as_parameter)
    {
        $this->issuer_included_as_parameter = $issuer_included_as_parameter;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDigits()
    {
        return $this->getParameter('digits');
    }

    /**
     * {@inheritdoc}
     */
    public function setDigits($digits)
    {
        if (!is_numeric($digits) || $digits < 1) {
            throw new \InvalidArgumentException('Digits must be at least 1.');
        }

        return $this->setParameter('digits', $digits);
    }

    /**
     * {@inheritdoc}
     */
    public function getDigest()
    {
        return $this->getParameter('algorithm');
    }

    /**
     * {@inheritdoc}
     */
    public function setDigest($digest)
    {
        if (!in_array($digest, hash_algos())) {
            throw new \InvalidArgumentException("'$digest' digest is not supported.");
        }

        return $this->setParameter('algorithm', $digest);
    }

    /**
     * {@inheritdoc}
     */
    public function setImage($image)
    {
        return $this->setParameter('image', $image);
    }

    /**
     * {@inheritdoc}
     */
    public function getImage()
    {
        return $this->getParameter('image');
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter($parameter)
    {
        if (array_key_exists($parameter, $this->parameters)) {
            return $this->parameters[$parameter];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setParameter($parameter, $value)
    {
        $this->parameters[$parameter] = $value;

        return $this;
    }

    private function hasSemicolon($value)
    {
        $semicolons = array(':', '%3A', '%3a');
        foreach ($semicolons as $semicolon) {
            if (false !== strpos($value, $semicolon)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    private function getDecodedSecret()
    {
        $secret = Base32::decode($this->getSecret());

        return $secret;
    }

    /**
     * @param int $int
     *
     * @return string
     */
    private function intToByteString($int)
    {
        $result = array();
        while (0 !== $int) {
            $result[] = chr($int & 0xFF);
            $int >>= 8;
        }

        return str_pad(implode(array_reverse($result)), 8, "\000", STR_PAD_LEFT);
    }

    protected function compareOTP($safe, $user)
    {
        if (function_exists('hash_equals')) {
            return hash_equals($safe, $user);
        }
        $safeLen = strlen($safe);
        $userLen = strlen($user);

        if ($userLen !== $safeLen) {
            return false;
        }

        $result = 0;

        for ($i = 0; $i < $userLen; $i++) {
            $result |= (ord($safe[$i]) ^ ord($user[$i]));
        }

        return $result === 0;
    }
}



class TOTP extends OTP implements TOTPInterface
{
    public function __construct()
    {
        parent::__construct();
        $this->setInterval(30);
    }

    /**
     * {@inheritdoc}
     */
    public function setInterval($interval)
    {
        if (!is_int($interval) || $interval < 1) {
            throw new \InvalidArgumentException('Interval must be at least 1.');
        }

        $this->setParameter('period', $interval);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getInterval()
    {
        return $this->getParameter('period');
    }

    /**
     * {@inheritdoc}
     */
    public function at($timestamp)
    {
        return $this->generateOTP($this->timecode($timestamp));
    }

    /**
     * {@inheritdoc}
     */
    public function now()
    {
        return $this->at(time());
    }

    /**
     * {@inheritdoc}
     */
    public function verify($otp, $timestamp = null, $window = null)
    {
        if (null === $timestamp) {
            $timestamp = time();
        }

        if (!is_int($window)) {
            return $this->compareOTP($this->at($timestamp), $otp);
        }
        $window = abs($window);

        for ($i = -$window; $i <= $window; ++$i) {
            if ($this->compareOTP($this->at($i * $this->getInterval() + $timestamp), $otp)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getProvisioningUri($google_compatible = true)
    {
        $params = array();
        if (true !== $google_compatible || 30 !== $this->getInterval()) {
            $params = array('period' => $this->getInterval());
        }

        return $this->generateURI('totp', $params, $google_compatible);
    }

    /**
     * @param int $timestamp
     *
     * @return int
     */
    private function timecode($timestamp)
    {
        return (int) ((((int) $timestamp * 1000) / ($this->getInterval() * 1000)));
    }
}
