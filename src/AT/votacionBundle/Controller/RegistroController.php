<?php
namespace AT\votacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class RegistroController extends Controller
{
    /**
     * Accion para el registro de votantes
     * 
     * @Route("/", name="registro")
     * @Template("votacionBundle:Registro:index.html.twig")
     * @author Diego Malagón <diego@altactic.com>
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Resonse
     */
    public function indexAction(Request $request)
    {
        $security = $this->get('security');
        
        $form = $this->createRegistroForm();
        
        $acceso_denegado = true;
        if($request->getMethod() == 'POST')
        {
            
        }
        
        return array(
            'form' => $form->createView(), 
        );
    }
    
    private function createRegistroForm()
    {
        $formData = array(
            'nombre' => null, 
            'apellido' => null,
            'email' => null,
            'doc' => null,
            'celular' => null,
            'telefono' => null,
            'profesion' => null,
            'pass' => null,
            'pass_conf' => null,            
        );
        
        $form = $this->createFormBuilder($formData)
           ->add('nombre', 'text', array('required' => true))
           ->add('apellido', 'text', array('required' => true))
           ->add('email', 'email', array('required' => true))
           ->add('doc', 'number', array('required' => true))
           ->add('celular', 'number', array('required' => true))
           ->add('telefono', 'number', array('required' => true))
           ->add('profesion', 'text', array('required' => false))
           ->add('pass', 'password', array('required' => true))
           ->add('pass_conf', 'password', array('required' => true))
           ->getForm(); 
        
        return $form;
    }
}
?>
