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
     * @Route("/validaciones", name="validar_votos_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $security = $this->get('security');
        $pagLetras = $this->getPaginacionAbecedario(0);
        
        if($request->getMethod() == 'POST') 
        {
            $lselected = $request->request->get('letter');
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
        
        $listadoNoValidado = $this->getListadoVotacion($lselected, 0);
        
        return array(
            'listado' => $listadoNoValidado,
            'pagLetras' => $pagLetras,
            'LetterAct' => $lselected,
            'orign' => 'novalidados'
        );
    }
    
    /**
     * @author Camilo Quijano <camilo@altactic.com>
     * @version 1
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Render
     * @Template("votacionBundle:ValidacionVotos:index.html.twig")
     * @Route("/validados", name="validados_votos_cancelar")
     * @Method({"GET", "POST"})
     */
    public function validadosAction(Request $request)
    {
        $security = $this->get('security');
        $pagLetras = $this->getPaginacionAbecedario(1);
        
        if($request->getMethod() == 'POST') 
        {
            $lselected = $request->request->get('letter');
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
        $listadoNoValidado = $this->getListadoVotacion($lselected, 1);
        
        return array(
            'listado' => $listadoNoValidado,
            'pagLetras' => $pagLetras,
            'LetterAct' => $lselected,
            'orign' => 'validados'
        );
    }
    
    /**
     * Funcion POST que cambia el estado del voto
     * 
     * @author Camilo Quijano <camilo@altactic.com>
     * @version 1
     * @param \Symfony\Component\HttpFoundation\Request $request (act=>(val, NoVal), id=> Id del voto)
     * @return Response 1=>Exitoso 0=>Error
     * @Route("/validar", name="validar_votos")
     * @Method("POST")
     */
    public function validarVoto(Request $request)
    {
        if($request->isXmlHttpRequest() && $request->getMethod() == 'POST'){
            
            $em = $this->getDoctrine()->getManager();
            $votoId = $request->request->get('id');
            $action = $request->request->get('act');
            
            $NoError = 0;
            new Response();
            if ($action == 'Val' || $action == 'NoVal') {

                $AuxVV = ($action == 'Val') ? 1 : 0;
                $voto = $em->getRepository("votacionBundle:TblVotaciones")->findOneById($votoId);
                if ($voto) {
                    $voto->setVotoValidado($AuxVV);
                    $em->persist($voto);
                    $em->flush();
                    $NoError = 1;
                }
            }
            
            echo $NoError;
            exit();
        }
    }
    
    /**
     * Funcion que tra Listado de votaciones sin validad filtrando por la letra que ingresa como parametro en la primera letra del apellido
     * si viene como parametro all, no aplica filtro de letra inicial del apellido
     * 
     * @author Camilo Quijano <camilo@altactic.com>
     * @version 1
     * @param Int $validado Votos que deseo que me filtr (0=>no validados, 1=>Validados)
     * @param String $letra Letra por la que deseo filtrar los apllidos, si viene all no aplica filtro
     * @return Array Con listado de registros que coinciden con la busqueda
     */
    private function getListadoVotacion($letra, $validados)
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
                WHERE tv.votoValidado = ".$validados." ".$where."";
        $query = $em->createQuery($dql);
        return $query->getResult();
    }
    
    /**
     * Funcion que tra las letras por las que empiezan los registros que estan sin validar
     * returnando un array con key (letra), y valor (Cantidad de repeticiones) de la primera letra del apellido
     * 
     * @author Camilo Quijano <camilo@altactic.com>
     * @version 1
     * @param Int $validado Votos que deseo que me filtr (0=>no validados, 1=>Validados)
     * @return Array Con letras que tienen registros
     */
    private function getPaginacionAbecedario($validados)
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
                    WITH tu.id = tv.usuario AND tv.votoValidado = ".$validados."
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