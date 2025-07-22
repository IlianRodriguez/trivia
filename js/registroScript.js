document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('frmRegistrar');
    const btnRegistrar = document.getElementById('registrar');
    const accionInput = document.getElementById('Accion');
    const inputCedula = document.getElementById('cedula');
    const inputCorreo = document.getElementById('correo');
    const inputClave = document.getElementById('clave');
    const btnCancelar = document.getElementById('cancelar');

    // Nuevo: Select visible y campo oculto para estado
    const estadoSelect = document.getElementById('estadoSelect');
    const estadoHidden = document.getElementById('estadoHidden');

    // Por defecto el select deshabilitado
    estadoSelect.disabled = true;
    estadoSelect.value = 'activo';
    estadoHidden.value = 'activo';


    // Sincronizar campo oculto al cambiar el select (cuando está habilitado)
    estadoSelect.addEventListener('change', () => {
        estadoHidden.value = estadoSelect.value;
    });

    btnRegistrar.addEventListener('click', () => {
        const formData = new FormData(form);

        fetch('../controllers/registroCtrl.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let titulo = accionInput.value === "registrar" ? "Registrado" : "Actualizado";

                Swal.fire({
                    icon: 'success',
                    title: `Usuario ${titulo}`,
                    showConfirmButton: false,
                    timer: 1500
                });

                console.log(
                    accionInput.value === "registrar" ? "Usuario guardado:" : "Usuario actualizado:",
                    data
                );

                form.reset();
                btnRegistrar.value = "Registrar";
                accionInput.value = "registrar";
                inputCedula.removeAttribute('readonly');
                inputCorreo.removeAttribute('readonly');
                inputClave.removeAttribute('readonly');

                // Reset estado a default y deshabilitar
                estadoSelect.disabled = true;
                estadoSelect.value = 'activo';
                estadoHidden.value = 'activo';

                if ($.fn.DataTable.isDataTable('#tablaUsuarios')) {
                    $('#tablaUsuarios').DataTable().clear().destroy();
                }

                cargarUsuarios();

            } else {
                let errorsHtml = '';
                if (data.errors) {
                    for (const campo in data.errors) {
                        errorsHtml += `• ${data.errors[campo]}\n`;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Errores en el formulario',
                        text: errorsHtml
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }

                console.warn("Errores:", data.errors || data.message);
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error inesperado',
                confirmButtonText: 'OK'
            });
            console.error('Error en fetch:', error);
        });
    });

    const inputBuscar = document.getElementById('buscar');
    const tbodyResultado = document.getElementById('resultado');

    inputBuscar.addEventListener('input', () => {
        const valor = inputBuscar.value.trim();

        fetch(`../controllers/registroCtrl.php?Accion=buscar&valor=${encodeURIComponent(valor)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarUsuariosEnTabla(data.data);
                } else {
                    tbodyResultado.innerHTML = '<tr><td colspan="5">No se encontraron resultados</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error en búsqueda:', error);
            });
    });

    function mostrarUsuariosEnTabla(usuarios) {
        if (usuarios.length === 0) {
            tbodyResultado.innerHTML = '<tr><td colspan="5">Sin resultados</td></tr>';
            return;
        }

        tbodyResultado.innerHTML = '';

        usuarios.forEach(usuario => {
            const fila = document.createElement('tr');
            fila.innerHTML = `
                <td>${usuario.cedula}</td>
                <td>${usuario.nombre ?? ''}</td>
                <td>${usuario.apellido ?? ''}</td>
                <td>${usuario.rol}</td>
                <td>
                    <button 
                        class="btn btn-primary btn-sm btnEditar"
                        data-cedula="${usuario.cedula}"
                        data-nombre="${usuario.nombre ?? ''}"
                        data-apellido="${usuario.apellido ?? ''}"
                        data-usuario="${usuario.usuario ?? ''}"
                        data-correo="${usuario.correo ?? ''}"
                        data-clave="${usuario.clave ?? ''}"     
                        data-rol="${usuario.rol}"
                        data-estado="${usuario.estado ?? ''}"
                    >Editar</button>
                </td>
            `;
            tbodyResultado.appendChild(fila);
        });

        if ($.fn.DataTable.isDataTable('#tablaUsuarios')) {
            $('#tablaUsuarios').DataTable().destroy();
        }

        $('#tablaUsuarios').DataTable({
            pageLength: 5,
            searching: false,
            lengthChange: false
        });

        $('#tablaUsuarios tbody').off('click', '.btnEditar').on('click', '.btnEditar', function () {
            const boton = $(this);

            inputCedula.value = boton.data('cedula');
            document.getElementById('nombre').value = boton.data('nombre') ?? '';
            document.getElementById('apellido').value = boton.data('apellido') ?? '';
            document.getElementById('usuario').value = boton.data('usuario') ?? '';
            inputCorreo.value = boton.data('correo') ?? '';
            inputClave.value = ''; // 🔐 Por seguridad, no mostrar clave
            document.getElementById('rol').value = boton.data('rol') ?? '';

            // Estado al editar: habilitar select y setear valor
            estadoSelect.disabled = false;
            estadoSelect.value = (boton.data('estado') ?? 'activo').toLowerCase();
            estadoHidden.value = estadoSelect.value;

            accionInput.value = 'actualizar';
            btnRegistrar.value = 'Actualizar';

            inputClave.disabled = true;


            inputCedula.setAttribute('readonly', true);
        });
    }

    function cargarUsuarios() {
        fetch(`../controllers/registroCtrl.php?Accion=buscar&valor=`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarUsuariosEnTabla(data.data);
                } else {
                    tbodyResultado.innerHTML = '<tr><td colspan="5">No se encontraron usuarios</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error al cargar usuarios:', error);
            });
    }

    btnCancelar.addEventListener('click', () => {
        form.reset();
        accionInput.value = 'registrar';
        btnRegistrar.value = 'Registrar';

        inputCedula.removeAttribute('readonly');
        inputCorreo.removeAttribute('readonly');
        inputClave.removeAttribute('readonly');

        estadoSelect.disabled = true;
        estadoSelect.value = 'activo';
        estadoHidden.value = 'activo';
        inputClave.disabled = false;

    });

    cargarUsuarios();
});