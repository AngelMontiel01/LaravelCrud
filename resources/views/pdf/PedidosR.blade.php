<!DOCTYPE html>
<html>

<head>
    <title>Lista de Pedidos Rechazados</title>
    <style>
        @page {
            margin: 0.5cm 0.5cm 0cm 0cm;
        }

        table {
            width: 70%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        #header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            width: 100%;
            background: white;
        }

        .imgHeader {
            float: left;
            width: 3cm;
        }

        .infoHeader {
            float: left;
            margin-left: 1cm;
        }

        #footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            width: 100%;
            height: 1.5cm; 
            background-color: rgb(219, 65, 44);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .textFooter {
            text-align: center;
            width: 100%;
            color: white;
        }

        .hijo {
            width: 2cm;
            height: 1cm;
            margin: 0.2cm;
            background-color: rgb(78, 16, 16);
        }

       
        #cajatabla {
            margin-top: 9cm; 
            width: 80%;
            float: right;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 6cm;
        }
    </style>
</head>

<body>
    <div id="header">
        <img class="imgHeader" src="https://store-images.s-microsoft.com/image/apps.31887.13527552335205219.79bdc359-aeae-43cd-ac4a-a9b8c2321785.972ea833-efd0-4edd-b0dc-20ea591f449f"
            alt="">
        <div class="infoHeader">
            <h1>Reporte de pedidos</h1>
            <p>Movimientos</p>
        </div>
    </div>

    <div id="cajatabla">
      
        <table>
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Cantidad</th>
                    <th>Cliente</th>
                    <th>Comercial</th>
                    <th>Estatus</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $pedido)
                <tr>
                    <td>{{ $pedido['id'] }}</td>
                    <td>{{ $pedido['cantidad'] }}</td>
                    <td>{{ $pedido['cliente'] }}</td>
                    <td>{{ $pedido['comercial'] }}</td>
                    <td>{{ $pedido['estatus'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="footer">
        <p class="textFooter">Reporte</p>
    </div>

    <div class="container">
        @for($i=0; $i<10; $i++)
        <div class="hijo"></div>
        @endfor
    </div>
</body>

</html>
