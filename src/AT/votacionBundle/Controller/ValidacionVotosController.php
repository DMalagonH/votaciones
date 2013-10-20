<?php
namespace AT\votacionBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ValidacionVotosController extends Controller
{
    /**
     * Accion para el registro de votantes
     * 
     * @author Camilo Quijano <camilo@altactic.com>
     * @version 1
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Render
     * @Template("votacionBundle:ValidacionVotos:index.html.twig")
     * @Route("/validaciones", name="validar_votos")
     */
    public function indexAction(Request $request)
    {
        $security = $this->get('security');
        
        $pagLetras = $this->getPaginacionAbecedario();
        
        if($request->getMethod() == 'POST')
        {
            $lselected = $request->request->get('letter');
            /**
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
                        
                        return $this->redirect($this->generateUrl('inactivo'));
                    }
                    else
                    {
                        $this->get('session')->getFlashBag()->add('alerts', array("type" => "error", "title" => "", "text" => "Ya existe un usuario con esta información"));
                    }
                }
                else
                {
                    $this->get('session')->getFlashBag()->add('alerts', array("type" => "error", "title" => "Datos inválidos:", "text" => "Verifique la información suministrada"));
                }                  
            }
            else
            {
                $this->get('session')->getFlashBag()->add('alerts', array("type" => "error", "title" => "Datos inválidos:", "text" => "Verifique la información suministrada"));
            }
        
        */
        }
        else {
            if ($pagLetras){
                foreach($pagLetras as $key=>$pl) {
                    $lselected = $key;
                    break;
                }
            }
            else {
                $lselected = 'all';
            }
        }
        
        $listadoNoValidado = $this->getListadoVotacionSinValidad($lselected);
        
        return array(
            //'form' => $form->createView(),
            'listado' => $listadoNoValidado,
            'pagLetras' => $pagLetras,
            'LetterAct' => $lselected
            
        );
    }
    
    /**
     * Funcion que tra Listado de votaciones sin validad filtrando por la letra que ingresa como parametro en la primera letra del apellido
     * si viene como parametro all, no aplica filtro de letra inicial del apellido
     * 
     * @author Camilo Quijano <camilo@altactic.com>
     * @version 1
     * @return Array Con listado de registros que coinciden con la busqueda
     */
    private function getListadoVotacionSinValidad($letra)
    {
        $em = $this->getDoctrine()->getManager();
        $where ='';
        if ($letra != 'all') {
            $where = "AND tu.usuarioApellido LIKE '".$letra."%'";
        }
        
        /**
         * @var String Consulta SQL qu trae los votos que no han sido validados y filtrados por el apellido, por la primera letra
         * SELECT * FROM tbl_votaciones tv
         * INNER JOIN tbl_usuarios tu ON tv.usuario_id = tu.id
         * WHERE tv.voto_validado = 0 AND usuario_apellido LIKE 'T%';
         */
        $dql = "SELECT tv.id AS votoId, tu.id, tu.usuarioNombre, tu.usuarioApellido, tu.usuarioEmail, tu.usuarioDocumento, tu.usuarioTelefono, tu.usuarioCelular
                FROM votacionBundle:TblVotaciones tv
                INNER JOIN votacionBundle:TblUsuarios tu 
                    WITH tu.id = tv.usuario
                WHERE tv.votoValidado = 0 ".$where."
                ";
        $query = $em->createQuery($dql);
        return $query->getResult();
    }
    
    /**
     * Funcion que tra las letras por las que empiezan los registros que estan sin validar
     * returnando un array con key (letra), y valor (Cantidad de repeticiones) de la primera letra del apellido
     * 
     * @author Camilo Quijano <camilo@altactic.com>
     * @version 1
     * @return Array Con letras que tienen registros
     */
    private function getPaginacionAbecedario()
    {
        $em = $this->getDoctrine()->getManager();
        /**
         * @var String Consulta SQL que retorna las letras por las que empiezan los apellidos
         * de los usuarios que han votado, pero que no han validado el voto
         * SELECT substring(tu.usuario_apellido, 1, 1) ini, COUNT(tu.id)
         * FROM tbl_votaciones tv
         * INNER JOIN tbl_usuarios tu ON tu.id = tv.usuario_id AND tv.voto_validado = 0
         * GROUP BY ini;
         */
        $dql = "SELECT SUBSTRING(tu.usuarioApellido, 1, 1) AS letra, COUNT(tu.id) totalPersonas
                FROM votacionBundle:TblVotaciones tv
                INNER JOIN votacionBundle:TblUsuarios tu 
                    WITH tu.id = tv.usuario AND tv.votoValidado = 0
                GROUP BY letra";
        $query = $em->createQuery($dql);
        $entities=$query->getResult();
        $arrayLetras = Array();
        foreach ($entities as $letra) {
            $arrayLetras[strtoupper($letra['letra'])] = $letra['totalPersonas'];
        }
        return $arrayLetras;
    }
}
?>