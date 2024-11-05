<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8" id="ordenDetalles">
    <h2 class="text-4xl font-bold text-gray-800 mb-4 text-center">Detalles de la Orden </h2>

    <div class="bg-white shadow-xl rounded-lg p-8 border border-gray-300">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-300 shadow-md transition-transform transform hover:scale-105">
                <h3 class="text-xl font-semibold text-gray-700 border-b-2 border-blue-500 pb-2">Información del Usuario</h3>
                <p class="mt-2"><strong>Nombre de Usuario:</strong> <span class="text-gray-600">{{ $orden->user->name }}</span></p>
                <p class="mt-2"><strong>Correo:</strong> <span class="text-gray-600">{{ $orden->user->email }}</span></p>
            </div>
            <div class="bg-gray-110 p-6 rounded-lg border border-gray-300 shadow-md transition-transform transform hover:scale-105">
                <h3 class="text-xl font-semibold text-gray-700 border-b-2 border-blue-500 pb-2">Detalles de la Factura</h3>
                <p class="mt-2"><strong>Método de Pago:</strong> <span class="text-gray-600">{{ strpos($orden->metodo_pago, 'par') !== false ? 'Pago a recibir' : $orden->metodo_pago }}</span></p>
                <p class="mt-2"><strong>Estado de Pago:</strong> <span class="text-gray-600">{{ ucfirst($orden->estado_pago) }}</span></p>
                <p class="mt-2"><strong>Estado de Entrega:</strong> <span class="text-gray-600">{{ ucfirst($orden->estado_entrega) }}</span></p>
            </div>
        </div>

        <div class="mt-8">
            <h3 class="text-xl font-semibold text-gray-700 border-b-2 border-blue-500 pb-2">Resumen de la Orden</h3>
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-300 mt-2 shadow-md">
            
                <p><strong>Subtotal:</strong> <span class="text-gray-600">{{ number_format($orden->sub_total, 2) }}</span></p>
                <p><strong>Total Final:</strong> <span class="text-gray-600">{{ number_format($orden->total_final, 2) }}</span></p>
                <p><strong>Costos de Envío:</strong> <span class="text-gray-600">{{ number_format($orden->costos_envio, 2) }}</span></p>
                <p><strong>Fecha de Entrega:</strong> <span class="text-gray-600">{{ $orden->fecha_entrega ? Carbon\Carbon::parse($orden->fecha_entrega)->format('d/m/Y') : 'N/A' }}</span></p>
            </div>
        </div>

        <div class="mt-8">
            <h3 class="text-xl font-semibold text-gray-700 border-b-2 border-blue-500 pb-2">Notas Adicionales</h3>
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-300 mt-2 shadow-md">
                <p class="text-gray-600">{{ $orden->notas }}</p>
            </div>
        </div>
    </div>

    <button onclick="imprimirOrden()" class="mt-4 bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
        Imprimir Orden
    </button>
    <script>
        function imprimirOrden() {
            var contenido = document.getElementById('ordenDetalles').innerHTML;
            var ventanaImpresion = window.open('', '', 'height=600,width=800');
            ventanaImpresion.document.write('<html><head><title>Imprimir Orden</title>');
            ventanaImpresion.document.write('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">');
            ventanaImpresion.document.write('</head><body>');
            ventanaImpresion.document.write(contenido);
            ventanaImpresion.document.write('</body></html>');
            ventanaImpresion.document.close();
            ventanaImpresion.print();
        }
    </script>
</div>







