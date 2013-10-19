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
     * Accion para el registro de votantes
     * 
     * @Route("/resultados", name="resultado_votaciones")
     * @Template("votacionBundle:ResultadosVotaciones:index.html.twig")
     * @author Camilo Quijano <camilo@altactic.com>
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Render
     */
    public function indexAction(Request $request)
    {
        $security = $this->get('security');

        //$form = $this->createRegistroForm();
        
        //$acceso_denegado = true;
        if($request->getMethod() == 'POST')
        {
            
        }
        
        $totalVotos = 0;
        $candidatos = $this->getResultadosPublicaciones();
        foreach ($candidatos as $candidato)
        {
            $totalVotos += $candidato['votaciones'];
        }
        
        print('<pre>');
        print($totalVotos);
        //print_r($canditatos);
        print('</pre>');
        
        return array(
            'candidatos' => $candidatos
           // 'form' => $form->createView(), 
        );
    }
    
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
            ";
        $query = $em->createQuery($dql);
        $entities=$query->getResult();
        return $entities;
        
        
        
        	/*
            SELECT * FROM cTA_usuarios_relaciones r
            JOIN CTA_USUARIOS c1 on R.USUARIOS_ID1 = C1.ID
            JOIN CTA_USUARIOS c2 on R.USUARIOS_ID2 = C2.ID
            WHERE r.cuenta_id = 1 AND RELACION_TIPO = 'amistad'
            AND ((r.USUARIOS_ID2 = 1 and r.relacion_Aprobada = 0)
            or ((r.USUARIOS_ID2 = 1 or r.USUARIOS_ID1 = 1) and r.relacion_Aprobada = 1))
            ORDER BY r.relacion_Aprobada
        
        
        $dql = "SELECT r.id as idrelacion, r.relacionAprobada, 
                    c1.id as id1, c1.usuarioNombre as n1, c1.usuarioApellido as a1, c1.usuarioEmail as e1, c1.usuarioThbPerfil as img1,
                    c2.id as id2, c2.usuarioNombre as n2, c2.usuarioApellido as a2, c2.usuarioEmail as e2, c2.usuarioThbPerfil as img2
                FROM CuentaBundle:CtaUsuariosRelaciones r 
                JOIN r.usuarios1 c1
                JOIN r.usuarios2 c2
                WHERE r.cuentaId =:cuenta AND r.relacionTipo = 'AMISTAD'
                    AND ((r.usuarios2 =:usuario AND r.relacionAprobada = 0)
                    OR
                    ((r.usuarios2 =:usuario OR r.usuarios1 =:usuario ) AND r.relacionAprobada = 1) 
                 )
                ORDER BY r.relacionAprobada";
        $query = $em->createQuery($dql);
        $query->setParameter('usuario', $security->getSessionValue('cta_usuario_id'));
        $query->setParameter('cuenta', $security->getSessionValue('cta_cuenta_id'));
		$entities=$query->getResult();
         */
        
        
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
}
?>
