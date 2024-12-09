<div>
    <div class="container mx-auto px-4 py-8">
        <div class="space-y-8 py-4">
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-6">
                    <h3 class="text-xl font-bold">Mis Ordenes</h3>
                    <button onclick="printTable()"
                            class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-400">
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
                            @forelse($ordenes as $orden)
                                @php
                                    $estado_orden = '';
                                     if($orden->estado_entrega == 'nuevo'){
                                         $estado_orden = '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-nuevo">
                                        <span class="mr-1 icon-[ph--sparkle]"></span>
                                        Nuevo
                                    </span>';
                                     } elseif ($orden->estado_entrega == 'procesado'){
                                         $estado_orden = '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-en-proceso">
                                        <span class="mr-1 icon-[uim--process]"></span>
                                        En Proceso
                                    </span>';
                                     } elseif ($orden->estado_entrega == 'enviado'){
                                        $estado_orden = '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-enviado">
                                        <span class="mr-1 icon-[bi--truck]"></span>
                                        Enviado
                                    </span>';
                                     } elseif ($orden->estado_entrega == 'entregado'){
                                         $estado_orden = '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-entregado">
                                        <span class="mr-1 icon-[bi--check-circle]"></span>
                                        Entregado
                                    </span>';

                                     } else{
                                         $estado_orden = '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-error">
                                        <span class="mr-1 icon-[teenyicons--exclamation-circle-outline]"></span>
                                        cancelado
                                    </span>';
                                     }


                                    $metodos_pago = '';
                                     if($orden->metodo_pago == 'efectivo'){
                                         $metodos_pago = '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-efectivo">
                                        <span class="mr-1 icon-[teenyicons--money-stack-outline]"></span>
                                        Efectivo
                                    </span>';
                                     } elseif ($orden->metodo_pago == 'tarjeta'){
                                         $metodos_pago = '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-tarjeta">
                                        <span class="mr-1 icon-[ion--card-outline]"></span>
                                        Tarjeta
                                    </span>';
                                     } elseif ($orden->metodo_pago == 'par'){
                                         $metodos_pago = '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-pago-al-recibir">
                                        <span class="mr-1 icon-[teenyicons--shop-outline]"></span>
                                        Pago al Recibir
                                    </span>';
                                     }

                                     $estado_pago = '';
                                     if($orden->estado_pago == 'fallo'){
                                         $estado_pago = '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-error">
                                        <span class="mr-1 icon-[teenyicons--exclamation-circle-outline]"></span>
                                        Error
                                    </span>';
                                     } elseif ($orden->estado_pago == 'pendiente'){
                                         $estado_pago = '<span class="bg-pagado inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-pagado">
                                         <span class="bg-orange-700 py-1 px-3 rounded text-white shadow">Pendiente</span>
                                         </span>';
                                     } else {
                                         $estado_pago = ' <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-pagado">
                                        <span class="mr-1 icon-[material-symbols--check-circle-outline]"></span>
                                        Pagado
                                    </span>';
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
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">{{$orden->id}}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $orden->total_final }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{!! $metodos_pago !!}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{!! $estado_pago !!}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{!! $estado_orden !!}</td>
                                    <td class="no-print" class="p-4 align-middle [&:has([role=checkbox])]:pr-0 text-right">
                                        <a href="{{ route('mi_orden', ['id' => $orden->id]) }}"
                                           class="bg-tarjeta text-white py-2 px-4 rounded-md hover:bg-blue-500">Ver
                                            Orden</a>
                                    </td>
                                </tr>
                            @empty
                                      <p>No hay ordenes aun hecha</p>
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if ($ordenes->isNotEmpty())
    <div class="mt-4 text-right"> <!-- Alinea a la derecha -->
        <nav aria-label="Page navigation">
            <ul class="flex justify-center space-x-2"> <!-- Flexbox para alinear los números -->
                @if ($ordenes->onFirstPage())
                    <li class="disabled"><span class="px-4 py-2 bg-gray-300 text-gray-500 cursor-not-allowed">Anterior</span></li>
                @else
                    <li><a href="{{ $ordenes->previousPageUrl() }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Anterior</a></li>
                @endif

                @for ($i = 1; $i <= $ordenes ->lastPage(); $i++)
                    <li>
                        @if ($i == $ordenes->currentPage())
                            <span class="px-4 py-2 bg-blue-500 text-white rounded">{{ $i }}</span> <!-- Página actual -->
                        @else
                            <a href="{{ $ordenes->url($i) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-blue-500 hover:text-white">{{ $i }}</a>
                        @endif
                    </li>
                @endfor

                @if ($ordenes->hasMorePages())
                    <li><a href="{{ $ordenes->nextPageUrl() }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Siguiente</a></li>
                @else
                    <li class="disabled"><span class="px-4 py-2 bg-gray-300 text-gray-500 cursor-not-allowed">Siguiente</span></li>
                @endif
            </ul>
        </nav>
    </div>
@endif
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

