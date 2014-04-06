<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jose
 * Date: 9/10/13
 * Time: 10:08
 * To change this template use File | Settings | File Templates.
 */

namespace Planillas\EntidadesBundle\Form\EventListener;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EmpleadoFieldSuscriber implements EventSubscriberInterface
{
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     * * The method name to call (priority defaults to 0)
     * * An array composed of the method name to call and the priority
     * * An array of arrays composed of the method names to call and respective
     * priorities, or 0 if unset
     *
     * For instance:
     *
     * * array('eventName' => 'methodName')
     * * array('eventName' => array('methodName', $priority))
     * * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        // check if the product object is "new"
        // If you didn't pass any data to the form, the data is "null".

        $form->add('empleado','text',array(
            'data_class' => 'BusBPM\CoreBundle\Entity\Boleta',
            'attr' => array(
                'readonly' => true,
            )
        ));
        // This should be considered a new "Product"
        /*if (!$data || !$data->getEmpleado()) {
            $form->add('boleta','entity',array(
                'class' => 'BusBPMCoreBundle:Boleta',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('b')
                        ->select(array('b','cc'))
                        ->leftJoin('b.casocajero','cc')
                        ->where('b.fecha = :fecha AND NOT EXISTS(SELECT e FROM BusBPMConfigBundle:ExcepcionBoleta e WHERE e.rol = b.rol AND e.fecha = :fecha)')
                        ->andWhere('cc IS NULL')
                        ->setParameter('fecha',date_format(new \DateTime(),'Y-m-d'));
                }
            ));
        } else {
            $form->add('boleta','text',array(
                'data_class' => 'BusBPM\CoreBundle\Entity\Boleta',
                'attr' => array(
                    'readonly' => true,
                )
            ));
        }*/
    }
}
