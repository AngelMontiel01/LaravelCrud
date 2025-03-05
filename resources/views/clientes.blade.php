@extends('layouts.navbarm')

@section('content')

    <h2 class="mb-3">Gestión de Clientes</h2>

    <!-- Formulario para Crear cliente -->
    <div class="card mb-4">
        <div class="card-header">Nuevo Cliente</div>
        <div class="card-body">
            <form id="clienteForm">
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
                        <label for="categoria_id" class="form-label">Categoria:</label>
                        <select id="categoria_id" class="form-select" required></select>
                    </div>

                </div>
                <button type="submit" class="btn btn-primary mt-3" id="submitBtn">Crear Cliente</button>
            </form>
        </div>
    </div>

    <!-- Tabla de clientes -->
    <div class="card">
        <div class="card-header">Lista de Clientes</div>
        <div class="card-body">
            <table id="clientesTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Paterno</th>
                        <th>Materno</th>
                        <th>Ciudad</th>
                        <th>Categoria</th>
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
            cargarClientes();
            cargarCategoria();
            cargarCiudad();
            
            // Manejo del formulario de creación de cliente
            $('#clienteForm').submit(function (e) {
                e.preventDefault();
                if ($('#clienteForm').data('cliente-id')) {
                    actualizaCliente(); // Si hay un ID, actualiza el cliente
                } else {
                    insertarCliente(); // Si no hay ID, crea un nuevo cliente
                }
            });
        });

        // Función para cargar clientes
        function cargarClientes() {
            $.ajax({
                url: '/cliente',
                method: 'GET',
                success: function (data) {
                    let tableBody = $('#clientesTable tbody');
                    tableBody.empty();

                    data.data.forEach(function (cliente) {
                        let row = `
                                    <tr>
                                        <td>${cliente.id}</td>
                                        <td>${cliente.nombre}</td>
                                        <td>${cliente.apellido1}</td>
                                        <td>${cliente.apellido2}</td>
                                        <td>${cliente.ciudad}</td>
                                        <td>${cliente.categoria}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm btnEdit" onclick="obtenerCliente(${cliente.id})">Editar</button>
                                            <button class="btn btn-danger btn-sm btnDelete" onclick="eliminarCliente(${cliente.id})">Eliminar</button>
                                        </td>
                                    </tr>
                                `;
                        tableBody.append(row);
                    });

                    $('#clientesTable').DataTable();
                    elementosRol();
                },
                error: function (xhr, status, error) {
                    console.error("Error en la solicitud:", error);
                    alert("Hubo un error al cargar los clientes.");
                }
            });
        }

        // Obtener cliente por ID para editar
        function obtenerCliente(id) {
            $.ajax({
                url: `/cliente/${id}`,
                method: 'GET',
                success: function (data) {
                    $('#nombre').val(data.data.nombre);
                    $('#paterno').val(data.data.apellido1);
                    $('#materno').val(data.data.apellido2);
                    $('#ciudad_id').val(data.data.ciudad_id);
                    $('#categoria_id').val(data.data.categoria_id);

                    $('#clienteForm').data('cliente-id', id);
                    $('#submitBtn').text('Actualizar Cliente');
                },
                error: function (xhr, status, error) {
                    alert("Error al obtener el Cliente");
                }
            });
        }

        // Insertar un nuevo cliente
        function insertarCliente() {
            let data = {
                nombre: $('#nombre').val(),
                apellido1: $('#paterno').val(),
                apellido2: $('#materno').val(),
                ciudad_id: $('#ciudad_id').val(),
                categoria_id: $('#categoria_id').val()
            };

            $.ajax({
                url: '/cliente',
                method: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    alert(response.message);
                    if (response.success) {
                        $('#clienteForm')[0].reset();
                        cargarClientes();
                    }
                },
                error: function (xhr, status, error) {
                    if (xhr.status === 403) {
                        alert("No tienes permisos para insertar este cliente.");
                    } else {
                        console.error("Error en la solicitud:", error);
                        alert("Hubo un error al actualizar el cliente.");
                    }
                }
            });
        }

        // Actualizar cliente
        function actualizaCliente() {
            let id = $('#clienteForm').data('cliente-id');
            if (!id) return alert("Selecciona un Cliente primero");

            let data = {
                nombre: $('#nombre').val(),
                apellido1: $('#paterno').val(),
                apellido2: $('#materno').val(),
                ciudad_id: $('#ciudad_id').val(),
                categoria_id: $('#categoria_id').val()
            };

            $.ajax({
                url: `/cliente/${id}`,
                method: 'PUT',
                data: JSON.stringify(data),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    alert(response.message);
                    if (response.success) {
                        $('#clienteForm')[0].reset();
                        cargarClientes();
                        $('#submitBtn').text('Crear Cliente');
                        $('#clienteForm').removeData('cliente-id');
                    }
                },
                error: function (xhr, status, error) {
                    if (xhr.status === 403) {
                        alert("No tienes permisos para actualizar este cliente.");
                    } else {
                        console.error("Error en la solicitud:", error);
                        alert("Hubo un error al actualizar el cliente.");
                    }
                }
            });
        }

        // Eliminar cliente
        function eliminarCliente(id) {
            if (!confirm('¿Seguro que deseas eliminar este cliente?')) return;

            $.ajax({
                url: `/cliente/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    alert(response.message);
                    if (response.success) {
                        cargarClientes();
                    }
                },
                error: function (xhr, status, error) {
                    if (xhr.status === 403) {
                        alert("No tienes permisos para eliminar este cliente.");
                    } else {
                        console.error("Error en la solicitud:", error);
                        alert("Hubo un error al actualizar el cliente.");
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

        function cargarCategoria() {
            fetch('/categoria')
                .then(res => res.json())
                .then(data => {
                    $('#categoria_id').empty();
                    data.forEach(cat => {
                        $('#categoria_id').append(`<option value="${cat.id}">${cat.nombre}</option>`);
                    });
                });

        }

        function elementosRol() {
            
            if (userRole == 3 || userRole == 1) {
                $('.btnDelete').prop('disabled', true);
                $('.btnEdit').prop('disabled', true);
                $('#submitBtn').prop('disabled', true);
                $('#nombre, #paterno, #materno,#ciudad_id,#categoria_id').prop('disabled', true);
            }
        }



    </script>

@endsection