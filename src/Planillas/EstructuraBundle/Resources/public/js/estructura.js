// vars
var alert_error = '<div class="alert alert-danger">__message__<div>';
var alert_success = '<div class="alert alert-success">__message__<div>';
var help_block = '<span class="help-block">__message__ <br></span>'
var progress_bar = '<div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>';
var $actual_process = "empresa";

/**
 * Recarga el contenido del select "Cliente" para la pestaña activa actual
 * @param empresa_id Identificador de la empresa por la cual cargar los clientes
 * @param select Objeto JQuery select
 */
function reloadEmpresaSelect (select) {
    if(!$.isEmptyObject($(select))) {
        $(select).block($block_ui_config);
        $.get(Routing.generate('estructura_empresas'), function (data) {
            $(select).html(data);
            $(select).unblock();
            reloadClienteSelect($(select).find('option:first').val(), findSelectType('cliente'));
        });
    }
}

/**
 * Recarga el contenido del select "Cliente" para la pestaña activa actual
 * @param empresa_id Identificador de la empresa por la cual cargar los clientes
 * @param select Objeto JQuery select
 */
function reloadClienteSelect (empresa_id, select) {
    if(empresa_id != "" && empresa_id != undefined && !$.isEmptyObject(select)) {
        $.get(Routing.generate('estructura_clientes',{'id':empresa_id}), function (data) {
            $(select).removeAttr('disabled');
            $(select).html(data);
            reloadSucursalSelect($(select).find('option:first').val(), findSelectType('sucursal'));
        });
    } else {
        $(select).attr('disabled','disabled');
        reloadSucursalSelect("", findSelectType('sucursal'));
    }
}

/**
 * Recarga el contenido del select "Sucursal" para la pestaña activa actual
 * @param cliente_id Identificador del cliente por el cual cargar las sucursales
 * @param select Objeto JQuery select
 */
function reloadSucursalSelect (cliente_id, select) {
    if(cliente_id != "" && cliente_id != undefined && !$.isEmptyObject(select)) {
        $.get(Routing.generate('estructura_sucursales',{'id':cliente_id}), function (data) {
            $(select).removeAttr('disabled');
            $(select).html(data);
            reloadTurnoSelect($(select).find('option:first').val(), findSelectType('turno'));
        });
    } else {
        $(select).attr('disabled','disabled');
        reloadTurnoSelect("", findSelectType('turno'));
    }
}

/**
 * Recarga los contenidos del select "Turno" para la pestaña activa actual
 * @param sucursal_id
 * @param select
 */
function reloadTurnoSelect (sucursal_id, select) {
    if(sucursal_id != "" && sucursal_id != undefined && !$.isEmptyObject(select)) {
        $.get(Routing.generate('estructura_turnos',{'id':sucursal_id}), function (data) {
            $(select).removeAttr('disabled');
            $(select).html(data);
            reloadPuestoSelect($(select).find('option:first').val(), findSelectType('puesto'));
        });
    } else {
        $(select).attr('disabled','disabled');
        reloadPuestoSelect("", findSelectType('puesto'))
    }
}

function reloadPuestoSelect (turno_id, select) {
    if(turno_id != "" && turno_id != undefined && !$.isEmptyObject(select)) {
        $.get(Routing.generate('estructura_puestos',{'id':turno_id}), function (data) {
            $(select).removeAttr('disabled');
            $(select).html(data);
        });
    } else {
        $(select).attr('disabled','disabled');
    }
}

function findSelectType (type) {
    var _form = $('form#' + $actual_process + '-form');
    var _select = _form.find('select[id$=' + $actual_process + '_' + type + ']');
    return _select;
}

/**
 * Envia el formulario de la pestaña actual
 * @private
 */
function _submit() {
    $('div#' + $actual_process +'-body').block($block_ui_config);
    var data = $('form#' + $actual_process + '-form').serialize();
    $.ajax({
        'url': Routing.generate('estructura_' + $actual_process + '_create'),
        'data': data,
        'type': "POST",
        'dataType': "HTML"
    })
        .done(function (response) {
            var message = response;
            var alert = transform2JQueryObject(message, alert_success);
            appendAlertMessage(alert);
            cleanFormErrors('planillas_estructurabundle_' + $actual_process + '_');


            $.ajax({
                'url': Routing.generate('estructura_' + $actual_process + '_list'),
                'type': 'GET'
            })
                .done(function (htmltable) {
                    $('div#' + $actual_process + '-body').html(htmltable);
                })
                .always(function(){
                    $('div#' + $actual_process +'-body').unblock();
                });
        })
        .fail(function (error) {
            var errors = JSON.parse(error.responseText);
            //var entidad = $actual_process.toUpperCase();
            //var alert = transform2JQueryObject('Ha ocurrido un error creando ' + entidad + '.',alert_error);
            //appendAlertMessage(alert);
            proccessErrors(errors, '#planillas_estructurabundle_' + $actual_process + '_');

            $('div#' + $actual_process +'-body').unblock();
        })
        .always();
}

/**
 * Transforma texto html en objeto jquery sustituyendo "__message__" en cualquier lugar del template utulizado
 * @param message
 * @param template
 * @returns {*|jQuery|HTMLElement}
 */
function transform2JQueryObject (message,template) {
    var object = template.replace(/__message__/g, message);
    return $(object);
}

/**
 * Procesa y muestra en el componente con identificador id los errores asociados al mismo.
 * @param errors
 * @param id
 */
function proccessErrors (errors, inputId) {
    $.each(errors, function (key, errors) {
        if (isNaN(key)) {
            if (key =="error") {
                $.each(errors, function (k, value) {
                    var alert = alert_error.replace(/__message__/g, value);
                    appendAlertMessage(alert);
                });
            } else {
                var input = $("input" + inputId + key);
                var select = $("select" + inputId + key);
                if(input.size()!=0)
                    var element = input;
                else
                    var element = select;
                element.parent().parent().addClass('has-error');

                $.each(errors, function (k, value) {
                    element.parent().append(transform2JQueryObject(value, help_block));
                });
            }
        } else {
            var alert = alert_error.replace(/__message__/g, value);
            appendAlertMessage(alert);
        }
    });
}

/**
 * Adiciona al div de mensajes un nuevo mensaje
 * @param alert
 */
function appendAlertMessage(alert) {
    alert = $(alert).delay(4000).hide(500);
    $('div#event-messages').append(alert);
}

/**
 * Limpia los errores en el formulario pasado por parámetros
 * @param formname
 */
function cleanFormErrors (formname) {
    $('input[id^=' + formname + ']').each(function (key, element) {
        $(element).parent().parent().removeClass('has-error');
        $(element).parent().find('span.help-block').remove();
        $(element).val('');
    });
    $('select[id^=' + formname + ']').each(function (key, element) {
        $(element).parent().parent().removeClass('has-error');
        $(element).parent().find('span.help-block').remove();
    });
}

function bind_events () {
    $('ul.pagination a').on('click', function (e) {
        e.preventDefault();
        load_page($(this));
    });
}

/**
 * Carga los datos de la página en la pestaña actual
 * @param element
 */
function load_page(element) {
    $('div#' + $actual_process +'-body').block($block_ui_config);

    var data;
    var page = -1;
    if(element != undefined) {
        var route = $(element).attr('href');
        var params = route.split("?");
        for (var i=0; i < params.length; i++) {
            if (params[i].indexOf("page") >= 0) {
                var split = params[i].split("&");
                for (var j=0; j < split.length; j++) {
                    if (split[j].indexOf("page") == 0) {
                        var page_var = split[j].split("=");
                        page = page_var[1];
                        break;
                    }
                }
            }
        }
    }

    page = (page != -1) ? page : 1;
    data = {'page':page};

    $.ajax({
        'url': Routing.generate('estructura_' + $actual_process + '_list'),
        'data' : data,
        'type': 'GET'
    })
    .done(function (htmltable) {
        $('div#' + $actual_process +'-body')
            .html(htmltable)
            .unblock();
        bind_events ();
    })
    .always(function () {
    });
}