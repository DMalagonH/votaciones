<?php

namespace AT\votacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class VotacionController extends Controller
{
    /**
     * Accion para el registro de votos
     * 
     * @Route("/votar", name="votacion")
     * @Template("votacionBundle:Votacion:index.html.twig")
     * @author Diego Malagón <diego@altactic.com>
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $security = $this->get('security');
        if(!$security->autentication()){ return $this->redirect($this->generateUrl('login'));}
        if(!$security->autorization($this->getRequest()->get('_route'))){ throw $this->createNotFoundException("Acceso denegado");}

        $estado = $security->getVotacionesResultadosEstado();
        
        $response = array();
        $candidatos = array();
        if(!$this->existeVoto())
        {
			if ($estado['votacionesActivas'] == 1) {
				$candidatos = $this->getCandidatos();
			}
            
                                    
            $response = array(
                'candidatos' => $candidatos,
                'votoRegistrado' => false,
                'estado' => $estado
            );
        }
        else
        {
            $response = array(
                'candidatos' => $candidatos,
                'votoRegistrado' => true,
                'estado' => $estado
            );
        }
        
        return $response;
    }
    
    /**
     * Funcion para confirmar un voto
     * 
     * @Route("/votar/confirmar", name="confirmar_voto")
     * @Template("votacionBundle:Votacion:confirmacion.html.twig")
     * @author Diego Malagón <diego@altactic.com>
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     */
    public function confirmacionVotoAction(Request $request)
    {
        $security = $this->get('security');
        if(!$security->autentication()){ return $this->redirect($this->generateUrl('login'));}
        if(!$security->autorization($this->getRequest()->get('_route'))){ throw $this->createNotFoundException("Acceso denegado");}
        
        if($request->isXmlHttpRequest() && $request->getMethod() == 'POST')
        {
            $cid = $request->get('cid');
            $cno = $request->get('cno');
            
            $em = $this->getDoctrine()->getManager();
            $candidato = $em->getRepository("votacionBundle:TblCandidatos")->findOneBy(array('id'=> $cid, 'candidatoNoTarjeton'=>$cno));
            
            if($candidato)
            {
                //Crear encriptacion de voto
                $sess_user = $this->get('session')->get('sess_user');
                $usuarioId = $sess_user['id'];
        
                $enc_voto = $security->encriptar($usuarioId.$cid.$cno);
                
                //Crear formulario
                $form = $this->createConfirmForm(array(
                    'cid' => $cid,
                    'cno' => $cno,
                    'enc' => $enc_voto,
                ));
                
                return array(
                    'candidato' => $candidato,
                    'form' => $form->createView(), 
                );
            }
        }
        else throw $this->createNotFoundException($this->get('translator')->trans("Acceso denegado"));
            
        return array();
    }
    
    /**
     * Funcion para registrar el voto
     * 
     * @Route("/votar/registrar", name="registrar_voto")
     * @author Diego Malagón <diego@altactic.com>
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param type $token
     */
    public function registrarVotoAction(Request $request)
    {
        $security = $this->get('security');
        if(!$security->autentication()){ return $this->redirect($this->generateUrl('login'));}
        if(!$security->autorization($this->getRequest()->get('_route'))){ throw $this->createNotFoundException("Acceso denegado");}
        
        if($request->getMethod() == 'POST')
        {
            $form = $this->createConfirmForm();
            
            $form->bind($request);
            if ($form->isValid())
            {
                //echo "FORM VALIDO";
                
                $sess_user = $this->get('session')->get('sess_user');        
                $usuarioId = $sess_user['id'];
                $usuarioHash = $sess_user['usuarioHash'];
                        
                $data = $form->getData();
                
                $form_cid = $data['cid'];
                $form_cno = $data['cno'];
                $form_enc_voto = $data['enc'];
                
                $enc_voto = $security->encriptar($usuarioId.$form_cid.$form_cno);
                
                if($enc_voto == $form_enc_voto)
                {
                    //echo "enc VALIDO";
                    if(!$this->existeVoto())
                    {
                        //echo "REGISTRAR VOTO";                        
                        $em = $this->getDoctrine()->getManager();
                        
                        $candidato = $em->getRepository('votacionBundle:TblCandidatos')->findOneBy(array('id' => $form_cid, 'candidatoNoTarjeton'=>$form_cno));
                        $usuario = $em->getRepository('votacionBundle:TblUsuarios')->findOneBy(array('id' => $usuarioId, 'usuarioHash' => $usuarioHash));
                        
                        // Registrar voto
                        $voto = new \AT\votacionBundle\Entity\TblVotaciones();
                        $voto->setVotoValidado(false);
                        $voto->setVotoFecha(new \DateTime());
                        $voto->setUsuarioIdValidador(null);
                        $voto->setCandidato($candidato);
                        $voto->setUsuario($usuario);
                        
                        $em->persist($voto);     
                        $em->flush();
                        
                        // Enviar correo informativo
                        $this->enviarCorreoConfirmacion();
                        
                        $this->get('session')->getFlashBag()->add('alerts', array("type" => "success", "title" => "", "text" => "Su voto se ha enviado correctamente"));
                        return $this->redirect($this->generateUrl('votacion'));
                    }
                    else
                    {
                        $this->get('session')->getFlashBag()->add('alerts', array("type" => "error", "title" => "Voto registrado", "text" => "ya existe un voto registrado"));
                        return $this->redirect($this->generateUrl('votacion'));
                    }
                }
                else
                {
                    $this->get('session')->getFlashBag()->add('alerts', array("type" => "error", "title" => "Error", "text" => "La información enviada es inválida"));
                    return $this->redirect($this->generateUrl('votacion'));
                }
            }
            else
            {
                $this->get('session')->getFlashBag()->add('alerts', array("type" => "error", "title" => "Error", "text" => "La información enviada es inválida"));
                return $this->redirect($this->generateUrl('votacion'));
            }
        }
        else return $this->redirect($this->generateUrl('votacion'));
        
        return new Response();
    }
    
    /**
     * Funcion para crear el formulario de confirmacion
     * 
     * @author Diego Malagón <diego@altactic.com>
     * @return Object formulario
     */
    private function createConfirmForm($formData = false)
    {
        if(!$formData)
        {    
            $formData = array(
                'cid' => null, 
                'cno' => null,        
                'enc' => null,        
            );
        }
        
        $form = $this->createFormBuilder($formData)
           ->add('cid', 'hidden', array('required' => true))
           ->add('cno', 'hidden', array('required' => true))
           ->add('enc', 'hidden', array('required' => true))
           ->getForm(); 
        
        return $form;
    }
    
    /**
     * Funcion que verifica si el usuario de sesion ya tiene un voto registrado
     * 
     * @author Diego Malagón <diego@altactic.com>
     * @return boolean true si ya existe un voto, false si no
     */
    private function existeVoto()
    {
        $sess_user = $this->get('session')->get('sess_user');
        
        $usuarioId = $sess_user['id'];
                
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT COUNT(v.id) c 
                FROM votacionBundle:TblUsuarios u
                INNER JOIN votacionBundle:TblVotaciones v WITH u.id = v.usuario
                WHERE u.id = :usuarioId";
        $query = $em->createQuery($dql);
        $query->setParameter('usuarioId', $usuarioId);
        $result = $query->getResult();
        
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
     * Funcion que obtiene los candidatos
     * 
     * @author Diego Malagón <diego@altactic.com>
     * @return array
     */
    private function getCandidatos()
    {
        $em = $this->getDoctrine()->getManager();
        
        $dql = "SELECT c.id, c.candidatoNombre, c.candidatoNoTarjeton, c.candidatoImagen, c.candidatoPartido
                FROM votacionBundle:TblCandidatos c
                ORDER BY c.candidatoNoTarjeton";
        $query = $em->createQuery($dql);
        $result = $query->getResult();
        
        return $result;
    }
    
    /**
     * Funcion para enviar correo informativo
     * 
     * @author Diego Malagón <diego@altactic.com>
     */
    private function enviarCorreoConfirmacion()
    {
        $mail = $this->get('mail');  
        
        $sess_user = $this->get('session')->get('sess_user');
        
        $email = $sess_user['usuarioEmail'];       
        
        
        $link = $this->getRequest()->getSchemeAndHttpHost().$this->generateUrl('resultado_votaciones');
        $body = '
            <p>
            Los votos entran en un proceso de validación vía telefónica o correo electrónico por parte de nuestro personal, 
            una vez el voto sea validado se verá reflejado en los <a href="'.$link.'">resultados</a>.
            </p>
            <br/>
            Muchas gracias por participar.
        ';        
        
        $mail->sendMail($email, 'Voto registrado', array(
            'title' => 'Su voto se ha registrado correctamente',
            'body' => $body
        ));
    }
}
?>
