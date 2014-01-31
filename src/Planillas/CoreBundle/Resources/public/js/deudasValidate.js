$(document).ready(function() {
    
   /* if (ldapInstalled == 'true') {
        $("#password_required").hide();
        $("#rePassword_required").hide();
    }  */

   /* $("#chkLogin").attr("checked", true);

    $("#addEmployeeTbl td div:empty").remove();
    $("#addEmployeeTbl td:empty").remove();
    
    $('#photofile').after('<label class="fieldHelpBottom">'+fieldHelpBottom+'</label>');

    if(createUserAccount == 0) {
        //hiding login section by default
        $(".loginSection").hide();
        $("#chkLogin").removeAttr("checked");
    }

    //default edit button behavior
    $("#btnSave").click(function() {
        $("#frmAddEmp").submit();
    });

    $("#chkLogin").click(function() {
        $(".loginSection").hide();

        $("#user_name").val("");
        $("#user_password").val("");
        $("#re_password").val("");
        $("#status").val("Enabled");

        if($("#chkLogin").is(':checked')) {
            $(".loginSection").show();
        }
    });*/

        //form validation
    var fechaInicio=$('#planillas_corebundle_cdeudas_fechaInicio').attr('name');
    var montoTotal=$('#planillas_corebundle_cdeudas_montoTotal').attr('name');

    var validationParams={
        rules:{
            fecha:{ required: true }
        },
        messages:{ required: " " }
    };
    validationParams['rules'][fechaInicio] = { required: true };
    validationParams['messages'][fechaInicio] = { required: '' };
    validationParams['rules'][montoTotal] = { required: true };
    validationParams['messages'][montoTotal] = { required: '' };
    /*validationParams['rules'][fecha] = { required: true };
    validationParams['messages'][fecha] = { required: 'Necesario' };
    validationParams['rules'][fecha] = { required: true };
    validationParams['messages'][fecha] = { required: 'Necesario' };
    validationParams['rules'][fecha] = { required: true };
    validationParams['messages'][fecha] = { required: 'Necesario' };*/

    $("#deudasForm").validate({

        rules: validationParams.rules,
        messages: validationParams.messages,
        validClass: "valid",
        onfocusin: function(element, event) {
            this.lastActive = element;

            // hide error label and remove error class on focus if enabled
            if ( this.settings.focusCleanup && !this.blockFocusCleanup ) {
                if ( this.settings.unhighlight ) {
                    this.settings.unhighlight.call( this, element, this.settings.errorClass, this.settings.validClass );
                }
                this.addWrapper(this.errorsFor(element)).hide();
            }
        },
        onfocusout: function(element, event) {
            if ( !this.checkable(element) && (element.name in this.submitted || !this.optional(element)) ) {
                this.element(element);
            }
        },
        onkeyup: function(element, event) {
            if ( event.which === 9 && this.elementValue(element) === '' ) {
                return;
            } else if ( element.name in this.submitted || element === this.lastActive ) {
                this.element(element);
            }
        },
        onclick: function(element, event) {
            // click on selects, radiobuttons and checkboxes
            if ( element.name in this.submitted ) {
                this.element(element);
            }
            // or option elements, check parent select in that case
            else if (element.parentNode.name in this.submitted) {
                this.element(element.parentNode);
            }
        },
        highlight: function(element, errorClass, validClass) {
            if (element.type === 'radio') {

                this.findByName(element.name).addClass(errorClass).removeClass(validClass);
            } else {
                /*Planillas template*/
                $(element).parent().parent().parent().addClass(errorClass).removeClass(validClass);
            }
        },
        unhighlight: function(element, errorClass, validClass) {
            if (element.type === 'radio') {
                this.findByName(element.name).removeClass(errorClass).addClass(validClass);
            } else {
                //planillas h
                $(element).parent().removeClass(errorClass).addClass(validClass);
            }
        }

        //errorClass:'form-group has-error'
    });
    /*var validationParams = {

        errorClass: 'form-group has-error',
        rules: {},
        messages: {},
        validClass: "valid",
        errorElement: "label",
        focusInvalid: true,
        errorContainer: $( [] ),
        errorLabelContainer: $( [] ),
        onsubmit: true,
        ignore: ":hidden",
        ignoreTitle: false,


        highlight: function(element, errorClass, validClass) {
            if (element.type === 'radio') {

                this.findByName(element.name).addClass(errorClass).removeClass(validClass);
            } else {
                /*Planillas template
                $(element).parent().parent().parent().addClass(errorClass).removeClass(validClass);
            }
        },
        unhighlight: function(element, errorClass, validClass) {
            if (element.type === 'radio') {
                this.findByName(element.name).removeClass(errorClass).addClass(validClass);
            } else {
                //planillas h
                $(element).parent().removeClass(errorClass).addClass(validClass);
            }
        },

        success: function (e) {
            $(e).closest('.control-group').removeClass('error').addClass('info');
            $(e).remove();
        },

        errorPlacement: function (error, element) {
            error.insertAfter(element);
        },

        submitHandler: function (form) {
        },
        invalidHandler: function (form) {
        }
    };*/
    fechainiciofield=$('#cdeudas_montoReducir').attr('name');
   //var validationParams={};
    //validationParams['rules'][fechainiciofield] = { required: true };
    //validationParams['messages'][fechainiciofield] = { required: "Email is required" };

    //$("#deudasForm").validate(validationParams);
   // $("#cdeudas_fechaInicio").rules("add", {required:true});


});