<?php
namespace AT\votacionBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ResultadosVotacionesController extends Controller
{
    /**
     * Estado de las votaciones (Estadisticas)
     * 
     * @author Camilo Quijano <camilo@altactic.com>
     * @version 1
     * @Template("votacionBundle:ResultadosVotaciones:index.html.twig")
     * @Route("/resultados", name="resultado_votaciones")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Render
     * @Method("GET")
     */
    public function indexAction()
    {
        $security = $this->get('security');
        //$acceso_denegado = true;
        
        $totalVotos = 0;
        $candidatos = $this->getResultadosPublicaciones();
        foreach ($candidatos as $candidato) {
            $totalVotos += $candidato['votaciones'];
        }
        
        return array(
            'candidatos' => $candidatos,
            'totalVotos' => $totalVotos
        );
    }
    /**
     * Arreglo de usuarios y votos por usuario
     * 
     * @author Camilo Quijano <camilo@altactic.com>
     * @version 1
     * @return Array Arreglo de candidatos y votos por candidato
     */
    private function getResultadosPublicaciones()
    {
        $em = $this->getDoctrine()->getManager();
        /**
         * @var String Consulta SQL que trae los candidatos con el respectivo conteo de los votos que han sido validados
         * SELECT tc.*, COUNT(tv.id) AS votaciones
         * FROM tbl_candidatos tc
         * LEFT JOIN tbl_votaciones tv
         *  ON tc.id = tv.candidato_id AND tv.voto_validado = 1
         * GROUP BY tc.id;
         */
        $dql = "SELECT tc.id, tc.candidatoNoTarjeton, tc.candidatoNombre, tc.candidatoImagen, COUNT(tv.id) AS votaciones
                FROM votacionBundle:TblCandidatos tc
                LEFT JOIN votacionBundle:TblVotaciones tv
                    WITH tc.id = tv.candidato AND tv.votoValidado = 1
                GROUP BY tc.id
                ORDER BY votaciones DESC";
        $query = $em->createQuery($dql);
        $entities=$query->getResult();
        return $entities;
    }
}
?>
