<?php

namespace Planillas\EntidadesBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Planillas\EntidadesBundle\Form\EventListener\EmpleadoFieldSubscriber;

class EFamiliaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$value=$options['id_empleado'];
        //$builder->addEventSubscriber(new EmpleadoFieldSubscriber());
        $builder
            ->add('edad')
            ->add('nombre')
            //->add('empleado')
            //->add('empleadoid'.'text')

            ->add('parentesco')
            ->add('ocupacion',null,array('label'=>'Ocupación'))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\EntidadesBundle\Entity\EFamilia'
        ));
        /*$resolver->setRequired(array(
            'id_empleado',
        ));*/
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_entidadesbundle_efamilia';
    }
}
