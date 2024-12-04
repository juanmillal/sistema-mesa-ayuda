<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";  // Dirección del servidor (generalmente "localhost" en entornos locales)
$username = "root";         // Nombre de usuario de la base de datos
$password = "";             // Contraseña del usuario (vacía por defecto en XAMPP)
$dbname = "mesa_ayuda";     // Nombre de la base de datos

// Crear la conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error); // Muestra un mensaje de error y termina la ejecución si hay un problema
}

// Obtener los datos enviados desde el formulario
$usuario = $_POST['usuario'];   // Captura el valor del campo 'usuario'
$email = $_POST['email'];       // Captura el valor del campo 'email'
$consulta = $_POST['consulta']; // Captura el valor del campo 'consulta'

// Crear la consulta SQL para insertar los datos en la tabla 'tickets'
$sql = "INSERT INTO tickets (usuario, email, consulta) VALUES ('$usuario', '$email', '$consulta')";

// Ejecutar la consulta y verificar si fue exitosa
if ($conn->query($sql) === TRUE) {
    // Si la consulta fue exitosa, muestra un mensaje de confirmación
    echo "<script>
        alert('Tu consulta ha sido enviada exitosamente.');
        window.location.href = 'index.html'; // Redirige al usuario de vuelta al formulario
    </script>";
} else {
    // Si hubo un error en la consulta, muestra el error
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Cierra la conexión a la base de datos
$conn->close();
?>
