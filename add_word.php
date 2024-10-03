<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ingles = $_POST['palabra_ingles'];
    $espanol = $_POST['palabra_espanol'];

    if (!empty($ingles) && !empty($espanol)) {
        $db = getDatabaseConnection();
        
        // Verificar si la palabra en inglés ya existe
        $stmt = $db->prepare("SELECT COUNT(*) FROM palabras WHERE palabra_ingles = ? OR palabra_espanol = ?");
        $stmt->execute([$ingles, $espanol]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            // La palabra ya existe, mostrar un mensaje o manejarlo de otra manera
            $errorMessage = "La palabra ya existe en la base de datos.";
        } else {
            // La palabra no existe, proceder a insertarla
            $stmt = $db->prepare("INSERT INTO palabras (palabra_ingles, palabra_espanol) VALUES (?, ?)");
            $stmt->execute([$ingles, $espanol]);
            header('Location: index.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Palabra</title>
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
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            margin: 10px 0;
            display: block;
            text-align: left;
        }

        input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 15px;
            border: none;
            background-color: #3498db;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        button:hover {
            background-color: #2980b9;
        }

        .menu-bar {
            font-size:10px;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #2a2a2a;
            padding: 0px;
            box-sizing: border-box;
        }

        .menu-button {
            background-color: transparent;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            float: left;
        }

        .flecha {
            font-size: 16px;
            margin-right: 5px;
        }

        .error-message {
            color: red;
            margin-top: 20px;
        }
    </style>
    <script>
        function redirectToIndex() {
            window.location.href = 'index.php'; // Asegúrate de que esta sea la URL correcta
        }
    </script>
</head>
<body>
    <div class="menu-bar">
        <button class="menu-button" onclick="redirectToIndex()">
            <span class="flecha">&#x25C0; Volver</span> 
        </button>
    </div>

    <div class="container">
        <h1>Añadir Nueva Palabra</h1>
        <form method="post" action="add_word.php">
            <label for="palabra_ingles">Palabra en Inglés:</label>
            <input type="text" name="palabra_ingles" id="palabra_ingles" required>

            <label for="palabra_espanol">Traducción en Español:</label>
            <input type="text" name="palabra_espanol" id="palabra_espanol" required>

            <button type="submit">Añadir</button>
        </form>
        <?php if (isset($errorMessage)): ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
