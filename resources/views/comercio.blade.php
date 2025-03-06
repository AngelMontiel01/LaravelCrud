@extends('layouts.navbarm')

@section('content')

    <h2 class="mb-3">Gestión de Comercial</h2>

    <!-- Formulario para Crear Comercial -->
    <div class="card mb-4">
        <div class="card-header">Nuevo Comercial</div>
        <div class="card-body">
            <form id="comercialForm">
                <div class="row">
                    <div class="col-md-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" id="nombre" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label for="paterno" class="form-label">Paterno:</label>
                        <input type="text" id="paterno" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label for="materno" class="form-label">Materno:</label>
                        <input type="text" id="materno" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label for="ciudad_id" class="form-label">Ciudad</label>
                        <select id="ciudad_id" class="form-select" required></select>
                    </div>
                    <div class="col-md-3">
                        <label for="comision" class="form-label">Comision:</label>
                        <input type="text" id="comision" step="any" class="form-control" required>
                    </div>

                </div>
                <button type="submit" class="btn btn-primary mt-3" id="submitBtn">Crear Comercial</button>

            </form>
        </div>
    </div>

    <!-- Tabla de clientes -->
    <div class="card">
        <div class="card-header">Lista de Comercial</div>
        <div class="card-body">
            <table id="comercialTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Paterno</th>
                        <th>Materno</th>
                        <th>Ciudad</th>
                        <th>Comision</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        const userRole = @json($rol); // El rol del usuario
        $(document).ready(function () {
            cargarComercial();
            cargarCiudad();



            // Manejo del formulario de creación de comercio
            $('#comercialForm').submit(function (e) {
                e.preventDefault();
                if ($('#comercialForm').data('comercial-id')) {
                    actualizaComercial(); // Si hay un ID, actualiza el comercio
                } else {
                    insertarComercial(); // Si no hay ID, crea un nuevo comercio
                }
            });
        });

        function cargarComercial() {

            $.ajax({
                url: '/comercial',
                method: 'GET',
                success: function (data) {

                    let tableBody = $('#comercialTable tbody');
                    tableBody.empty();

                    data.data.forEach(function (comercio) {

                        let row = `
                            <tr>
                                <td>${comercio.id}</td>
                                <td>${comercio.nombre}</td>
                                <td>${comercio.apellido1}</td>
                                <td>${comercio.apellido2}</td>

                                <td>${comercio.ciudad}</td>
                                <td>${comercio.comision}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm btnEdit" onclick="obtenerComercial(${comercio.id})">Editar</button>
                                    <button class="btn btn-danger btn-sm btnDelete" onclick="eliminarComercial(${comercio.id})">Eliminar</button>
                                </td>
                            </tr>
                        `;
                        tableBody.append(row);
                    });
                    // Inicializar DataTable 
                    elementosRol();
                    $('#comercialTable').DataTable();

                },
                error: function (xhr, status, error) {
                    if (xhr.status === 403) {
                        alert("No tienes permisos para acceder a los comerciales.");
                        window.location.href = "/";
                    } else {
                        console.error("Error en la solicitud:", error);
                        alert("Hubo un error al cargar los pedidos.");
                    }
                }
            });
        }


        function obtenerComercial(id) {
            fetch(`/comercial/${id}`)
                .then(res => res.json())
                .then(data => {
                    $('#nombre').val(data.data.nombre);
                    $('#paterno').val(data.data.apellido1);
                    $('#materno').val(data.data.apellido2);
                    $('#ciudad_id').val(data.data.ciudad_id);
                    $('#comision').val(data.data.comision);

                    $('#comercialForm').data('comercial-id', id);
                    $('#submitBtn').text('Actualizar Comercial');
                })
                .catch(() => alert("Error al obtener el Comercial"));
        }


        function insertarComercial() {
            var ciudad = $('#ciudad_id').val();



            if (ciudad === '0' || ciudad === '') {
                alert('Por favor, selecciona un valor diferente a 0 para la ciudad.');
                $('#ciudad_id').focus();
                return;
            }


            let data = {
                nombre: $('#nombre').val(),
                apellido1: $('#paterno').val(),
                apellido2: $('#materno').val(),
                ciudad_id: $('#ciudad_id').val(),
                comision: parseFloat($('#comision').val()) // Convertir a float
            }

            $.ajax({
                url: '/comercial',
                method: 'POST',
                data: JSON.stringify(data),  // Enviar los datos como JSON
                contentType: 'application/json',  // Indicar el tipo de contenido como JSON
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    alert(response.message);
                    if (response.success) {
                        $('#comercialForm')[0].reset();
                        cargarComercial();
                    }
                },
                error: function (xhr, status, error) {
                    if (xhr.status === 403) {
                        alert("No tienes permisos para insertar en los Comerciales.");
                    } else {
                        console.error("Error en la solicitud:", error);
                        alert("Hubo un error al insertar el Comerciales.");
                    }
                }
            });
        }


        function actualizaComercial() {

            let id = $('#comercialForm').data('comercial-id');
            if (!id) return alert("Selecciona un comercial primero");

            var ciudad = $('#ciudad_id').val();



            if (ciudad === '0' || ciudad === '') {
                alert('Por favor, selecciona un valor diferente a 0 para la ciudad.');
                $('#ciudad_id').focus();
                return;
            }
            let data = {
                nombre: $('#nombre').val(),
                apellido1: $('#paterno').val(),
                apellido2: $('#materno').val(),
                ciudad_id: $('#ciudad_id').val(),
                comision: $('#comision').val()
            };

            $.ajax({
                url: `/comercial/${id}`,
                method: 'PUT',
                data: JSON.stringify(data),  // Enviar los datos como JSON
                contentType: 'application/json',  // Indicar el tipo de contenido como JSON
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    alert(response.message);
                    if (response.success) {
                        $('#comercialForm')[0].reset();
                        cargarComercial();
                        $('#submitBtn').text('Crear Pedido'); // Resetear el botón después de la actualización
                        $('#comercialForm').removeData('comercial-id'); // Limpiar el ID del formulario
                    }
                },
                error: function (xhr, status, error) {
                    if (xhr.status === 403) {
                        alert("No tienes permisos para actualizar este comercial.");
                    } else {
                        console.error("Error en la solicitud:", error);
                        alert("Hubo un error al actualizar el pedido.");
                    }
                }
            });
        }



        function eliminarComercial(id) {
            if (!confirm('¿Seguro que deseas eliminar este comercial?')) return;

            $.ajax({
                url: `/comercial/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Agregando el token CSRF
                },
                success: function (response) {
                    alert(response.message);
                    if (response.success) {
                        cargarComercial();
                    }
                },
                error: function (xhr, status, error) {
                    if (xhr.status === 403) {
                        alert("No tienes permisos para eliminar este comercial.");
                    } else {
                        console.error("Error en la solicitud:", error);
                        alert("Hubo un error al actualizar el pedido.");
                    }
                }
            });
        }

        function cargarCiudad() {
            fetch('/ciudad')
                .then(res => res.json())
                .then(data => {
                    $('#ciudad_id').empty();
                    data.forEach(cdad => {
                        $('#ciudad_id').append(`<option value="${cdad.id}">${cdad.nombre}</option>`);
                    });
                });

        }

        function elementosRol() {

            if (userRole == 3 || userRole == 1) {
                $('.btnDelete').prop('disabled', true);
                $('.btnEdit').prop('disabled', true);
                $('#submitBtn').prop('disabled', true);
                $('#nombre, #paterno, #materno,#ciudad_id,#comision').prop('disabled', true);
            }
        }

    </script>

@endsection