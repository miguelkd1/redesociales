<?php
// Mostrar todos los errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de la base de datos
$servername = "localhost";
$username = "root"; // Usuario por defecto de XAMPP
$password = ""; // Contraseña por defecto de XAMPP (vacía)
$dbname = "consultoria";

try {
    // Crear conexión con PDO para mejor manejo de errores
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    // Configurar PDO para que lance excepciones en caso de error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Procesar los datos del formulario
    $response = array();
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los datos del formulario
        $nombre = $_POST["nombre"] ?? '';
        $email = $_POST["email"] ?? '';
        $telefono = $_POST["telefono"] ?? '';
        $sede = $_POST["sede"] ?? '';
        $servicio = $_POST["servicio"] ?? ''; // Este campo no está en la tabla usuarios
        $fecha = !empty($_POST["fecha"]) ? $_POST["fecha"] : null; // Este campo no está en la tabla usuarios
        $mensaje = $_POST["mensaje"] ?? ''; // Este campo no está en la tabla usuarios
        
        // Validar campos requeridos
        if (empty($nombre) || empty($email) || empty($telefono) || empty($sede)) {
            $response["success"] = false;
            $response["message"] = "Por favor, complete todos los campos obligatorios.";
        } else {
            // Preparar la consulta SQL para la tabla usuarios
            // Nota: profesional_id se establece en 1 (Dr. Heiner Moreno)
            $sql = "INSERT INTO usuarios (nombre, email, telefono, sede, profesional_id, servicio, fecha, mensaje) 
                    VALUES (:nombre, :email, :telefono, :sede, 1, :servicio, :fecha, :mensaje)";
            
            $stmt = $conn->prepare($sql);
            
            // Vincular parámetros
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':sede', $sede);
            $stmt->bindParam(':servicio', $servicio);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':mensaje', $mensaje);
            
            // Ejecutar la consulta
            if ($stmt->execute()) {
                $response["success"] = true;
                $response["message"] = "¡Gracias por contactarnos! Nos comunicaremos contigo pronto.";
            } else {
                $response["success"] = false;
                $response["message"] = "Error al enviar el formulario.";
            }
        }
        
        // Devolver respuesta en formato JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // Si no es una petición POST, redirigir a la página de contacto
        header("Location: contacto.html");
        exit();
    }
} catch (PDOException $e) {
    // Capturar y mostrar cualquier error de base de datos
    header('Content-Type: application/json');
    echo json_encode([
        "success" => false,
        "message" => "Error de base de datos: " . $e->getMessage()
    ]);
} catch (Exception $e) {
    // Capturar cualquier otro error
    header('Content-Type: application/json');
    echo json_encode([
        "success" => false,
        "message" => "Error general: " . $e->getMessage()
    ]);
}
?>
