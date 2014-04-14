<?php

namespace Planillas\CoreBundle\Controller;

use Doctrine\ORM\EntityManager;
use Planillas\CoreBundle\Form\Model\SalarioBasePuesto;
use Planillas\CoreBundle\Form\Type\SalarioBasePuestoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Planillas\CoreBundle\Entity\CSalarioBase;

/**
 * CSalarioBase controller.
 *
 */
class CSalarioBaseController extends Controller
{
    /**
     * Lists all CSalarioBase entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PlanillasCoreBundle:CSalarioBase')->findByPagado(array('pagado' => 0)); //los no pagados

        return $this->render('PlanillasCoreBundle:CSalarioBase:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new CSalarioBase entity.
     *
     */
    public function createAction(Request $request, $id_empleado)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        if (!$eEmpleado) {
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }
        $entity = new CSalarioBase();
        $entity->setEmpleado($eEmpleado);

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $periodoPago = $em->getRepository('PlanillasNomencladorBundle:NPeriodoPago')->findOneBy(array(
                'activo' => true,
            ));
            $entity->setPeriodoPago($periodoPago);

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('csalariobase_new', array('id' => $entity->getEmpleado()->getId())));
        }

        return $this->render('PlanillasCoreBundle:CSalarioBase:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a SalarioBasePuesto model.
     *
     * @return \Symfony\Component\Form\Form The form
     * @deprecated
     */
    private function createCreateForm($id_empleado)
    {
        $form = $this->createForm(new SalarioBasePuestoType(), null, array(
            'action' => $this->generateUrl('csalariobase_create', array('id_empleado' => $id_empleado)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array(
            'label' => 'Guardar',
            'attr' => array(
                'class' => 'btn btn-primary'
            )));

        return $form;
    }

    /**
     * Displays a form to create a new CSalarioBase entity.
     *
     */
    public function newAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $handler = $this->get('core.salario_base_puesto.handler');

        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find((int) $id);
        if (!$eEmpleado) {
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }

        if ($eEmpleado->getSalarioBase() == null) {
            $form = $handler->createNewForm($eEmpleado->getId());
        } else {
            $form = $handler->createEditForm($eEmpleado);
        }

        $entities = $this->getComponentesPagadas($id);

        return $this->render('PlanillasCoreBundle:CSalarioBase:new.html.twig', array(
                    'form' => $form->createView(),
                    'eEmpleado' => $eEmpleado,
                    'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a CSalarioBase entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CSalarioBase')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CSalarioBase entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CSalarioBase:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing CSalarioBase entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CSalarioBase')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CSalarioBase entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
        $entities = $this->getComponentesPagadas($id); //$em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')->findBy(array('empleado' => $entity->getEmpleado()->getId()));

        return $this->render('PlanillasCoreBundle:CSalarioBase:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                    'eEmpleado' => $entity->getEmpleado(),
                    'entities' => $entities
        ));
    }

    /**
     * Creates a form to edit a CSalarioBase entity.
     *
     * @param CSalarioBase $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     * @deprecated
     */
    private function createEditForm(SalarioBasePuesto $entity)
    {
        $form = $this->createForm(new SalarioBasePuestoType(true), $entity, array(
            'action' => $this->generateUrl('csalariobase_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array(
            'label' => 'Actualizar',
            'attr' => array(
                'class' => 'btn btn-primary'
            )));

        return $form;
    }

    /**
     * Edits an existing CSalarioBase entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $handler = $this->get('core.salario_base_puesto.handler');

        if ($handler->handle_update($request, $id)) {
            return $this->redirect($this->generateUrl('csalariobase_new', array('id' => $id)));
        }

        $entities = $this->getComponentesPagadas($id);
        //$deleteForm = $this->createDeleteForm($id);
        return $this->render('PlanillasCoreBundle:CSalarioBase:new.html.twig', array(
            //'entity' => $entity,
            'form' => $handler->getForm()->createView(),
            //'delete_form' => $deleteForm->createView(),
            'entities' => $entities,
            'eEmpleado' => $handler->getForm()->getData()->getEmpleado(),
        ));
    }

    /**
     * Deletes a CSalarioBase entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasCoreBundle:CSalarioBase')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CSalarioBase entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('csalariobase'));
    }

    /**
     * Creates a form to delete a CSalarioBase entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('csalariobase_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm();
    }

    /* Helper functions */

    public function getComponentesPagadas($iIdEmpleado)
    {
        /*$con=$this->container->get('database_connection');
        $sql=$sql = 'SELECT  * FROM e_componentes_salariales LEFT JOIN c_planillas_componentes ON (e_componentes_salariales.id=c_planillas_componentes.componentePermanente_id)';
        $rows=$con->query($sql);
        echo "<pre>";print_r($rows->fetchAll());echo "</pre>";exit;*/
        /*
         *  $conn = $this->container->get('database_connection');
            $sql = 'SELECT res.id, COUNT(*)...';
            $rows = $conn->query($sql);
         * */

        $em = $this->getDoctrine()->getManager();
        $sql = 'SELECT c  FROM PlanillasEntidadesBundle:EcomponentesSalariales c';
        $sql .= ' where c.empleado=' . $iIdEmpleado;
        $sql.=' order by c.id desc';
        $query = $em->createQuery($sql);
        $results = $query->getResult();
        /* $sql = 'SELECT c  FROM PlanillasCoreBundle:CPlanillasComponentesPermanentes c  right Join c.componentePermanente e ';
          $sql .= ' where c.empleado=' . $iIdEmpleado;
          $sql.=' order by c.id desc';
          $sql = "SELECT * FROM `e_componentes_salariales`
          RIGHT JOIN `c_planillas_componentes` ON (`e_componentes_salariales`.id = `c_planillas_componentes`.componentePermanente_id)";
          $query =  $em->createQuery($sql);
          $data = $query->getArrayResult(); */
        /* foreach ($data as $d) {
          print_r($d->getComponentePermenante());
          }exit; */
        $salida = array();
        foreach ($results as $r) {
            /** @var  \Planillas\EntidadesBundle\Entity\EcomponentesSalariales $r */
            if ($r->getPlanillaEmpleado() != null) {
                if ($r->getComponente() == 0 && $r->getPermanente() == false) {

                    $salida[] = array(
                        'componente' => $r->getComponente(),
                        'tipoDeuda' => $r->getTipoDeuda(),
                        'montoTotal' => $r->getMontoTotal(),
                        'moneda' => $r->getMoneda(),
                        'planilla' => array(
                            'fechaInicio' => $r->getPlanillaEmpleado()->getPlanilla()->getFechaInicio(),
                            'fechaFin' => $r->getPlanillaEmpleado()->getPlanilla()->getFechaFin()),
                    );
                }
            } else {
                $componentes = $oBonificaciones = $em->getRepository('PlanillasCoreBundle:CPlanillasComponentesPermanentes')
                    ->findBy(array(
                        'componentePermanente' => $r->getId(),
                        'empleado' => $iIdEmpleado
                    ));
                if (count($componentes) > 0) {
                    foreach ($componentes as $comp) {
                        if ($r->getComponente() == 1) {
                            $salida[] = array(
                                'componente' => $r->getComponente(),
                                'tipoDeuda' => $r->getTipoDeuda(),
                                'cantidad' => $r->getCantidad(),
                                'moneda' => $r->getMoneda(),
                                'planilla' => array('fechaInicio' => $comp->getPlanilla()->getFechaInicio(), 'fechaFin' => $comp->getPlanilla()->getFechaFin()),
                            );
                        } else {
                            $salida[] = array(
                                'componente' => $r->getComponente(),
                                'tipoDeuda' => $r->getTipoDeuda(),
                                'montoTotal' => $r->getMontoTotal(),
                                'moneda' => $r->getMoneda(),
                                'planilla' => array('fechaInicio' => $comp->getPlanilla()->getFechaInicio(), 'fechaFin' => $comp->getPlanilla()->getFechaFin()),
                            );
                        }
                    }
                }
            }
        }

        return $salida; //$query->getArrayResult();
    }
    /**
     *  public function findStadisticsYear($costumer_id, $year) {
    $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
    $rsm->addScalarResult('month', 'month');
    $rsm->addScalarResult('num_invoices', 'num_invoices');
    $rsm->addScalarResult('total_amount', 'total_amount');
    $q = $this->getEntityManager()->createNativeQuery(
    'SELECT MONTH(i.date) as month,
    count(i.id) as num_invoices,
    sum(i.amount) as total_amount
    FROM invoice
    WHERE costumer_id=:idCostumer
    AND YEAR(i.date) = :year
    GROUP BY MONTH(i.date) ',
    $rsm);

    $q->setParameter('idCostumer', $costumer_id)
    ->setParameter('year', $year);

    return $q->getResult();
    }
     */

}
