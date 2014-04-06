<?php

namespace Planillas\CoreBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SupervisorType extends AbstractType
{
    public $eEmpleado;
    public function __construct($eEmpleado = 0)
    {
        $this->eEmpleado = $eEmpleado;
    }
        /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
           ->add('supervisor', 'entity', array(
            'class' => 'PlanillasCoreBundle:CEmpleado',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('e')
                                ->where('e.id <> 0');
            })
        )

        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_supervisor';
    }
}
