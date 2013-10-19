<?php

namespace AT\votacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class LoginController extends Controller
{
    
    /**
     * Accion para login de la aplicacion
     * 
     * @Route("/login", name="login")
     * @Template("votacionBundle:Login:login.html.twig")
     * @author Diego Malagón <diego@altactic.com>
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Resonse
     */
    public function loginAction(Request $request)
    {
//        $security = $this->get('security');
        $validate = $this->get('validate');
        
//        echo $security->encriptar('1234');
        
        return array();
    }
}
?>
