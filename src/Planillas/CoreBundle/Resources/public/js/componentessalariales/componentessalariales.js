function createRebajo() {
    var $form = $('button#btn-submit-rebajo').closest('form');
    $form.block($block_ui_config);

    var data = $form.serialize();

    $.ajax({
        'url': Routing.generate('componentes_salariales_create',{'componente':'rebajo'}),
        'data': data,
        'type': "POST",
        'dataType': "HTML"
    })
        .done(function(response) {
            $form.replaceWith($(response));
            load_page();
        })
        .fail()
        .always(function(){
            $form.unblock();
        })
    ;
}

function createBonificacion() {
    var $form = $('button#btn-submit-bonificacion').closest('form');
    $form.block($block_ui_config);

    var data = $form.serialize();

    $.ajax({
        'url': Routing.generate('componentes_salariales_create',{'componente':'bonificacion'}),
        'data': data,
        'type': "POST",
        'dataType': "HTML"
    })
        .done(function(response) {
            $form.replaceWith($(response));
            load_page();
        })
        .fail()
        .always(function(){
            $form.unblock();
        })
    ;
}

function searchHistorial() {
    var $form = $('button#buscar_historial').closest('form');
    var _data = $form.serialize();
    var _action = $form.attr('action');

    var $panel = $('div#results-panel');
    $panel.block($block_ui_config);

    $.ajax({
        url: _action,
        data: _data,
        type: 'GET',
        dataType: 'HTML'
    })
        .done(function(response){
            $panel.replaceWith($(response).find('div#results-panel'));
            $('ul.pagination a').on('click', function (e) {
                e.preventDefault();
                load_page($(this));
            });
        })
        .fail()
        .always(function(){
            $panel.unblock();
        })
    ;

}

function bind_events () {
    $('ul.pagination a').on('click', function (e) {
        e.preventDefault();
        load_page($(this));
    });
    $('#componente_rebajos_type_fechaInicio').datepicker({
        format: 'dd/mm/yyyy'
    });
    $('#componente_bonificaciones_type_fechaVencimiento').datepicker({
        format: 'dd/mm/yyyy'
    });

    $('button#btn-submit-rebajo').on('click', function(e) {
        e.preventDefault();
        createRebajo();
    });

    $('button#btn-submit-bonificacion').on('click', function(e) {
        e.preventDefault();
        createBonificacion();
    });

    $('button#buscar_historial').on('click', function(e){
        e.preventDefault();
        searchHistorial();
    });

    $('input#buscar_historial_componentes_salariales_fechaInicio').datepicker({
        format: 'dd/mm/yyyy'
    });

    $('input#buscar_historial_componentes_salariales_fechaFin').datepicker({
        format: 'dd/mm/yyyy'
    });

    $('input#componente_rebajos_type_permanente').on('click', function(e) {
        var numeroCuotas = $('input#componente_rebajos_type_numeroCuotas');
        if(numeroCuotas.attr('disabled'))
            numeroCuotas.removeAttr('disabled');
        else
            numeroCuotas.attr('disabled','disabled');
    });

    if($('input#componente_rebajos_type_permanente').attr('selected')){
        $('input#componente_rebajos_type_numeroCuotas').attr('disabled','disabled');
    }
}

/**
 * Carga los datos de la página en la pestaña actual
 * @param element
 */
function load_page(element) {
    var panel = $('div#results-panel');
    panel.block($block_ui_config);

    if(element != undefined)
        var link = $(element).attr('href');
    else
        var link = Routing.generate('componentes_salariales');

    panel.load(link + " div#results-panel", function () {
        panel.unblock();
        bind_events();
    });

}
