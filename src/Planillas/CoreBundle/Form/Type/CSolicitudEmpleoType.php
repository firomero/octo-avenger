<?php

namespace Planillas\CoreBundle\Form\Type;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CSolicitudEmpleoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('salario')
            ->add('nombre')
            ->add('apellidos')
            ->add('telefono')
            ->add('correo')
            ->add('fecha')
            ->add('vacante', 'entity', array(
                    'class' => 'PlanillasCoreBundle:CVacante',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('v')
                            ->where('v.cantidadPlazas > 0');

                    })
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\CoreBundle\Entity\CSolicitudEmpleo'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_csolicitudempleo';
    }
}
