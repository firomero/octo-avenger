<?php

namespace Planillas\EntidadesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NBancoType extends AbstractType
{
    var $bDestruyeEmpleado;
    
    public function __construct($bDestruyeEmpleado=false){
       $this->bDestruyeEmpleado = $bDestruyeEmpleado;    
    }
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cuentasBancos', 'entity', array(
                                                'class' => 'Planillas\NomencladorBundle\Entity\NBanco',
                                                'expanded' => true,
                                                'multiple' => true, )
                 )
        ;
        
        /*if($this->bDestruyeEmpleado)
           $builder->remove('empleado');*/
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\CoreBundle\Entity\CEmpleado'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_nomencladorbundle_ecuentabanco';
    }
}
