<?php
session_start();
require_once 'database.php';

// Conexión a la base de datos
$db = getDatabaseConnection();

// Inicializar variables
$error = '';
$mensaje = 'Ingrese la traducción'; // Mensaje inicial
$correcto = false;
$mostrar_mensaje = ''; // Mensaje para el div

// Obtener una nueva palabra aleatoria si es la primera vez o después de enviar una respuesta correcta
if (!isset($_SESSION['palabra_actual'])) {
    $stmt = $db->query("SELECT * FROM palabras ORDER BY RANDOM() LIMIT 1");
    $palabra = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($palabra) {
        $_SESSION['palabra_actual'] = $palabra; // Guardar la nueva palabra en sesión
    } else {
        $mostrar_mensaje = '<div class="mensaje incorrecto">Error: No se encontraron palabras.</div>';
    }
}

// Verificar respuesta del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $respuesta = strtolower(trim($_POST['respuesta']));
    $palabra_actual = $_SESSION['palabra_actual']; // Actualizar palabra actual al recibir la respuesta
    
    if ($palabra_actual) { // Verificar que la palabra actual exista
        $correcta = strtolower($palabra_actual['palabra_espanol']);

        if ($respuesta === $correcta) {
            $correcto = true;
            $mostrar_mensaje = '<div class="mensaje correcto"><strong>¡Correcto!</strong> La respuesta es correcta. Cargando la siguiente palabra...</div>';

            // Obtener una nueva palabra aleatoria
            $stmt = $db->query("SELECT * FROM palabras ORDER BY RANDOM() LIMIT 1");
            $palabra = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($palabra) {
                $_SESSION['palabra_actual'] = $palabra; // Actualizar la sesión con la nueva palabra
            } else {
                $mostrar_mensaje = '<div class="mensaje incorrecto">Error: No se encontraron más palabras.</div>';
            }
        } else {
            $error = 'Incorrecto. Inténtalo de nuevo con la misma palabra.';
            $mostrar_mensaje = '<div class="mensaje incorrecto">' . $error . '</div>';
        }
    } else {
        $mostrar_mensaje = '<div class="mensaje incorrecto">Error: No hay palabra actual.</div>'; // Mensaje de error
    }
} else {
    $palabra_actual = $_SESSION['palabra_actual']; // Asegurarse de que se inicialice aquí
    $mostrar_mensaje = '<div class="mensaje inicial">' . $mensaje . '</div>';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica de Inglés</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            transition: background-color 0.3s;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
            transition: transform 0.3s;
        }

        .menu-bar {
            font-size: 10px;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #2a2a2a;
            padding: 0px;
            box-sizing: border-box;
            display: flex;
            align-items: center;
        }

        .menu-button {
            background-color: transparent;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            margin-right: auto;
        }

        h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        p {
            font-size: 18px;
            margin-bottom: 20px;
            color: #555;
        }

        .palabra-ingles {
            font-size: 25px; /* Tamaño aumentado */
            color: green; /* Color visible */
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 10px;
            border: 2px solid #3498db;
            border-radius: 5px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus {
            border-color: #2980b9;
            outline: none;
        }

        button {
            padding: 12px 20px;
            border: none;
            background-color: #3498db;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin: 5px;
            font-size: 16px;
        }

        button:hover {
            background-color: #2980b9;
        }

        .mensaje {
            margin-top: 20px;
            padding: 12px;
            border-radius: 5px;
            font-weight: bold;
            transition: opacity 0.5s;
        }

        .correcto {
            background-color: #2ecc71;
            color: white;
            animation: fadeIn 0.5s;
        }

        .incorrecto {
            background-color: #e74c3c;
            color: white;
            animation: shake 0.5s;
        }

        .inicial {
            background-color: #3498db;
            color: white;
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
            100% { transform: translateX(0); }
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 24px;
            }

            p {
                font-size: 16px;
            }

            button {
                padding: 10px 15px;
            }

            input[type="text"] {
                padding: 10px;
            }
        }
    </style>
    <script>
        function autoComplete() {
            const correcta = "<?php echo htmlspecialchars($palabra_actual['palabra_espanol'] ?? ''); ?>";
            document.getElementById('respuesta').value = correcta;
        }

        function redirectToSkd() {
            window.location.href = 'index.php'; // Asegúrate de que esta sea la URL correcta
        }

        window.onload = function() {
            document.getElementById('respuesta').focus();
            document.getElementById('flecha').innerHTML = '&#x25C0; salir'; // Flecha hacia la izquierda
        }

        <?php if ($correcto): ?>
            setTimeout(() => {
                location.reload(); // Recargar después de 1 segundo si la respuesta es correcta
            }, 1000);
        <?php endif; ?>
    </script>
</head>
<body>
    <div class="menu-bar">
        <button class="menu-button" onclick="redirectToSkd()"><span class="flecha" id="flecha"></span></button>
    </div>

    <div class="container">
        <h1>Traduce la palabra</h1>
        <p>Palabra en inglés: <span class="palabra-ingles"><?php echo htmlspecialchars($palabra_actual['palabra_ingles'] ?? ''); ?></span></p>
        
        <form method="POST">
            <label for="respuesta">Tu respuesta (en español): </label>
            <input type="text" id="respuesta" name="respuesta" required>
            <button type="submit">Enviar</button>
            <button type="button" onclick="autoComplete()">Mostrar respuesta</button>
        </form>

        <div id="mensaje">
            <?php echo $mostrar_mensaje; ?>
        </div>
    </div>
</body>
</html>
