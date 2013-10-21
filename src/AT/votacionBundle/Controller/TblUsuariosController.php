<?php

namespace AT\votacionBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
//use AT\votacionBundle\Entity\TblUsuarios;
//use AT\votacionBundle\Form\TblUsuariosType;

/**
 * Controlador de usuarios
 * @package votacionBundle\Controller
 * @Route("/usuarios")
 */
class TblUsuariosController extends Controller
{
    /**
     * Listado de usuarios
     *
     * @author Camilo Quijano <camilo@altactic.com>
     * @version 1
     * @return Render
     * @Template("votacionBundle:TblUsuarios:index.html.twig")
     * @Route("/", name="usuarios")
     * @Method("GET")
     */
    public function indexAction()
    {
		$security = $this->get('security');
        if(!$security->autentication()){ return $this->redirect($this->generateUrl('login'));}
        if(!$security->autorization($this->getRequest()->get('_route'))){ throw $this->createNotFoundException("Acceso denegado");}

		// Validar si tiene permiso a acceso de crud de usuarios
        $session = $this->get('session')->get('sess_user');
        $permisoCrear = $session['permisoNuevosUsuarios'];
        if (!$permisoCrear) { throw $this->createNotFoundException('Not Found'); }
			
		$em = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('votacionBundle:TblUsuarios')->findAll();
		return array('entities' => $entities );

    }
    
    /**
     * Crear nuevo usuario
     * 
     * @author Camilo Quijano <camilo@altactic.com>
     * @version 1
     * @Template("votacionBundle:TblUsuarios:new.html.twig")
     * @Route("/new", name="usuarios_new")
     * @Method({"GET", "POST"})
     * @param \Symfony\Component\HttpFoundation\Request $request Form de usuario Nuevo
     * @return Render
     */
    public function newAction(Request $request)
    {
        $security = $this->get('security');
        if(!$security->autentication()){ return $this->redirect($this->generateUrl('login'));}
        if(!$security->autorization($this->getRequest()->get('_route'))){ throw $this->createNotFoundException("Acceso denegado");}

		// Validar si tiene permiso a acceso de crud de usuarios
        $session = $this->get('session')->get('sess_user');
        $permisoCrear = $session['permisoNuevosUsuarios'];
        if (!$permisoCrear) { throw $this->createNotFoundException('Not Found'); }

		$form = $this->createUsuarioForm();
		
		if ($request->getMethod() == 'POST')
		{
			$form->bind($request);
			if ($form->isValid())
			{
				$data = $form->getData();
				if ($this->validateForm($data)) {
					
					if(!$this->existeUsuario($data['email'], $data['doc']))
					{
						$enc_pass = $security->encriptar($data['pass']);
						
						$hash = uniqid('u', true);

						$usuario = new \AT\votacionBundle\Entity\TblUsuarios();

						$usuario->setUsuarioActivado($data['usuarioActivado']);
						$usuario->setUsuarioRol('admin');
						$usuario->setPermisoNuevosUsuarios($data['permisoNuevosUsuarios']);

						$usuario->setUsuarioNombre($data['nombre']);
						$usuario->setUsuarioApellido($data['apellido']);
						$usuario->setUsuarioEmail($data['email']);
						$usuario->setUsuarioDocumento($data['doc']);
						$usuario->setUsuarioTipoDocumento('Cédula de ciudadanía');
						$usuario->setUsuarioProfesion($data['profesion']);
						$usuario->setUsuarioTelefono($data['telefono']);
						$usuario->setUsuarioCelular($data['celular']);
								
						$usuario->setUsuarioPassword($enc_pass);
						$usuario->setUsuarioHash($hash);

						$em = $this->getDoctrine()->getManager();
						$em->persist($usuario);     
						$em->flush();

						$this->get('session')->getFlashBag()->add('alerts', array("type" => "success", "title" => "", "text" => "Usuario creado correctamente"));
						return $this->redirect($this->generateUrl('usuarios_show', array('id' => $usuario->getId())));
					}
					else {
						$this->get('session')->getFlashBag()->add('alerts', array("type" => "error", "title" => "", "text" => "Ya existe un usuario con esta información"));
					}
				}
				else {
					$this->get('session')->getFlashBag()->add('alerts', array("type" => "error", "title" => "*Datos inválidos:", "text" => "Verifique la información suministrada"));
				}
			}
			else {
				$this->get('session')->getFlashBag()->add('alerts', array("type" => "error", "title" => "Datos inválidos:", "text" => "Verifique la información suministrada"));
			}
		}
        

        return array(
            'form'   => $form->createView(),
        );
    }
    
    /**
     * Editar un usuario
     * @author Camilo Quijano <camilo@altactic.com>
     * @version 1
     * @Template("votacionBundle:TblUsuarios:edit.html.twig")
     * @Route("/{id}/edit", name="usuarios_edit")
     * @Method({"GET", "POST"})
     * @param \Symfony\Component\HttpFoundation\Request $request Form de usuario a editar
     * @return Render
     */
    public function editAction(Request $request, $id)
    {
		$security = $this->get('security');
        if(!$security->autentication()){ return $this->redirect($this->generateUrl('login'));}
        if(!$security->autorization($this->getRequest()->get('_route'))){ throw $this->createNotFoundException("Acceso denegado");}

        // Validar si tiene permiso a acceso de crud de usuarios
        $session = $this->get('session')->get('sess_user');
        $permisoCrear = $session['permisoNuevosUsuarios'];
        if (!$permisoCrear) { throw $this->createNotFoundException('Not Found'); }
        
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('votacionBundle:TblUsuarios')->find($id);

        if (!$usuario) {
            throw $this->createNotFoundException('Unable to find TblUsuarios entity.');
        }

        $editForm = $this->editUsuarioForm($usuario);
        //$deleteForm = $this->createDeleteForm($id);
        if ($request->getMethod() == 'POST')
        {
            //$editForm->handleRequest($request);
            $editForm->bind($request);
            if ($editForm->isValid()) {
                
                $data = $editForm->getData();
                if ($this->validateFormEdit($data)) {

					$usuario->setUsuarioActivado($data['usuarioActivado']);
					$usuario->setUsuarioRol($data['rol']);
					$usuario->setPermisoNuevosUsuarios($data['permisoNuevosUsuarios']);
					$usuario->setUsuarioNombre($data['nombre']);
					$usuario->setUsuarioApellido($data['apellido']);
					//$usuario->setUsuarioEmail($data['email']);
					//$usuario->setUsuarioDocumento($data['doc']);
					//$usuario->setUsuarioTipoDocumento('Cédula de ciudadanía');
					$usuario->setUsuarioProfesion($data['profesion']);
					$usuario->setUsuarioTelefono($data['telefono']);
					$usuario->setUsuarioCelular($data['celular']);
					//$usuario->setUsuarioPassword($enc_pass);
					//$usuario->setUsuarioHash($hash);
					$em->persist($usuario);
					$em->flush();
					
					$this->get('session')->getFlashBag()->add('alerts', array("type" => "success", "title" => "", "text" => "Usuario editado correctamente"));
					return $this->redirect($this->generateUrl('usuarios_show', array('id' => $id)));
                }    
            }
            $this->get('session')->getFlashBag()->add('alerts', array("type" => "error", "title" => "Datos inválidos:", "text" => "Verifique la información suministrada"));
        }

        return array(
            'entity'      => $usuario,
            'edit_form'   => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        );
    }
       
    /**
     * Ver detalles de un usuario
     *
     * @author Camilo Quijano <camilo@altactic.com>
     * @version 1
     * @Template("votacionBundle:TblUsuarios:show.html.twig")
     * @Route("/{id}", name="usuarios_show")
     * @Method("GET")
     * @param Int $id Id del usuario
     * @return Render
     */
    public function showAction($id)
    {
		$security = $this->get('security');
        if(!$security->autentication()){ return $this->redirect($this->generateUrl('login'));}
        if(!$security->autorization($this->getRequest()->get('_route'))){ throw $this->createNotFoundException("Acceso denegado");}

        // Validar si tiene permiso a acceso de crud de usuarios
        $session = $this->get('session')->get('sess_user');
        $permisoCrear = $session['permisoNuevosUsuarios'];
        if (!$permisoCrear) { throw $this->createNotFoundException('Not Found'); }
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('votacionBundle:TblUsuarios')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TblUsuarios entity.');
        }

        //$deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            //'delete_form' => $deleteForm->createView(),
        );
    }
  
    /**
     * Funcion que verifica si existe un usuario con el email o documento
     * 
     * @author Diego Malagón <diego@altactic.com>
     * @param string $email
     * @param integer $documento
     * @return boolean retorna true si ya exite el usuario, false si no
     */
    private function existeUsuario($email, $documento)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT COUNT(u.id) c 
                FROM votacionBundle:TblUsuarios u
                WHERE u.usuarioEmail = :email OR u.usuarioDocumento = :documento";
        $query = $em->createQuery($dql);
        $query->setParameter('email', $email);
        $query->setParameter('documento', $documento);
        $result = $query->getResult();
        
        //$this->get('security')->debug($result);
        
        if($result[0]['c'] == 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    
    /**
     * Funcion para crear el formulario de crear nuevo usuario
     * 
     * @author Diego Malagón <diego@altactic.com>
     * @author Camilo Quijano <camilo@altactic.com>
     * @version 2 Optimizacion para funcionamiento de crear usuario tomado de nuevo registro
     * @return Object Formulario
     */
    private function createUsuarioForm()
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
            'usuarioActivado' => null,
            'permisoNuevosUsuarios' => null,
        );
        
        $form = $this->createFormBuilder($formData)
           ->add('nombre', 'text', array('required' => true))
           ->add('apellido', 'text', array('required' => true))
           ->add('email', 'email', array('required' => true))
           ->add('doc', 'text', array('required' => true))
           ->add('celular', 'text', array('required' => true))
           ->add('telefono', 'text', array('required' => false))
           ->add('profesion', 'text', array('required' => false))
           ->add('pass', 'password', array('required' => true))
           ->add('pass_conf', 'password', array('required' => true))
           ->add('permisoNuevosUsuarios', 'checkbox', array('required' => false))
           ->add('usuarioActivado', 'checkbox', array('required' => false))
           ->getForm();
        return $form;
    }
    
    /**
     * Funcion para crear el formulario de editar usuario
     * 
     * @author Diego Malagón <diego@altactic.com>
     * @author Camilo Quijano <camilo@altactic.com>
     * @version 2 Optimizacion para funcionamiento de crear usuario tomado de nuevo registro
     * @return Object Formulario
     */
    private function editUsuarioForm($entity)
    {
        $formData = array(
            'nombre' => null,
            'apellido' => null,
            //'email' => null,
            //'doc' => null,
            'celular' => null,
            'telefono' => null,
            'profesion' => null,
            //'pass' => null,
            //'pass_conf' => null,
            'rol' => '',
            'usuarioActivado' => null,
            'permisoNuevosUsuarios' => null,
        );
        
        $pNU = ($entity->getPermisoNuevosUsuarios()) ? Array('checked' => 'checked') : Array();
        $pNA = ($entity->getUsuarioActivado()) ? Array('checked' => 'checked') : Array();
        $ArrayRoles = Array('user' => 'Usuario', 'admin' => 'Administrador');
        $form = $this->createFormBuilder($formData)
           ->add('nombre', 'text', array('required' => true, 'attr' => array('value' => $entity->getUsuarioNombre())))
           ->add('apellido', 'text', array('required' => true, 'attr' => array('value' => $entity->getUsuarioApellido())))
           //->add('email', 'email', array('required' => true, 'attr' => array('value' => $entity->getUsuarioEmail())))
           //->add('doc', 'text', array('required' => true, 'attr' => array('value' => $entity->getUsuarioDocumento())))
           ->add('celular', 'text', array('required' => true, 'attr' => array('value' => $entity->getUsuarioCelular())))
           ->add('telefono', 'text', array('required' => false, 'attr' => array('value' => $entity->getUsuarioTelefono())))
           ->add('profesion', 'text', array('required' => false, 'attr' => array('value' => $entity->getUsuarioProfesion())))
           //->add('pass', 'password', array('required' => true))
           //->add('pass_conf', 'password', array('required' => true))
           ->add('permisoNuevosUsuarios', 'checkbox', array('required' => false, 'attr' => $pNU))
           ->add('usuarioActivado', 'checkbox', array('required' => false, 'attr' => $pNA))
           ->add('rol', 'choice', array('choices'  => $ArrayRoles, 'preferred_choices' => array($entity->getUsuarioRol())))
           ->getForm();
        
        return $form;
    }
    
    /**
     * Funcion para validar los datos del formulario Nuevo usuario
     * 
     * @author Diego Malagón <diego@altactic.com>
     * @author Camilo Quijano <camilo@altactic.com>
     * @version 2 Optimizacion para funcionamiento de crear usuario tomado de nuevo registro
     * @param Array $data array con los campos del formulario
     * @return boolean
     */
    private function validateForm($data)
    {
        $validate = $this->get('validate');
        $val = array();
        
        //Validar datos
        $val['nombre'] = $validate->validateTextOnly($data['nombre'], true);
        $val['apellido'] = $validate->validateTextOnly($data['apellido'], true);
        $val['email'] = $validate->validateEmail($data['email'], true);
        $val['doc'] = $validate->validateInteger($data['doc'], true);
        $val['celular'] = $validate->validateInteger($data['celular'], true);
        $val['telefono'] = $validate->validateInteger($data['telefono'], false);
        $val['profesion'] = $validate->validateTextOnly($data['profesion'], false);
        
        // Validar password
        $val['pass'] = false;
        if($data['pass'] == $data['pass_conf'])
        {
            if($validate->validatePassword($data['pass']))
            {
                $val['pass'] = true;
            }
        }
        
        //$this->get('security')->debug($val);

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
     * Funcion para validar los datos del formulario de usuario a editar
     * 
     * @author Diego Malagón <diego@altactic.com>
     * @author Camilo Quijano <camilo@altactic.com>
     * @version 2 Optimizacion para funcionamiento de crear usuario tomado de nuevo registro
     * @param Array $data array con los campos del formulario
     * @return boolean
     */
    private function validateFormEdit($data)
    {
        $validate = $this->get('validate');
        $val = array();
        
        //Validar datos
        $val['nombre'] = $validate->validateTextOnly($data['nombre'], true);
        $val['apellido'] = $validate->validateTextOnly($data['apellido'], true);
        //$val['email'] = $validate->validateEmail($data['email'], true);
        //$val['doc'] = $validate->validateInteger($data['doc'], true);
        $val['celular'] = $validate->validateInteger($data['celular'], true);
        $val['telefono'] = $validate->validateInteger($data['telefono'], false);
        $val['profesion'] = $validate->validateTextOnly($data['profesion'], false);
        $val['rol'] = ($data['rol'] == 'admin' or $data['rol'] == 'user') ? true : false;
        
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
    public function deleteAction(Request $request, $id)
    {
        
        * Deletes a TblUsuarios entity.
        *
        * @Route("/{id}", name="usuarios_delete")
        * @Method("DELETE")
        
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('votacionBundle:TblUsuarios')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TblUsuarios entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('usuarios'));
    }     
    
    private function createDeleteForm($id)
    {
        * Creates a form to delete a TblUsuarios entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('usuarios_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array ('class' => 'btn btn-mini' )))
            ->getForm()
        ;
    }
     */
    
}
