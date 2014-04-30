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
        $empleado->addChild('Días extras', array(
            'route' => 'cdiasextra'
        ));
        $empleado->addChild('Días menos', array(
            'route' => 'causencias'
        ));
        $empleado->addChild('Componentes Salariales', array(
            'route' => 'componentes_salariales'
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
        $config->addChild('Estructura Jerárquica', array(
            'route' => 'planillas_estructura_homepage'
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

        return $menu;
    }

    public function userMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('userMenu', array(
            'navbar' => true,
            'pull-right' => true,
        ));

        $menu->addChild('Usuario', array(
            'route' => 'sonata_admin_dashboard',
            'icon' => 'user'
        ));

        return $menu;
    }
}
