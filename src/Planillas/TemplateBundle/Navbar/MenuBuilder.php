<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mayte
 * Date: 24/09/13
 * Time: 15:41
 * To change this template use File | Settings | File Templates.
 */

namespace Planillas\TemplateBundle\Navbar;

use Symfony\Component\HttpFoundation\Request;
use Mopa\Bundle\BootstrapBundle\Navbar\AbstractNavbarMenuBuilder;

class MenuBuilder extends AbstractNavbarMenuBuilder
{
    public function createMainMenu()
    {
        $menu = $this->createNavbarMenuItem();

        $menu->addChild('Inicio', array('route' => 'planillas_core_homepage'));
	    $menu->addChild('Empleados', array('route' => 'empleado_index'));
		$menu->addChild('Nomencladores', array('route' => 'sonata_admin_dashboard'));
        $menu->addChild('Vacantes', array('route' => 'cvacante'));
        $menu->addChild('Solicitudes', array('route' => 'csolicitudempleo'));
        //$menu->addChild('Horarios', array('route' => 'chorario'));
        
        $menu->addChild('Horas Extras', array('route' => 'chorasextras'));
        $menu->addChild('Incapacidades', array('route' => 'cincapacidades'));
        $menu->addChild('Deudas', array('route' => 'cdeudas'));
        $menu->addChild('Días Extras', array('route' => 'cdiasextra'));
        $menu->addChild('Días Menos', array('route' => 'causencias'));
        $menu->addChild('Definir horarios',array('route'=>'chorario'));
        $menu->addChild('Pagos',array('route'=>'cpagos'));
        
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
