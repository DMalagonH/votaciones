<?php

namespace AT\votacionBundle\Services;

class SecurityService
{
    protected $doctrine;
    protected $session;
    protected $em;
        
    function __construct($doctrine, $session) 
    {
        $this->doctrine = $doctrine;
        $this->session = $session;
        $this->em = $doctrine->getManager();
    }
    
   /**
     * Funcion para la encriptacion de contraseñas
     * 
     * @author Diego Malagón <diego@altactic.com>
     * @param string $txt cadena a encriptar
     * @return string cadena encriptada
     */
    public function encriptar($txt)
    {
        $secret = '1e4a565a79ff96059da1fd1075f2b34bd';
        
        $len = strlen($txt);
        $mlen = round($len/2);
        
        $m1 = substr($txt, 0, $mlen);
        $m2 = substr($txt, $mlen);
        
        $return = sha1(md5($m1.$secret).sha1($m2.$secret));
        
        return $return;
    }
    
    /**
     * Funcion que genera una contraseña aleatoria
     * 
     * @author Diego Malagón <diego@altactic.com>
     * @return string Contraseña generada sin encriptar
     */
    public function generarPassword()
    {
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890@ñÑ";
        $pass = "";
        for($i=0;$i<10;$i++) 
        {
            $pass .= substr($str,rand(0,62),1);
        }
        return $pass;
    }
    
    
}
?>
