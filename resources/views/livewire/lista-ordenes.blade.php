<div>
    <div class="container mx-auto px-4 py-8">
        <div class="space-y-8 py-4">
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-6">
                    <h3 class="text-xl font-bold">Mis Ordenes</h3>
                </div>
                <div class="p-6 pt-0">
                    <div class="relative w-full overflow-auto">
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

                            <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">ORD001</td>
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">232.06</td>
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-tarjeta">
                                    <span class="mr-1 icon-[ion--card-outline]"></span>
                                    Tarjeta
                                </span>
                                </td>
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-procesando">
                                    <span class="mr-1 icon-[uim--process]"></span>
                                    Procesando
                                </span>
                                </td>
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-nuevo">
                                    <span class="mr-1 icon-[ph--sparkle]"></span>
                                    Nuevo
                                </span>
                                </td>
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 text-right">
                                    <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                                        Ver Orden
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">ORD002</td>
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">5.00</td>
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-efectivo">
                                    <span class="mr-1 icon-[teenyicons--money-stack-outline]"></span>
                                    Efectivo
                                </span>
                                </td>
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-error">
                                    <span class="mr-1 icon-[teenyicons--exclamation-circle-outline]"></span>
                                    Error
                                </span>
                                </td>
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-en-proceso">
                                    <span class="mr-1 icon-[uim--process]"></span>
                                    En Proceso
                                </span>
                                </td>
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 text-right">
                                    <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                                        Ver Orden
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">ORD003</td>
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">100.00</td>
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-pago-al-recibir">
                                    <span class="mr-1 icon-[teenyicons--shop-outline]"></span>
                                    Pago al Recibir
                                </span>
                                </td>
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-pagado">
                                    <span class="mr-1 icon-[material-symbols--check-circle-outline]"></span>
                                    Pagado
                                </span>
                                </td>
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-enviado">
                                    <span class="mr-1 icon-[bi--truck]"></span>
                                    Enviado
                                </span>
                                </td>
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 text-right">
                                    <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                                        Ver Orden
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">ORD004</td>
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">57.89</td>
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-efectivo">
                                    <span class="mr-1 icon-[teenyicons--money-stack-outline]"></span>
                                    Efectivo
                                </span>
                                </td>
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-enviado">
                                    <span class="mr-1 icon-[material-symbols--check-circle-outline]"></span>
                                    Pagado
                                </span>
                                </td>
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-entregado">
                                    <span class="mr-1 icon-[carbon--box]"></span>
                                    Entregado
                                </span>
                                </td>
                                <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 text-right">
                                    <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                                        Ver Orden
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

