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
        
        $form_valido = false;
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if ($form->isValid())
            {
                $data = $form->getData();


                if($this->validateForm($data))
                {
                    if(!$this->existeUsuario($data['email'], $data['doc']))
                    {
                        $enc_pass = $security->encriptar($data['pass']);

                        $usuario = new \AT\votacionBundle\Entity\TblUsuarios();

                        $usuario->setUsuarioActivado(false);
                        $usuario->setUsuarioRol('user');
                        $usuario->setPermisoNuevosUsuarios(false);

                        $usuario->setUsuarioNombre($data['nombre']);
                        $usuario->setUsuarioApellido($data['apellido']);
                        $usuario->setUsuarioEmail($data['email']);
                        $usuario->setUsuarioDocumento($data['doc']);
                        $usuario->setUsuarioProfesion($data['profesion']);
                        $usuario->setUsuarioTelefono($data['telefono']);
                        $usuario->setUsuarioCelular($data['celular']);

                        $usuario->setUsuarioPassword($enc_pass);
                        $usuario->setUsuarioHash(uniqid('user', true));

                        $em = $this->getDoctrine()->getManager();
                        $em->persist($usuario);     
                        $em->flush();

//                        $security->debug($data);
                    }
                }
                  
            }
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
           ->add('celular', 'number', array('required' => false))
           ->add('telefono', 'number', array('required' => false))
           ->add('profesion', 'text', array('required' => false))
           ->add('pass', 'password', array('required' => true))
           ->add('pass_conf', 'password', array('required' => true))
           ->getForm(); 
        
        return $form;
    }
    
    private function validateForm($data)
    {
        $validate = $this->get('validate');
        $val = array();
        
        //Validar datos
        $val['nombre'] = $validate->validateTextOnly($data['nombre'], true);
        $val['apellido'] = $validate->validateTextOnly($data['apellido'], true);
        $val['email'] = $validate->validateEmail($data['email'], true);
        $val['doc'] = $validate->validateInteger($data['doc'], true);
        $val['celular'] = $validate->validateInteger($data['celular'], false);
        $val['telefono'] = $validate->validateInteger($data['telefono'], false);
        $val['profesion'] = $validate->validateInteger($data['profesion'], false);
        
        // Validar password
        $val['pass'] = false;
        if($data['pass'] == $data['pass_conf'])
        {
            if($validate->validatePassword($data['pass']))
            {
                $val['pass'] = true;
            }
        }
        
//        $this->get('security')->debug($val);

        
        foreach($val as $v)
        {
            if($v == false)
            {
                return false;
            }
        }
        return true;    
}

    private function existeUsuario($email, $documento)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT COUNT(u.id) c 
                FROM votacionBundle:TblUsuarios u
                WHERE u.usuarioEmail = :email OR u.usuarioDocumento = :documento
                ";
        $query = $em->createQuery($dql);
        $query->setParameter('email', $email);
        $query->setParameter('documento', $documento);
        $result = $query->getResult();
        
//        $this->get('security')->debug($result);
        
        if($result[0]['c'] == 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

}
?>
