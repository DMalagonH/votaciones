<?php

namespace AT\votacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CandidatosController extends Controller
{
    /**
     * Accion para la previsualizacion de los candidatos
     * 
     * @Route("/candidatos", name="candidatos")
     * @Template("votacionBundle:Candidatos:index.html.twig")
     * @author Diego Malagón <diego@altactic.com>
     * @return Resonse
     */
    public function indexAction()
    {
        
        $candidatos = $this->getCandidatos();
        
        return array(
            'candidatos' => $candidatos
        );
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
        
        $dql = "SELECT c.id, c.candidatoNombre, c.candidatoNoTarjeton, c.candidatoImagen, c.candidatoPartido, c.candidatoDescripcionCorta, c.candidatoLinkFacebook, c.candidatoLinkTwiter, c.candidatoLinkLinkedin
                FROM votacionBundle:TblCandidatos c
                ORDER BY c.candidatoNoTarjeton";
        $query = $em->createQuery($dql);
        $result = $query->getResult();
        
        return $result;
    }
    
    /**
     * Accion para mostrar la pagina de detalles del candidato
     * 
     * @Route("/candidatos/{id}", name="detalle_candidato")
     * @Template("votacionBundle:Candidatos:detail.html.twig")
     * @author Diego Malagón <diego@altactic.com>
     * @param integer $id id de candidato
     * @return Resonse
     */
    public function detailAction($id)
    {
        $candidato = $this->getCandidato($id);
        
        return array(
            'candidato' => $candidato
        );
    }
    
    /**
     * Funcion que obtene un candidato por id
     * 
     * @author Diego Malagón <diego@altactic.com>
     * @param integer $id id de candidato
     * @return array
     */
    private function getCandidato($id)
    {
        $return = false;
        $em = $this->getDoctrine()->getManager();
        
        $dql = "SELECT c.id, c.candidatoNombre, c.candidatoNoTarjeton, c.candidatoImagen, c.candidatoPartido, c.candidatoDescripcionCorta, c.candidatoDescripcion, c.candidatoLinkFacebook, c.candidatoLinkTwiter, c.candidatoLinkLinkedin
                FROM votacionBundle:TblCandidatos c
                WHERE c.id = :candidatoId";
        $query = $em->createQuery($dql);
        $query->setParameter('candidatoId', $id);
        $query->setMaxResults(1);
        $result = $query->getResult();
        
        if(isset($result[0]))
            $return = $result[0];
        
        return $return;
    }
            
}

?>
