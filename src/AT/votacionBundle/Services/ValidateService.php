<?php

namespace AT\votacionBundle\Services;

class ValidateService
{
    public function validateEmail($email, $require=true)
    {
        $return = false;
        if(!empty($email))
        {
            if(filter_var($email, FILTER_VALIDATE_EMAIL)) $return = true;        
        }
        elseif(!$require) $return = true;
        
        return $return;
    }
    
    /**
     * Funcion para la validacion del nivel de seguridad de contraseñas
     * 
     * @author Diego Malagón <diego@altactic.com>
     * @param type $pass
     * @return boolean
     */
    function validatePassword($pass)
    {
        $return = false;
        $nivel = 0;
                
        if(strlen($pass) >= 6)
        {
            $nivel += 1;
            if(preg_match('`[a-z]`',$pass) && preg_match('`[A-Z]`',$pass))
            {
                $nivel += 1;
            }
            if(preg_match('`[0-9]`',$pass) && (preg_match('`[a-z]`',$pass) || preg_match('`[A-Z]`',$pass)))
            {
                $nivel += 1;
            }
            if(preg_match('`[\`,´,~,!,@,#,$,&,%, ,^,(,),+,=,{,},[,\],|,-,_,/,*,$,=,°,¡,?,¿,\,,\.,;,:,\".\',<,>]`',$pass) && (preg_match('`[a-z]`',$pass) || preg_match('`[A-Z]`',$pass)))
            {
                $nivel += 1;
            }
            if(strlen($pass) >= 8)
            {
                $nivel += 1;
            }
            if(strlen($pass) >= 10)
            {
                $nivel += 1;
            }
            
            if($nivel >= 4)
            {
                $return = true;
            }
        }
        return $return;
    }
    
    
    public function validateDominio($dominio, $require=true)
    {
        $return = false;
        if(!empty($dominio))
        {
            if(preg_match('/^[A-Za-z0-9-.]+\.[A-Za-z]{2,4}$/', $dominio)) $return = true;            
        }
        elseif(!$require) $return = true;
        
        return $return;
    }
    
    public function validateIp($ip, $require=true)
    {
        $return = false;
        if(!empty($ip))
        {
            if(filter_var($ip, FILTER_VALIDATE_IP)) $return = true;        
        }
        elseif(!$require) $return = true;
        
        return $return;
    }
    
    public function validateUrl($url, $require=true)
    {
        $return = false;
        if(!empty($url))
        {
            if(preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url)) $return = true;
        }
        elseif(!$require) $return = true;
        
        return $return;
    }
    
    public function validateDate($ObjDateTime, $tipo=false, $require=true)
    {
        $return = false;
        
        $ArrDateTime = $this->ObjectToArray($ObjDateTime);
        $DateTime = $ArrDateTime['date'];
        
        $date = explode(' ', $DateTime);
        $date = $date[0];
        
        if(!empty($date))
        {
            $hoy = date('Y-m-d');

            $exp_date = explode('-', $date);

            if(checkdate($exp_date[1], $exp_date[2], $exp_date[0]))
            {
                if(!$tipo)
                {
                    $return = true;
                }
                else
                {
                    switch ($tipo)
                    {
                        case 'futura':
                        {
                            if(strtotime($date) >= strtotime($hoy))
                            {
                                $return = true;
                            }
                            break;
                        }
                        case 'pasada':
                        {
                            if(strtotime($date) <= strtotime($hoy))
                            {
                                $return = true;
                            }
                            break;
                        }
                    }
                }
            }
        }
        elseif(!$require) $return = true;
        return $return;
    }

    public function validateDateFormat($strDate, $format='dd/mm/yyyy', $require=true)
    {
        $return = false;
        if(!empty($strDate))
        {
            if($format == 'dd/mm/yyyy')
                if(preg_match('/^[0-9]{1,2}+\/[0-9]{1,2}+\/[0-9]{4}$/', $strDate)) $return = true;
            else
                if(preg_match('/[0-9]/', $strDate)) $return = true;
        }
        elseif(!$require) $return = true;
        
        return $return;
    }
    
    public function validateTextOnly($txt, $require=true)
    {
        $return = false;
        if(!empty($txt))
        {
            if(preg_match('/[a-zA-Z]/', $txt)) $return = true;
        }
        elseif(!$require) $return = true;
        
        return $return;
    }
    
    public function validateAlfaNum($txt, $require=true)
    {
        $return = false;
        if(!empty($txt))
        {
            if(preg_match('/[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9]/', $txt)) $return = true;
        }
        elseif(!$require) $return = true;
        
        return $return;
    }
    
    public function validateMonth($m, $require=true)
    {
        $return = false;
        if($m !== null)
        {
            if(is_numeric($m))
            {
                if(preg_match('/^[0-9]{1,2}$/', $m))
                {
                    if($m >= 1 && $m <= 12)
                    {
                        $return = true;
                    }
                }
            }
        }
        elseif(!$require) $return = true;
        
        return $return;
    }
    
    public function validateYear($y, $require=true)
    {
        $return = false;
        if($y !== null)
        {
            if(is_numeric($y))
            {
                if(preg_match('/^[0-9]{4}$/', $y))
                {
                    if($y >= 1900)
                    {
                        $return = true;                    
                    }
                }
            }
        }
        elseif(!$require) $return = true;
        
        return $return;
    }
    
    public function validateInteger($n, $require=true)
    {
        $return = false;
        if($n)
        {
            if(is_numeric($n))
            {
                if(preg_match('/^[[:digit:]]+$/', $n))
                {
                    $return = true;                    
                }
            }
        }
        elseif(!$require) $return = true;
        
        return $return;
    }
    
    public function ObjectToArray($Objeto) {
        if (is_object ( $Objeto ))
        $Objeto = get_object_vars ( $Objeto );
        if (is_array ( $Objeto ))
        foreach ( $Objeto as $key => $value )
        $Objeto [$key] =  $this->ObjectToArray( $Objeto [$key] );
        return $Objeto;
    }
}
?>
