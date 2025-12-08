<?php
session_start();
include 'db.php';

$mensaje = "";

// LÓGICA DE REGISTRO
if (isset($_POST['registro'])) {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    
    // Encriptar contraseña
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Verificar si el correo ya existe
    $check = $conn->query("SELECT id FROM usuarios WHERE email = '$email'");
    if ($check->num_rows > 0) {
        $mensaje = "El correo ya está registrado.";
    } else {
        $sql = "INSERT INTO usuarios (nombre, email, password) VALUES ('$nombre', '$email', '$password_hash')";
        if ($conn->query($sql) === TRUE) {
            $mensaje = "Cuenta creada con éxito. Ahora inicia sesión.";
        } else {
            $mensaje = "Error: " . $conn->error;
        }
    }
}

// LÓGICA DE LOGIN
if (isset($_POST['login'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM usuarios WHERE email = '$email'");

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['nombre'];
            header("Location: index.php"); // Recargar para mostrar el contenido
            exit();
        } else {
            $mensaje = "Contraseña incorrecta.";
        }
    } else {
        $mensaje = "Usuario no encontrado.";
    }
}

// LÓGICA DE LOGOUT
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jueguín Plataformero</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous"> 
    <link rel="stylesheet" href="style.css">
    <style>
        .auth-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: radial-gradient(circle at top left, #0a2e35, #0a0a0a);
        }
        .auth-box {
            background: #151515;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.1);
            width: 100%;
            max-width: 400px;
            color: white;
        }
        .auth-box h2 { color: #00b8b8; text-align: center; margin-bottom: 1.5rem; }
        .form-control { background: #222; border: 1px solid #444; color: white; }
        .form-control:focus { background: #2a2a2a; color: white; border-color: #00b8b8; box-shadow: none; }
        .btn-auth { width: 100%; background: #00b8b8; border: none; padding: 10px; color: black; font-weight: bold; margin-top: 10px; }
        .btn-auth:hover { background: white; }
        .toggle-link { text-align: center; margin-top: 15px; display: block; color: #bbb; cursor: pointer; }
        .toggle-link:hover { color: white; }
        .alert { padding: 10px; font-size: 0.9rem; margin-bottom: 15px; }
    </style>
</head>
<body>

    <?php if (!isset($_SESSION['user_id'])): ?>
    
    <div class="auth-wrapper">
        <div class="auth-box">
            <?php if($mensaje): ?>
                <div class="alert alert-info"><?php echo $mensaje; ?></div>
            <?php endif; ?>

            <div id="login-form">
                <h2>Iniciar Sesión</h2>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary btn-auth">ENTRAR</button>
                    <span class="toggle-link" onclick="toggleForms()">¿No tienes cuenta? Regístrate</span>
                </form>
            </div>

            <div id="register-form" style="display: none;">
                <h2>Crear Cuenta</h2>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" name="registro" class="btn btn-primary btn-auth">REGISTRARSE</button>
                    <span class="toggle-link" onclick="toggleForms()">¿Ya tienes cuenta? Inicia Sesión</span>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleForms() {
            var login = document.getElementById('login-form');
            var register = document.getElementById('register-form');
            if (login.style.display === 'none') {
                login.style.display = 'block';
                register.style.display = 'none';
            } else {
                login.style.display = 'none';
                register.style.display = 'block';
            }
        }
    </script>

    <?php else: ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="#">Jueguín Plataformero</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
              aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" data-target="presentacion" href="#">Presentación</a></li>
                    <li class="nav-item"><a class="nav-link" data-target="historia" href="#">Historia</a></li>
                    <li class="nav-item"><a class="nav-link" data-target="personajes" href="#">Personajes</a></li>
                    <li class="nav-item"><a class="nav-link" data-target="mecanicas" href="#">Mecánicas</a></li>
                    <li class="nav-item"><a class="nav-link" data-target="conocenos" href="#">Conócenos</a></li>
                    <li class="nav-item ms-3">
                        <a class="btn btn-outline-danger btn-sm mt-1" href="index.php?logout=true">Salir (<?php echo $_SESSION['user_name']; ?>)</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <section id="presentacion" class="content-section hero active-content d-flex align-items-center justify-content-between px-5">
        <div class="text-container">
            <h1 class="display-4 fw-bold">VIVE EL <span class="resaltado">RETO</span><br>AQUÍ</h1>
            <p class="lead mt-4">Bienvenido, <strong><?php echo $_SESSION['user_name']; ?></strong>.<br>Combina acción y lógica en un mundo minimalista<br>lleno de secretos y mecánicas sorprendentes.</p>
            <button class="btn-explore mt-4">EXPLORE</button>
        </div>

        <div class="image-container">
            <img src="drawable/personaje.png" alt="Personaje" class="personaje">
        </div>
    </section>

    <section id="historia" class="content-section" style="display: none;">
        <div class="container pt-5">
            <h2 class="text-center mt-5">📜 Historia del Juego</h2>
            <p class="text-center lead mt-3">Descubre el origen del desafío que enfrentarás en este mundo lleno de misterios y peligros.</p>
        </div>
    </section>

    <section id="personajes" class="content-section" style="display: none;">
        <div class="container pt-5">
            <h2 class="text-center mt-5">👤 Nuestros Personajes</h2>
            <p class="text-center lead mt-3">Conoce a Jorge Ortiz y a los científicos locos detrás de los inventos.</p>
        </div>
    </section>

  <section id="mecanicas" class="content-section" style="display: none;">
        <div class="container pt-5">
            <h2 class="text-center mt-5">⚙️ Mecánicas de Juego</h2>
            <p class="text-center lead mt-3">Aprende a usar los extraños poderes que te otorga este laboratorio experimental.</p>

            <div class="power-grid">
              <div class="power-card">
                <div class="power-img-wrap" tabindex="0">
                  <img src="drawable/plataforma_estatica.png" alt="Poder 1">
                  <div class="tooltip-desc">Crea una plataforma sólida en el aire para que el jugador se apoye. Uso: Varios (3).</div>
                </div>
                <div class="power-name">Plataforma estatica</div>
              </div>

              <div class="power-card">
                <div class="power-img-wrap" tabindex="0">
                  <img src="drawable/impulso.png" alt="Poder 2">
                  <div class="tooltip-desc">Genera un empuje en la dirección apuntada por el jugador. Uso: Varios (5).</div>
                </div>
                <div class="power-name">Impulso</div>
              </div>

              <div class="power-card">
                <div class="power-img-wrap" tabindex="0">
                  <img src="drawable/g_inversor.png" alt="Poder 3">
                  <div class="tooltip-desc">Invierte la gravedad del jugador mientras se mantenga presionado. Uso: Medidor.</div>
                </div>
                <div class="power-name">G-inversor</div>
              </div>

              <div class="power-card">
                <div class="power-img-wrap" tabindex="0">
                  <img src="drawable/embestifresa.png" alt="Poder 4">
                  <div class="tooltip-desc">Cambia el color del jugador para permitir un dash; al usarlo vuelve al color original. Uso: Varios (3).</div>
                </div>
                <div class="power-name">Embestifresa</div>
              </div>

              <div class="power-card">
                <div class="power-img-wrap" tabindex="0">
                  <img src="drawable/mochila_de_cocas.png" alt="Poder 5">
                  <div class="tooltip-desc">Mochila que permite descenso controlado y consume carga al estar en uso. Uso: Medidor.</div>
                </div>
                <div class="power-name">Mochila de cocas</div>
              </div>

              <div class="power-card">
                <div class="power-img-wrap" tabindex="0">
                  <img src="drawable/coca_agitada.png" alt="Poder 6">
                  <div class="tooltip-desc">Crea un área que permite mover objetos 'sin enganche' o 'flotantes' en 8 direcciones; afecta también al jugador si está dentro. Uso: Único.</div>
                </div>
                <div class="power-name">Desplazador</div>
              </div>

              <div class="power-card">
                <div class="power-img-wrap" tabindex="0">
                  <img src="drawable/Desplazador.png" alt="Poder 7">
                  <div class="tooltip-desc">Coloca un cubo que repele al jugador y objetos al tocarlo. Útil para saltos. Uso: Único.</div>
                </div>
                <div class="power-name">Cubo repulsor</div>
              </div>

              <div class="power-card">
                <div class="power-img-wrap" tabindex="0">
                  <img src="drawable/caja_repulsora.png" alt="Poder 8">
                  <div class="tooltip-desc">Caja móvil que empuja y repele objetos cercanos al contacto. Uso: (según diseño).</div>
                </div>
                <div class="power-name">Caja repulsora</div>
              </div>

              <div class="power-card">
                <div class="power-img-wrap" tabindex="0">
                  <img src="drawable/gel_adherente.png" alt="Poder 9">
                  <div class="tooltip-desc">Permite pintar superficies verticales para caminar sobre ellas por tiempo limitado. Uso: Varios (4).</div>
                </div>
                <div class="power-name">Gel Adherente</div>
              </div>

              <div class="power-card">
                <div class="power-img-wrap" tabindex="0">
                  <img src="drawable/paso_sombra.png" alt="Poder 10">
                  <div class="tooltip-desc">Marca una sombra en la posición actual; al reactivarlo teletransporta al jugador a esa marca. Uso: Único.</div>
                </div>
                <div class="power-name">Paso sombra</div>
              </div>

              <div class="power-card">
                <div class="power-img-wrap" tabindex="0">
                  <img src="drawable/iman.png" alt="Poder 11">
                  <div class="tooltip-desc">Lanza un imán que atrae plataformas 'enganche', 'desenganche' o 'flotante' según lo que toque. Uso: Varios (3).</div>
                </div>
                <div class="power-name">Iman</div>
              </div>

              <div class="power-card">
                <div class="power-img-wrap" tabindex="0">
                  <img src="drawable/reseteo_local.png" alt="Poder 12">
                  <div class="tooltip-desc">Restaura el layout al estado inicial del intento; no restaura poderes ni intentos. Uso: Único.</div>
                </div>
                <div class="power-name">Reseteo local</div>
              </div>

              <div class="power-card">
                <div class="power-img-wrap" tabindex="0">
                  <img src="drawable/error_de_codigo.png" alt="Poder 13">
                  <div class="tooltip-desc">Intercambia o altera temporalmente elementos del nivel (por ejemplo, entrada/salida). Uso: Único.</div>
                </div>
                <div class="power-name">Eror de codigo</div>
              </div>

              <div class="power-card">
                <div class="power-img-wrap" tabindex="0">
                  <img src="drawable/brujula_gravitacional.png" alt="Poder 14">
                  <div class="tooltip-desc">Muestra dirección cardinal y, al usarlo, cambia la gravedad hacia esa dirección elevando/colocando al jugador. Uso: Único.</div>
                </div>
                <div class="power-name">Brujula gravitacional</div>
              </div>

              <div class="power-card">
                <div class="power-img-wrap" tabindex="0">
                  <img src="drawable/singularidad.png" alt="Poder 15">
                  <div class="tooltip-desc">Crea una 'X' que elimina objetos dentro de su área (excluye entradas y salidas). Uso: Varios (3).</div>
                </div>
                <div class="power-name">Singularidad</div>
              </div>
            </div>
        </div>
    </section>


    <section id="conocenos" class="content-section" style="display: none;">
        <div class="container pt-5">
            <h2 class="text-center mt-5">👋 Conócenos</h2>
            <p class="text-center lead mt-3">El pequeño equipo apasionado por el pixel art que creó este juego.</p>
        </div>
 <div class="row align-items-center mt-5">
            <div class="col-md-4 text-center">
                <img src="drawable/miembro1.png" class="img-fluid rounded" style="max-width:180px;">
            </div>
            <div class="col-md-8">
                <h4>Luis Antonio</h4>
                <p>
                    Tecnologo en desarrollo de software.
                    Programador.
                </p>
            </div>
        </div>

        <div class="row align-items-center mt-5">
            <div class="col-md-4 text-center">
                <img src="drawable/miembro2.png" class="img-fluid rounded" style="max-width:180px;">
            </div>
            <div class="col-md-8">
                <h4>Jorge Alberto</h4>
                <p>
                    Tecnologo en desarrollo de software.
                    Artista pixel art.
                </p>
            </div>
        </div>

        <div class="row align-items-center mt-5 mb-5">
            <div class="col-md-4 text-center">
                <img src="drawable/miembro3.png" class="img-fluid rounded" style="max-width:180px;">
            </div>
            <div class="col-md-8">
                <h4>Sinuhe Beltran</h4>
                <p>
                    Tecnologo en desarrollo de software.
                    Documentador y tester.
                </p>
            </div>
        </div>
    </div>

    </section>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>

    <?php endif; ?>
</body>
</html>
