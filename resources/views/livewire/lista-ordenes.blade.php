<div>
    <div class="container mx-auto px-4 py-8">
        <div class="space-y-8 py-4">
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-6">
                    <h3 class="text-xl font-bold">Mis Ordenes</h3>
                    <button onclick="printTable()" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-400">
                        Imprimir Ordenes
                    </button>
                </div>
                <div class="p-6 pt-0">
                    <div id="orderTable" class="relative w-full overflow-auto">
                        <table class="w-full caption-bottom text-sm">
                            <thead class="[&_tr]:border-b">
                            <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">
                                    ID de orden
                                </th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">
                                    Total final
                                </th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">
                                    Método pago
                                </th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">
                                    Estado pago
                                </th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">
                                    Estado entrega
                                </th>
                                <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">
                                    Acción
                                </th>
                            </tr>
                            </thead>

                            <tbody class="[&_tr:last-child]:border-0">
                            @foreach($ordenes as $orden)
                            @php
                                $estado_orden = '';
                                 if($orden->estado_entrega == 'nuevo'){
                                     $estado_orden = '<span class="mr-1 icon-[ph--sparkle]">Nuevo</span>';
                                 } elseif ($orden->estado_entrega == 'procesado'){
                                     $estado_orden = '<span class="mr-1 icon-[uim--process]">En Proceso</span>';
                                 } elseif ($orden->estado_entrega == 'enviado'){
                                     $estado_orden = '<span class="mr-1 icon-[bi--truck]">Enviado</span>';
                                 } elseif ($orden->estado_entrega == 'entregado'){
                                     $estado_orden = '<span class="mr-1 icon-[carbon--box]">Entregado</span>';
                                 } else{
                                     $estado_orden = '<span class=".bg-cancelado">Cancelado </span>';
                                 }

                                
                                $metodos_pago = '';
                                 if($orden->metodo_pago == 'efectivo'){
                                     $metodos_pago = '<span class="mr-1 icon-[ion--card-outline]">efectivo</span>';
                                 } elseif ($orden->metodo_pago == 'tarjeta'){
                                     $metodos_pago = '<span class="mr-1 icon-[teenyicons--money-stack-outline]">Tarjeta de crédito o débito</span>';
                                 } elseif ($orden->metodo_pago == 'par'){
                                     $metodos_pago = '<span class="mr-1 icon-[teenyicons--shop-outline]">Pago al Recibir</span>';
                                 } 

                                 $estado_pago = '';
                                 if($orden->estado_pago == 'fallo'){
                                     $estado_pago = '<span class="mr-1 icon-[teenyicons--exclamation-circle-outline]">Falló</span>';
                                 } elseif ($orden->estado_pago == 'pendiente'){
                                     $estado_pago = '<span class="bg-orange-700 py-1 px-3 rounded text-white shadow">Pendiente</span>';
                                 } else {
                                     $estado_pago = '<span class="mr-1 icon-[uim--process]">Pagado</span>';
                                 }

                                 $moneda = '';
                                 if ($orden -> moneda == 'usd'){
                                     $moneda = 'usd';
                                  } elseif ($orden -> moneda == 'lps'){
                                      $moneda = 'lps';
                                  } else{
                                       $moneda = 'eur';
                                  }
                            @endphp
                            <tr wire:key="{{$orden -> id}}"
                                class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-900 dark:even:bg-slate-800">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{$orden->id}}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $orden->total_final }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{!! $metodos_pago !!}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{!! $estado_pago !!}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{!! $estado_orden !!}</td>
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 text-right">
                                    <a href="{{ route('mi_orden', ['id' => $orden->id]) }}"
                                       class="bg-slate-600 text-white py-2 px-4 rounded-md hover:bg-slate-500">Ver Orden</a>
                                </td>
                            </tr>
                        @endforeach
                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function printTable() {
    var printContents = document.getElementById('orderTable').innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    }
    </script>
</div>

