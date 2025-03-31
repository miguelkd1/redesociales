// Script para recargar automáticamente la página cuando cambian los archivos CSS
(function() {
    // Intervalo de comprobación en milisegundos (2 segundos)
    const checkInterval = 2000;
    
    // Almacena la última fecha de modificación del archivo CSS
    let lastModified = '';
    
    // Función para comprobar si el archivo CSS ha cambiado
    function checkForChanges() {
        fetch('style.css', { 
            method: 'HEAD',
            cache: 'no-store' 
        })
        .then(response => {
            const currentModified = response.headers.get('Last-Modified');
            if (lastModified && lastModified !== currentModified) {
                console.log('CSS file changed, reloading page...');
                location.reload(true);
            }
            lastModified = currentModified;
        })
        .catch(error => console.error('Error checking for CSS changes:', error));
    }
    
    // Comprobar cambios periódicamente
    setInterval(checkForChanges, checkInterval);
    
    // Comprobar al cargar la página
    checkForChanges();
    
    console.log('Auto-reload script initialized. Checking for CSS changes every ' + (checkInterval/1000) + ' seconds.');
})();
