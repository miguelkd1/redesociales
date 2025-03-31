// Script para manejar el envío del formulario de contacto
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const formStatus = document.getElementById('formStatus');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Mostrar indicador de carga
            formStatus.innerHTML = 'Enviando...';
            formStatus.className = 'form-status';
            formStatus.style.display = 'block';
            
            // Recopilar datos del formulario
            const formData = new FormData(contactForm);
            
            // Enviar datos mediante AJAX
            fetch('procesar_contacto.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                // Guardar el texto de la respuesta para depuración
                const responseClone = response.clone();
                responseClone.text().then(text => {
                    console.log('Respuesta del servidor:', text);
                });
                
                if (!response.ok) {
                    throw new Error('Error en la red: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                // Mostrar mensaje de éxito o error
                if (data.success) {
                    formStatus.innerHTML = data.message;
                    formStatus.className = 'form-status success';
                    contactForm.reset(); // Limpiar el formulario
                } else {
                    formStatus.innerHTML = data.message;
                    formStatus.className = 'form-status error';
                }
            })
            .catch(error => {
                // Mostrar mensaje de error detallado
                console.error('Error completo:', error);
                formStatus.innerHTML = 'Ha ocurrido un error al enviar el formulario: ' + error.message + 
                                       '<br>Por favor, verifica la consola del navegador para más detalles.';
                formStatus.className = 'form-status error';
            });
        });
    }
    
    // Validación de la fecha (no permitir fechas pasadas)
    const fechaInput = document.getElementById('fecha');
    if (fechaInput) {
        const today = new Date().toISOString().split('T')[0];
        fechaInput.setAttribute('min', today);
    }
});
