<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

$collection->add('empleado', new Route('/', array(
    '_controller' => 'PlanillasCoreBundle:CEmpleado:index',
)));

$collection->add('empleado_show', new Route('/{id}/show', array(
    '_controller' => 'PlanillasCoreBundle:CEmpleado:show',
)));

$collection->add('empleado_new', new Route('/new', array(
    '_controller' => 'PlanillasCoreBundle:CEmpleado:new',
)));

$collection->add('empleado_create', new Route(
    '/create',
    array('_controller' => 'PlanillasCoreBundle:CEmpleado:create'),
    array('_method' => 'post')
));

$collection->add('empleado_edit', new Route('/{id}/edit', array(
    '_controller' => 'PlanillasCoreBundle:CEmpleado:edit',
)));

$collection->add('empleado_update', new Route(
    '/{id}/update',
    array('_controller' => 'PlanillasCoreBundle:CEmpleado:update'),
    array('_method' => 'post|put')
));

$collection->add('empleado_delete', new Route(
    '/{id}/delete',
    array('_controller' => 'PlanillasCoreBundle:CEmpleado:delete'),
    array('_method' => 'post|delete')
));

return $collection;
