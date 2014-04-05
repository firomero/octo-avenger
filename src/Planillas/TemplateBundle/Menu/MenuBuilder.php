<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mayte
 * Date: 24/09/13
 * Time: 15:41
 * To change this template use File | Settings | File Templates.
 */

namespace Planillas\TemplateBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class MenuBuilder
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root', array(
            'navbar' => true,
            'pull-right' => false,
        ));

        $menu->addChild('Inicio', array(
            'route' => 'planillas_core_homepage'
        ));

        //empleado
	    $empleado = $menu->addChild('Empleados', array(
            'dropdown' => true,
            'caret' => true,
        ));
        $empleado->addChild('Gestionar Empleados', array(
            'route' => 'empleado_index',
        ));
        $empleado->addChild('Horas extras', array(
            'route' => 'chorasextras'
        ));
        $empleado->addChild('Incapacidades', array(
            'route' => 'cincapacidades'
        ));
        //$menu->addChild('Deudas', array('route' => 'cdeudas'));
        $empleado->addChild('Días extras', array(
            'route' => 'cdiasextra'
        ));
        $empleado->addChild('Días menos', array(
            'route' => 'causencias'
        ));

        //configuracion
        $config = $menu->addChild('Configuración', array(
            'dropdown' => true,
            'caret' => true,
        ));
        $config->addChild('Nomencladores', array(
            'route' => 'sonata_admin_dashboard'
        ));
        $config->addChild('Definir horarios',array(
            'route'=>'chorario'
        ));

        $menu->addChild('Vacantes', array(
            'route' => 'cvacante'
        ));
        $menu->addChild('Solicitudes', array(
            'route' => 'csolicitudempleo'
        ));
        $menu->addChild('Planillas de pago',array(
            'route'=>'cplanillas_listar'
        ));
        
        //$menu->addChild('Cursos', array('route' => 'cursos'));
        //$menu->addChild('Dato legal', array('route' => 'datolegal'));
        //$menu->addChild('Educacion', array('route' => 'educacion'));
        //$menu->addChild('Idioma', array('route' => 'educacionidiomas'));
        
        //$menu->addChild('Familia', array('route' => 'familia'));
        //$menu->addChild('Persona dependen', array('route' => 'personadepende'));

        return $menu;
    }

    public function subNavbar()
    {
        $menu = $this->createSubnavbarMenuItem();

        $menu->addChild('Inicio', array('route' => 'planillas_core_homepage'));
        //$menu->addChild('Listar', array('route' => 'empleado_index'));

        return $menu;
    }
}
