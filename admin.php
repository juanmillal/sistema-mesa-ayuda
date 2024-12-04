<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";  // Dirección del servidor (usualmente "localhost")
$username = "root";         // Usuario de la base de datos (por defecto en XAMPP es "root")
$password = "";             // Contraseña (vacía por defecto en XAMPP)
$dbname = "mesa_ayuda";     // Nombre de la base de datos

// Crear conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error); // Termina el script si la conexión falla
}

// Verificar si el formulario de respuesta fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura los datos del formulario
    $id = $_POST['id'];                // ID del ticket
    $respuesta = $_POST['respuesta'];  // Respuesta del administrador
    
    // Actualiza el ticket con la respuesta y cambia el estado a 'resuelto'
    $sql = "UPDATE tickets SET respuesta='$respuesta', estado='resuelto' WHERE id=$id";

    // Verifica si la actualización fue exitosa
    if ($conn->query($sql) === TRUE) {
        // Muestra una alerta y redirige de nuevo a la página de administración
        echo "<script>
            alert('Respuesta enviada con éxito.');
            window.location.href = 'admin.php';
        </script>";
    } else {
        // Muestra un mensaje de error si la consulta falla
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Consulta para obtener todos los tickets pendientes
$sql = "SELECT * FROM tickets WHERE estado='pendiente'";
$result = $conn->query($sql); // Ejecuta la consulta
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"> <!-- Define el tipo de codificación de caracteres -->
    <title>Admin - Mesa de Ayuda</title>
    <style>
        /* Estilos generales para la página */
        body {
            font-family: Arial, sans-serif;  /* Usamos una fuente legible */
            margin: 0;                      /* Elimina los márgenes predeterminados */
            padding: 20px;                  /* Espaciado interior de la página */
            background: #f4f4f9;            /* Fondo gris claro */
        }
        
        h1 {
            text-align: center;            /* Centra el título */
            font-size: 32px;               /* Aumenta el tamaño de la fuente */
            color: #007bff;                /* Color azul para el título */
            margin-bottom: 30px;           /* Espaciado debajo del título */
        }

        /* Estilos para cada ticket */
        .ticket {
            background: #fff;              /* Fondo blanco para el ticket */
            margin-bottom: 20px;           /* Espaciado entre tickets */
            padding: 20px;                 /* Espaciado interior dentro del ticket */
            border-radius: 10px;           /* Bordes redondeados */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Sombra suave */
        }

        /* Estilos para los campos de texto (textarea) */
        textarea {
            width: 100%;                   /* Ancho completo para el campo */
            margin-top: 10px;               /* Espaciado superior */
            padding: 12px;                  /* Relleno interior */
            border: 2px solid #ccc;        /* Borde gris claro */
            border-radius: 8px;            /* Bordes redondeados */
            font-size: 16px;                /* Tamaño de la fuente dentro del campo */
            resize: vertical;              /* Permite redimensionar solo verticalmente */
            transition: border-color 0.3s ease; /* Suaviza el cambio de color del borde */
        }

        /* Efecto al hacer foco (click o tab) en el campo */
        textarea:focus {
            border-color: #007bff;         /* Color azul al estar enfocado */
            outline: none;                 /* Elimina el contorno de enfoque */
        }

        /* Estilos para el botón de respuesta */
        button {
            margin-top: 15px;               /* Espaciado superior */
            padding: 12px 20px;             /* Relleno del botón */
            background: #28a745;            /* Fondo verde */
            color: white;                   /* Texto blanco */
            border: none;                   /* Sin borde */
            border-radius: 8px;             /* Bordes redondeados */
            font-size: 16px;                /* Tamaño de la fuente */
            cursor: pointer;               /* Cambia el cursor al pasar por encima */
            transition: background-color 0.3s ease; /* Efecto de transición al pasar el ratón */
        }

        /* Efecto hover en el botón */
        button:hover {
            background: #218838;            /* Color más oscuro al pasar el ratón */
        }

        /* Asegura que los tickets tengan una separación correcta en pantallas pequeñas */
        @media (max-width: 600px) {
            .ticket {
                padding: 15px;  /* Menor relleno en pantallas pequeñas */
            }
            h1 {
                font-size: 28px;  /* Tamaño de fuente más pequeño en móviles */
            }
        }
    </style>
</head>
<body>
    <h1>Tickets Pendientes</h1>

    <!-- Bucle PHP para mostrar los tickets pendientes -->
    <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="ticket">
            <!-- Mostrar los datos del ticket: usuario, email y consulta -->
            <p><strong>Usuario:</strong> <?php echo $row['usuario']; ?></p>
            <p><strong>Email:</strong> <?php echo $row['email']; ?></p>
            <p><strong>Consulta:</strong> <?php echo $row['consulta']; ?></p>
            
            <!-- Formulario para responder el ticket -->
            <form method="POST">
                <!-- Campo de texto para que el administrador ingrese la respuesta -->
                <textarea name="respuesta" rows="4" placeholder="Escribe tu respuesta aquí" required></textarea>
                <!-- Campo oculto para enviar el ID del ticket con la respuesta -->
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <!-- Botón para enviar la respuesta -->
                <button type="submit">Responder</button>
            </form>
        </div>
    <?php } ?>
</body>
</html>
