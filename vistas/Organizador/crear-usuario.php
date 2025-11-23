<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registro Jugador - UPE-SPORT</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Roboto&display=swap" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="crear-usuario.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container d-flex align-items-center justify-content-center min-vh-100">
  <div class="hero-card p-4 shadow rounded" style="max-width: 900px; width: 100%;">
    <div class="text-center mb-4">
      <h2>Registro de Organizador</h2>
    </div>

    <form id="formCrearJugador" class="text-start" novalidate>
      <div class="row g-4">
    
        <div class="col-md-6">
          <h3 class="mb-3">Datos de la Organización</h3>

          <div class="mb-3">
            <label for="usuario" class="form-label">Organización</label>
            <input type="text" class="form-control" id="usuario" placeholder="Tu Organización" required />
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="email" placeholder="ejemplo@correo.com" required />
            <div class="invalid-feedback d-none" id="validemail">Ingresá un correo válido</div>
          </div>

          <div class="mb-3">
            <label for="web" class="form-label">Web (opcional)</label>
            <input type="text" class="form-control" id="web" placeholder="https://tusitio.com" />
            <div class="invalid-feedback d-none" id="validweb">Ingresá una URL válida</div>
          </div>

          <div class="mb-3">
            <h5>País</h5>
            <select id="selectPais" class="form-select">
              <option></option>
            </select>
          </div>

          <div class="mb-3">
            <label>Descripción de Organización</label>
            <textarea id="descripcion" class="form-control" rows="3"></textarea>
          </div>
        </div>

        <div class="col-md-6">
          <h3 class="mb-3">Datos del Organizador</h3>

          <div class="mb-3">
            <label>Nombre</label>
            <input type="text" id="nombre" class="form-control"pattern="[A-Za-z\s]+" required/>
            <div class="invalid-feedback d-none" id="validNom">Ingresá un nombre válido</div>
          </div>

          <div class="mb-3">
            <label>Apellido</label>
            <input type="text" id="apellido" class="form-control" pattern="[A-Za-z\s]+" required/>
            <div class="invalid-feedback d-none" id="validapellido">Ingresá un apellido válido</div>
          </div>

          <div class="mb-3">
            <label for="fechaNacimiento" class="form-label">Fecha de nacimiento</label>
            <input type="date" class="form-control" id="fechaNacimiento" required />
            <div class="invalid-feedback d-none" id="validfecha">Debes tener al menos 13 años</div>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" placeholder="********" required />
            <div class="form-check mt-2">
              <input class="form-check-input" type="checkbox" id="showPass" />
              <label class="form-check-label" for="showPass">Mostrar contraseña</label>
            </div>
          </div>

          <div class="mb-3">
            <label for="confirmPassword" class="form-label">Confirmar contraseña</label>
            <input type="password" class="form-control" id="confirmPassword" placeholder="********" required />
            <div class="invalid-feedback d-none" id="validpass">Las contraseñas no coinciden</div>
          </div>
        </div>
      </div>

      
      <div class="mt-4">
        <button type="submit" class="perfil-form btn btn-primary w-100">Registrarme</button>
      </div>
    </form>

    <div class="mt-3 text-center small text-muted">
      ¿Ya tenés cuenta? <a href="../auth/login.html">Iniciá sesión</a>
    </div>
  </div>
</div>

 
  <div class="modal fade" id="registroExitoso" tabindex="-1" aria-labelledby="registroExitosoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header">
          <h5 class="modal-title" id="registroExitosoLabel">¡Registro exitoso!</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          Tu cuenta fue creada correctamente. Ahora podés iniciar sesión.
        </div>
        <div class="modal-footer">
          <a href="../auth/login.html" class="btn btn-primary">Ir a login</a>
        </div>
      </div>
    </div>
  </div>


</body>
</html>
