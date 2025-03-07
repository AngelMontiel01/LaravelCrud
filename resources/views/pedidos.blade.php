@extends('layouts.navbarm')

@section('content')
    <h2 class="mb-3">Gestión de Pedidos</h2>

    <!-- Formulario para Crear Pedido -->
    <div class="card mb-4">
        <div class="card-header">Nuevo Pedido</div>
        <div class="card-body">
            <form id="pedidoForm">
                <div class="row">
                    <div class="col-md-3">
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" id="cantidad" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label for="cliente_id" class="form-label">Cliente</label>
                        <select id="cliente_id" class="form-select" required>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="comercial_id" class="form-label">Comercial</label>
                        <select id="comercial_id" class="form-select" required></select>
                    </div>
                    <div class="col-md-3">
                        <label for="estatus_id" class="form-label">Estatus</label>
                        <select id="estatus_id" class="form-select" required></select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3" id="submitBtn">Crear Pedido</button>

            </form>
        </div>
    </div>

    <!-- Tabla de Pedidos -->
    <div class="card">
        <div class="card-header">Lista de Pedidos <a href="{{ route('descargar.pdf') }}" class="btn btn-primary">Descargar PDF</a>
        </div>
        <div class="card-body">
            <table id="pedidosTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cantidad</th>
                        <th>Cliente</th>
                        <th>Comercial</th>
                        <th>Estatus</th>
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


            cargarPedidos();
            cargarClientes();
            cargarComerciales();
            cargarEstatus();

            // Manejo del formulario de creación de pedido
            $('#pedidoForm').submit(function (e) {
                e.preventDefault();
                if ($('#pedidoForm').data('pedido-id')) {
                    actualizarPedido();
                } else {
                    insertarPedido();
                }
            });
        });
        function cargarPedidos() {
            $.ajax({
                url: '/pedidos',
                method: 'GET',
                success: function (data) {
                    let tableBody = $('#pedidosTable tbody');
                    tableBody.empty();
                    data.data.forEach(function (pedido) {
                        let row = `
                                                                 <tr>
                                                                     <td>${pedido.id}</td>
                                                                     <td>${pedido.cantidad}</td>
                                                                     <td>${pedido.cliente}</td>
                                                                     <td>${pedido.comercial}</td>
                                                                     <td>${pedido.estatus}</td>
                                                                     <td>
                                                                         <button class="btn btn-warning btn-sm btnEdit" onclick="obtenerPedido(${pedido.id})">Editar</button>
                                                                         <button class="btn btn-danger btn-sm btnDelete" onclick="eliminarPedido(${pedido.id})">Eliminar</button>
                                                                     </td>
                                                                 </tr>
                                                             `;
                        tableBody.append(row);
                    });
                    $('#pedidosTable').DataTable();

                    elementosRol();
                    Editar();

                },
                error: function (xhr, status, error) {
                    if (xhr.status === 403) {
                        alert("No tienes permisos para acceder a los pedidos.");

                    } else {
                        console.error("Error en la solicitud:", error);
                        alert("Hubo un error al cargar los pedidos.");
                    }
                }
            });
        }
        function obtenerPedido(id) {
            fetch(`/pedidos/${id}`)
                .then(res => res.json())
                .then(data => {

                    $('#cantidad').val(data.data.cantidad);
                    $('#cliente_id').val(data.data.cliente_id);
                    $('#comercial_id').val(data.data.comercial_id);
                    $('#estatus_id').val(data.data.estatus_id);
                    $('#pedidoForm').data('pedido-id', id);
                    $('#submitBtn').text('Actualizar Pedido');
                    Editar();
                })
                .catch(() => alert("Error al obtener el pedido"));
        }

        function insertarPedido() {
            var client = $('#cliente_id').val();
            var comercial = $('#comercial_id').val();

         
            if (client === '0' || client === '') {
                alert('Por favor, selecciona un valor diferente a 0 para el cliente.');
                $('#cliente_id').focus();
                return; 
            }

            if (comercial === '0' || comercial === '') {
                alert('Por favor, selecciona un valor diferente a 0 para el comercial.');
                $('#comercial_id').focus(); 
                return; 
            }

            let data = {
                cantidad: $('#cantidad').val(),
                cliente_id: $('#cliente_id').val(),
                comercial_id: $('#comercial_id').val(),
                estatus_id: $('#estatus_id').val()
            };

            $.ajax({
                url: '/pedidos',
                method: 'POST',
                data: JSON.stringify(data),  // Enviar los datos como JSON
                contentType: 'application/json',  // Indicar el tipo de contenido como JSON
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    alert(response.message);
                    if (response.success) {
                        $('#pedidoForm')[0].reset();
                        cargarPedidos();
                    }
                },
                error: function (xhr, status, error) {
                    if (xhr.status === 403) {
                        alert("No tienes permisos para insertar en los pedidos.");
                    } else {
                        console.error("Error en la solicitud:", error);
                        alert("Hubo un error al insertar el pedido.");
                    }
                }
            });
        }



        function actualizarPedido() {
            let id = $('#pedidoForm').data('pedido-id');
            if (!id) return alert("Selecciona un pedido primero");

            var client = $('#cliente_id').val();
            var comercial = $('#comercial_id').val();

         
            if (client === '0' || client === '') {
                alert('Por favor, selecciona un valor diferente a 0 para el cliente.');
                $('#cliente_id').focus();
                return; 
            }

            if (comercial === '0' || comercial === '') {
                alert('Por favor, selecciona un valor diferente a 0 para el comercial.');
                $('#comercial_id').focus(); 
                return; 
            }
            let data = {
                cantidad: $('#cantidad').val(),
                cliente_id: $('#cliente_id').val(),
                comercial_id: $('#comercial_id').val(),
                estatus_id: $('#estatus_id').val()
            };

            $.ajax({
                url: `/pedidos/${id}`,
                method: 'PUT',
                data: JSON.stringify(data),  // Enviar los datos como JSON
                contentType: 'application/json',  // Indicar el tipo de contenido como JSON
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    alert(response.message);
                    if (response.success) {
                        $('#pedidoForm')[0].reset();
                        cargarPedidos();
                        $('#submitBtn').text('Crear Pedido'); // Resetear el botón después de la actualización
                        $('#pedidoForm').removeData('pedido-id'); // Limpiar el ID del formulario
                    }
                },
                error: function (xhr, status, error) {
                    if (xhr.status === 403) {
                        alert("No tienes permisos para actualizar este pedido.");
                    } else {
                        console.error("Error en la solicitud:", error);
                        alert("Hubo un error al actualizar el pedido.");
                    }
                }
            });
        }

        function eliminarPedido(id) {
            if (!confirm('¿Seguro que deseas eliminar este pedido?')) return;

            $.ajax({
                url: `/pedidos/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Agregando el token CSRF
                },
                success: function (response) {
                    alert(response.message);
                    if (response.success) {
                        cargarPedidos();
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error en la solicitud:", error);
                    alert("Hubo un error al eliminar el pedido.");
                }
            });
        }



        function cargarClientes() {
            fetch('/clientes')
                .then(res => res.json())
                .then(data => {
                    const clientes = data.data || [];
                    $('#cliente_id').empty();
                    $('#cliente_id').append('<option value="0">Selecciona un Cliente</option>');
                    if (clientes.length === 0) {
                    } else {
                        clientes.forEach(cliente => {
                            $('#cliente_id').append(`<option value="${cliente.id}">${cliente.nombre}</option>`);
                        });
                    }
                })
                .catch(() => alert("Error al cargar los clientes"));
        }

        function cargarComerciales() {
            fetch('/comerciales')
                .then(res => res.json())
                .then(data => {
                    const clientes = data.data || [];
                    $('#comercial_id').empty();
                    $('#comercial_id').append('<option value="0">Selecciona un Comercial</option>');
                    if (clientes.length === 0) {
                    } else {
                        clientes.forEach(comercial => {
                            $('#comercial_id').append(`<option value="${comercial.id}">${comercial.nombre}</option>`);
                        });
                    }
                })
                .catch(() => alert("Error al cargar los comerciales"));
        }

        function cargarEstatus() {
            fetch('/estatus')
                .then(res => res.json())
                .then(data => {
                    $('#estatus_id').empty();
                    data.forEach(estatus => {
                        $('#estatus_id').append(`<option value="${estatus.id}">${estatus.nombre}</option>`);
                    });
                });
        }


        function elementosRol() {
            if (userRole == 3) {
                $('.btnDelete').prop('disabled', true);
                $('.btnEdit').prop('disabled', true);
                $('#submitBtn').prop('disabled', true);
                $('#cantidad, #cliente_id, #comercial_id, #estatus_id').prop('disabled', true);
            }
        }

        function Editar() {
            if (userRole == 2) {

                $('.btnDelete').hide();
                $('#cantidad, #cliente_id, #comercial_id').prop('disabled', true);
                $('#estatus_id').prop('disabled', false);

                if ($('#pedidoForm').data('pedido-id')) {
                    $('#submitBtn').show().text('Actualizar Estatus');
                    $('.btnDelete').hide();
                } else {
                    $('#submitBtn').hide();
                }
            }
        }

    </script>


@endsection