<?php
include 'database.php';

$db = getDatabaseConnection();

$id = $_GET['id'];

// Obtener la palabra actual
$stmt = $db->prepare("SELECT * FROM palabras WHERE id = ?");
$stmt->execute([$id]);
$palabra = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ingles = $_POST['palabra_ingles'];
    $espanol = $_POST['palabra_espanol'];

    // Actualizar la palabra en la base de datos
    $stmt = $db->prepare("UPDATE palabras SET palabra_ingles = ?, palabra_espanol = ? WHERE id = ?");
    $stmt->execute([$ingles, $espanol, $id]);
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Palabra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            box-sizing: border-box;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #2980b9;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            color: #3498db;
            font-size: 16px;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            input[type="text"] {
                font-size: 14px;
            }

            button {
                padding: 10px;
                font-size: 14px;
            }

            a {
                font-size: 14px;
            }
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
        <h1>Editar Palabra</h1>
        <form method="post" action="edit_word.php?id=<?php echo $id; ?>">
            <label for="palabra_ingles">Palabra en Inglés:</label>
            <input type="text" name="palabra_ingles" id="palabra_ingles" value="<?php echo htmlspecialchars($palabra['palabra_ingles']); ?>" required>

            <label for="palabra_espanol">Traducción en Español:</label>
            <input type="text" name="palabra_espanol" id="palabra_espanol" value="<?php echo htmlspecialchars($palabra['palabra_espanol']); ?>" required>

            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
