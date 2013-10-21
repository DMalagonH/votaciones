<?php

namespace AT\votacionBundle\Services;

class SecurityService
{
    protected $doctrine;
    protected $session;
    protected $em;
    protected $container;
        
    function __construct($container) 
    {
		$this->container = $container;
		$this->doctrine = $container->get('doctrine');
		$this->session = $container->get('session');
		$this->em = $this->doctrine->getManager();
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
    
    /**
     * Funcion que crea un token
     * 
     * @author Diego Malagón <diego@altactic.com>
     * @param string $email email de usuario
     * @param string $hash hash de usuario
     * @param string $pre prefijo para el hash dinamico
     * @return string token para la uri
     */
    public function addToken($email, $hash, $pre = '')
    {
        $link_hash = uniqid($pre, true);        
        $enc_token = $this->encriptar($email.'/'.$hash.'/'.$link_hash);
        $strtoken = base64_encode($email.'/'.$hash.'/'.$link_hash);
        
        $token = new \AT\votacionBundle\Entity\Token();
        $token->setEmail($email);
        $token->setHash($hash);
        $token->setToken($enc_token);
        $this->em->persist($token);     
        $this->em->flush();
        
        return $strtoken;
    }
    
    /**
     * Funcion para validar un token
     * 
     * Verifica que el token sea valido y lo elimina
     * 
     * @author Diego Malagón <diego@altactic.com>
     * @param string $token_uri token recibido por la uri
     * @param boolean $delete indica si se debe eliminar o no el token una vez validado
     * @return boolean
     */
    public function validateToken($token_uri, $delete = true)
    {
        $valid = false;
        
        if($this->validateBase64($token_uri))
        {        
            $dec_token = base64_decode($token_uri, true);

            $explode = explode('/', $dec_token);
            $email = (isset($explode[0])) ? $explode[0] : false;
            $hash = (isset($explode[1])) ? $explode[1] : false;
            $token = (isset($explode[2])) ? $explode[2] : false;    

            if($email && $hash && $token)
            {        
                $enc_token = $this->encriptar($dec_token);

                $dql = "SELECT COUNT(t.id) c FROM votacionBundle:Token t
                        WHERE t.email = :email AND t.hash = :hash AND t.token = :token";
                $query = $this->em->createQuery($dql);
                $query->setParameter('email', $email);
                $query->setParameter('hash', $hash);
                $query->setParameter('token', $enc_token);
                $result = $query->getResult();

                if(isset($result[0]['c']))
                {
                    if($result[0]['c'] == 1)
                    {
                        $valid = true;

                        //Eliminar token
                        if($delete)
                        {
                            $this->deleteToken($email, $hash);
                        }
                    }
                }
            }
        }
        return $valid;
    }
    
    /**
     * Funcion para eliminar un token
     * 
     * @author Diego Malagón <diego@altactic.com>
     * @param string $email email de usuario
     * @param string $hash hash de usuario
     */
    public function deleteToken($email, $hash)
    {
        $dql = "DELETE FROM votacionBundle:Token t
                WHERE t.email = :email AND t.hash = :hash";
        $query = $this->em->createQuery($dql);
        $query->setParameter('email', $email);
        $query->setParameter('hash', $hash);
        $query->getResult();
    }
    
    /**
     * Funcion para validar base64
     * 
     * @param string $code64 cadena codificada en base64
     * @return boolean
     */
    public function validateBase64($code64)
    {
        $validate = false;
        $dec = base64_decode($code64, true);
        
        $enc = base64_encode($dec);
        
        $val_deco = base64_decode($enc, true);
        
        if($val_deco !== false)
        {
            $validate = true;
        }
        return $validate;
    }
        
    /**
     * Funcion para imprimir la estructura de una variable
     * 
     * @author Diego Malagón <diego@altactic.com>
     * @param type $var variable a depurar
     */
    public function debug($var)
    {
        if(is_object($var) || is_array($var))
        {
            echo "<pre>";
            print_r($var);
            echo "</pre>";
        }
        else
        {
            var_dump($var);
            echo "<br/>";
        }
    }
    
    /**
     * Funcion para iniciar session
     * 
     * @author Diego Malagón <diego@altactic.com>
     * @param string $user email de usuario
     * @param string $pass contraseña del usuario
     */
    public function login($user, $pass)
    {
        $dql = "SELECT 
                    u.id, 
                    u.usuarioEmail, 
                    u.usuarioNombre, 
                    u.usuarioApellido, 
                    u.usuarioTipoDocumento,
                    u.usuarioDocumento,
                    u.usuarioRol,
                    u.permisoNuevosUsuarios,
                    u.usuarioHash
               FROM votacionBundle:TblUsuarios u
               WHERE
                    u.usuarioEmail = :email
                    AND u.usuarioPassword = :pass
                    AND u.usuarioActivado = :activado";
        $query = $this->em->createQuery($dql);
        $query->setParameter('email', $user);
        $query->setParameter('pass', $this->encriptar($pass));
        $query->setParameter('activado', true);
        $query->setMaxResults(1);
        $result = $query->getResult();
        
        if(count($result) == 1)
        {
            $usuario = $result[0];
            
            $this->session->set('sess_user',$usuario);
            
            return $usuario;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Funcion para eliminar la session
     * 
     * @author Diego Malagón <diego@altactic.com>
     */
    public function logout()
    {
        $sess_user = $this->session->get('sess_user');
        
        $this->deleteToken($sess_user['usuarioEmail'], $sess_user['usuarioHash']);
        
        $this->session->set('sess_user',null);
    }
    
    /**
     * Funcion que verifica si el usuario esta autenticado
     * 
     * @author Diego Malagón <diego@altactic.com>
     * @return boolean
     */
    public function autentication()
    {
        $return = false;
        $sess_usuario = $this->session->get('sess_user');
        
        if(isset($sess_usuario['usuarioEmail']) && isset($sess_usuario['usuarioHash']) && isset($sess_usuario['usuarioRol']))
        {
            $return = true;
        }
        
        return  $return;
    }
    
    /**
     * Funcion para verificar si el usuario tiene permiso a una seccion de la aplicacion
     * 
     * @author Diego Malagón <diego@altactic.com>
     * @param string $route nombre de la ruta en symfony
     * @return boolean
     */
    public function autorization($route)
    {
        $return = false;
        $sess_usuario = $this->session->get('sess_user');
        
        if(isset($sess_usuario['usuarioRol']))
        {
            $rol = $sess_usuario['usuarioRol'];
            
            if($rol == 'user')
            {
                $permisos = array(
                    'candidatos',
                    'detalle_candidato',
                    'resultado_votaciones',
                    // Rutas de votacion
                    'votacion',
                    'confirmar_voto',
                    'registrar_voto'
                );
            }
            elseif('admin')
            {
                $permisos = array(
                    'candidatos',
                    'detalle_candidato',
                    'resultado_votaciones',
                    // Rutas de votacion
                    'votacion',
                    'confirmar_voto',
                    'registrar_voto',
                    //ruta para validacion de votos
                    'validar_votos',
                    'validar_votos_index',
                    'validados_votos_cancelar',
                    //rutas para crud de usuarios
                    'usuarios',
                    'usuarios_new',
                    'usuarios_edit',
                    'usuarios_show',
                    
                );
            }
            
            if(in_array($route, $permisos))
            {
                $return = true;
            }
        }
        
        return $return;
    }

	/**
	 * Funcion para retornar parametros de fechas de inicio y fin de votaciones y resultados
	 *
	 * @author Camilo Quijano <camilo@altactic.com>
	 * @version 1
	 */
    public function getParameters()
    {
		$parameters['votaciones_fecha_inicio'] = $this->container->getParameter('votaciones_fecha_inicio');
		$parameters['votaciones_fecha_fin'] = $this->container->getParameter('votaciones_fecha_fin');
		$parameters['resultados_fecha_inicio'] = $this->container->getParameter('resultados_fecha_inicio');
		$parameters['resultados_fecha_fin'] = $this->container->getParameter('resultados_fecha_fin');

		return $parameters;
	}

	public function getVotacionesResultadosEstado()
	{
		$parameters = $this->getParameters();

		$now = new \DateTime();
		$fActualU = $now->format('U');
		$estado['fechaActual'] = $fActualU;
		$estado['votacionesActivas'] = 0;
		$estado['resultadosActivos'] = 0;

		$parameters['votaciones_fecha_inicio'] = ($parameters['votaciones_fecha_inicio']) ? $parameters['votaciones_fecha_inicio'] : $fActualU -10000;
		$parameters['votaciones_fecha_fin'] = ($parameters['votaciones_fecha_fin']) ? $parameters['votaciones_fecha_fin'] : $fActualU +10000;
		$parameters['resultados_fecha_inicio'] = ($parameters['resultados_fecha_inicio']) ? $parameters['resultados_fecha_inicio'] : $fActualU -10000;
		$parameters['resultados_fecha_fin'] = ($parameters['resultados_fecha_fin']) ? $parameters['resultados_fecha_fin'] : $fActualU +10000;
		
		if (($parameters['votaciones_fecha_inicio'] < $fActualU) && ($parameters['votaciones_fecha_fin'] > $fActualU))
		{
			$estado['votacionesActivas'] = 1;
		}
		
		if (($parameters['resultados_fecha_inicio'] < $fActualU) && ($parameters['resultados_fecha_fin'] > $fActualU))
		{
			$estado['resultadosActivos'] = 1;
		}

		return $estado;
	}

	
}
?>
