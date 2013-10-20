<?php

namespace AT\votacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
        $security = $this->get('security');
        if($security->autentication()){ return $this->redirect($this->generateUrl('votacion'));}
        
        
        $form = $this->createLoginForm();
        
        $acceso_denegado = true;
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if ($form->isValid())
            {
                $data = $form->getData();
                
                if($this->validateForm($data))
                {
                    $usuario = $security->login($data['user'], $data['pass']);
                    if($usuario !== false)
                    {
                        $acceso_denegado = false;
                        
                        return $this->redirect($this->generateUrl('votacion'));
                    }
                }
                
            }
            
            if($acceso_denegado)
            {
                $this->get('session')->getFlashBag()->add('alerts', array("type" => "error", "title" => "Datos inválidos", "text" => "Verifique su usuario o contraseña"));
                
            }
        }
        
        return array(
            'form' => $form->createView(), 
        );
    }
    
    /**
     * Funcion para crear el formulario de registro
     * 
     * @author Diego Malagón <diego@altactic.com>
     * @return Object Formulario
     */
    private function createLoginForm()
    {
        $formData = array(
            'user' => null, 
            'pass' => null,        
        );
        
        $form = $this->createFormBuilder($formData)
           ->add('user', 'email', array('required' => true))
           ->add('pass', 'password', array('required' => true))
           ->getForm(); 
        
        return $form;
    }
    
    /**
     * Funcion para validar el formulario de login
     * 
     * @author Diego Malagón <diego@altactic.com>
     * @param array $data arreglo con los campos del formulario
     * @return boolean
     */
    private function validateForm($data)
    {
        $validate = $this->get('validate');
        $val = array();
        
        $val['user'] = $validate->validateEmail($data['user'], true);
        
        foreach($val as $v)
        {
            if($v == false)
            {
                return false;
            }
        }
        return true;    
    }
    
    /**
     * Accion para eliminar la session 
     * 
     * @Route("/logout", name="logout")
     * @author Diego Malagón <diego@altactic.com>
     */
    public function logoutAction()
    {
        $security = $this->get('security');
        if(!$security->autentication()){ return $this->redirect($this->generateUrl('login'));}
        
        $security->logout();
        
        return $this->redirect($this->generateUrl('login'));
    }
}
?>
