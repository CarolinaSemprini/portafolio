jQuery(document).ready(function($) {

    var form = $('#contact-form');
    var messageContainer = $('#form-message-container');
    var submitButton = form.find('button[type="submit"]');
    var originalButtonContent = submitButton.html(); 

    // ===============================================
    // FUNCIÓN DE VALIDACIÓN INDIVIDUAL DE CAMPO
    // ===============================================
    function validateField(field) {
        // Elimina cualquier mensaje de error anterior para este campo
        field.next('.field-error-alert').remove(); 
        
        var fieldName = field.attr('name');
        var errorText = '';
        var isValid = true;

        if (field.prop('required') && field.val().trim() === '') {
            errorText = 'Este campo es obligatorio.';
            isValid = false;
        } 
        
        // Validación específica para el campo de email
        else if (fieldName === 'email') {
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(field.val())) {
                errorText = '❌ Dirección de correo inválida.';
                isValid = false;
            }
        }
        
        if (!isValid) {
            // Inserta el mensaje de error con tu estilo CSS
            field.after('<div class="field-error-alert form-alert error">' + errorText + '</div>');
        }
        
        return isValid;
    }

    // ===============================================
    // MANEJO DE ENVÍO DEL FORMULARIO
    // ===============================================
    form.on('submit', function(e) {
        e.preventDefault(); 
        
        messageContainer.empty(); // Limpiamos el mensaje general de éxito/error

        var formIsValid = true;
        
        // 1. Ejecutar validación para cada campo
        form.find('input[required], textarea[required]').each(function() {
            // Si cualquier campo falla, marcamos que el formulario no es válido.
            if (!validateField($(this))) {
                formIsValid = false;
            }
        });

        // 2. Si hay errores de validación, detenemos el proceso
        if (!formIsValid) {
            // Hacemos scroll al primer error para que el usuario lo vea
            $('html, body').animate({
                scrollTop: form.find('.field-error-alert:first').offset().top - 100 
            }, 500);
            return; // Detenemos la función aquí.
        }
        
        // 3. Si es válido, procedemos con AJAX
        
        // Elimina todos los mensajes de error individuales antes de enviar
        form.find('.field-error-alert').remove(); 
        messageContainer.html('<div class="form-alert loading">Enviando mensaje...</div>');

        submitButton.prop('disabled', true);
        submitButton.text('Enviando...'); 

        var formData = form.serialize() + '&action=semprini_send_email';

        // Llamada a AJAX al servidor
        $.ajax({
            type: 'POST',
            url: semprini_ajax.ajax_url, 
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Muestra el mensaje de éxito general
                    messageContainer.html('<div class="form-alert success">' + response.data + '</div>');
                    form.trigger('reset');
                } else {
                    // Si el servidor (PHP) devuelve un error, lo mostramos
                    messageContainer.html('<div class="form-alert error">' + response.data + '</div>');
                }
            },
            error: function() {
                messageContainer.html('<div class="form-alert error">❌ Error de conexión. No se pudo contactar con el servidor.</div>');
            },
            complete: function() {
                submitButton.prop('disabled', false);
                submitButton.html(originalButtonContent);
            }
        });
    });

    // 4. (Opcional) Validar en tiempo real al salir del campo
    form.find('input[required], textarea[required]').on('blur', function() {
        validateField($(this));
    });

    // 5. (Opcional) Revalidar al escribir
    form.find('input, textarea').on('input', function() {
        // Solo validamos después de que ya existió un error previo
        if ($(this).next('.field-error-alert').length) {
            validateField($(this));
        }
    });

});