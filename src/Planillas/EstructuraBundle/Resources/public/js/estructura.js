// vars
var alert_error = '<div class="alert alert-danger">__message__<div>';
var alert_success = '<div class="alert alert-success">__message__<div>';
var help_block = '<span class="help-block">__message__ <br></span>'
var progress_bar = '<div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>';
var $actual_process = "empresa";

var $block_message = '<img src="data:image/gif;base64,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"/>';
var $block_css = {
    border: 'none',
    padding: '5px',
    backgroundColor: '#fff',
    '-webkit-border-radius': '10px',
    '-moz-border-radius': '10px',
    opacity: .5,
    color: '#000'
}

/**
 * Recarga el contenido del select "Cliente" para la pestaña activa actual
 * @param empresa_id Identificador de la empresa por la cual cargar los clientes
 * @param select Objeto JQuery select
 */
function reloadEmpresaSelect (select) {
    if(!$.isEmptyObject($(select))) {
        $(select).block({ message: $block_message , css: $block_css});
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
                });
        })
        .fail(function (error) {
            var errors = JSON.parse(error.responseText);
            //var entidad = $actual_process.toUpperCase();
            //var alert = transform2JQueryObject('Ha ocurrido un error creando ' + entidad + '.',alert_error);
            //appendAlertMessage(alert);
            proccessErrors(errors, '#planillas_estructurabundle_' + $actual_process + '_');

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
    $('div#' + $actual_process +'-body').block({ message: $block_message , css: $block_css});

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