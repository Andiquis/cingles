<?php
include 'database.php';

$db = getDatabaseConnection();

// Si se envía la acción de eliminar
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $db->prepare("DELETE FROM palabras WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: index.php');
    exit();
}

// Consulta para obtener todas las palabras
$consulta = $db->query("SELECT * FROM palabras");
$palabras = $consulta->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vocabulario Inglés-Español</title>
    <style>
        /* Estilos globales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
            box-sizing: border-box;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        td {
            color: #333;
        }

        a {
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #3498db;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #2980b9;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .delete-button {
            color: #e74c3c;
        }

        .delete-button:hover {
            color: #c0392b;
        }

        @media (max-width: 600px) {
            table, th, td {
                font-size: 14px;
            }

            th, td {
                padding: 8px;
            }

            .button {
                padding: 8px 12px;
            }

            .container {
                padding: 15px;
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
            padding: 10px;
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
            <span class="flecha">Aprende Ingles</span> 
        </button>
    </div>
    <div class="container">
        <h1>Lista de Vocabulario</h1>
        <table>
            <thead>
                <tr>
                    <th>Inglés</th>
                    <th>Español</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($palabras as $palabra): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($palabra['palabra_ingles']); ?></td>
                        <td><?php echo htmlspecialchars($palabra['palabra_espanol']); ?></td>
                        <td class="action-buttons">
                            <a href="edit_word.php?id=<?php echo $palabra['id']; ?>" class="button">Editar</a>
                            <a href="index.php?delete=<?php echo $palabra['id']; ?>" class="delete-button" >Eliminar</a>
                            <!--a href="index.php?delete=<?php /*echo $palabra['id'];*/ ?>" class="delete-button" onclick="return confirm('¿Estás seguro de que deseas eliminar esta palabra?')">Eliminar</a-->
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="add_word.php" class="button">Agregar nueva palabra</a>
        <br><br>
        <a href="quiz.php" class="button">Hacer una prueba</a>
    </div>
</body>
</html>
