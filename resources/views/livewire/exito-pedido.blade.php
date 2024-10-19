@if($orden)
    <div class="bg-green-200">
        <div class="my-5 bg-green-200 py-4">
            <div class="max-w-4xl mx-auto">
                <main class="flex flex-col md:flex-row gap-4">
                    <div class="flex-grow bg-green-500 text-white p-8 rounded-lg shadow">
                        <div class="flex justify-center mb-6">
                            <span class="icon-[material-symbols--check-circle] h-20 w-20"></span>
                        </div>
                        <h1 class="text-3xl font-bold text-center mb-2">GRACIAS</h1>
                        <h2 class="text-2xl font-semibold text-center mb-4">SU ORDEN HA SIDO CONFIRMADA</h2>
                        <p class="text-center mb-8">
                            Se enviará un correo de confirmación a <span
                                class="font-black text-blue-800">{{auth()->user()->email}}</span> dentro de poco.
                        </p>
                        {{--Estado de la orden--}}
                        <div class="bg-white text-gray-800 p-6 rounded-lg">
                            <div class="flex justify-between items-center mb-4">
                                <div class="bg-white text-gray-800 p-6 rounded-lg">
                                    <p class="mb-4">
                                        Order #{{$orden->id}} fue realizada {{$orden->created_at->format('d/m/y H:i')}}
                                        y se
                                        encuentra en estado {{$orden->estado_entrega}}
                                    </p>
                                    <div class="flex justify-between items-center mb-4">
                                        <!-- Orden Confirmada -->
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="rounded-full p-2 mb-2">
                                            <span
                                                class="icon-[material-symbols--check-circle-rounded] h-16 w-16 @if(in_array($orden->estado_entrega, ['nuevo', 'procesado', 'enviado', 'entregado']))text-green-500 @endif text-gray-200"></span>
                                            </div>
                                            <span class="text-xs text-center">ORDEN CONFIRMADA</span>
                                        </div>

                                        <!-- Conexión entre estados -->
                                        <div
                                            class="flex-grow h-1 @if(in_array($orden->estado_entrega, ['procesado', 'enviado', 'entregado'])) bg-green-500 @endif bg-gray-300 mx-2"></div>

                                        <!-- Orden Procesada -->
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="rounded-full p-2 mb-2">
                                            <span
                                                class="icon-[material-symbols--check-circle-rounded] h-16 w-16 @if(in_array($orden->estado_entrega, [ 'procesado', 'enviado', 'entregado']))text-green-500 @endif text-gray-200"></span>
                                            </div>
                                            <span class="text-xs text-center">ORDEN PROCESADA</span>
                                        </div>

                                        <!-- Conexión entre estados -->
                                        <div
                                            class="flex-grow h-1 mx-2"></div>

                                        <!-- Orden Enviada -->
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="rounded-full p-2 mb-2">
                                            <span
                                                class="icon-[material-symbols--check-circle-rounded] h-16 w-16 @if(in_array($orden->estado_entrega, [ 'enviado', 'entregado']))text-green-500 @endif text-gray-200"></span>
                                            </div>
                                            <span class="text-xs text-center">ORDEN ENVIADA</span>
                                        </div>

                                        <!-- Conexión entre estados -->
                                        <div
                                            class="flex-grow h-1 mx-2"></div>

                                        <!-- Orden Entregada -->
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="rounded-full p-2 mb-2">
                                            <span
                                                class="icon-[material-symbols--check-circle-rounded] h-16 w-16 @if(in_array($orden->estado_entrega, ['entregado']))text-green-500 @endif text-gray-200"></span>
                                            </div>
                                            <span class="text-xs text-center">ORDEN ENTREGADA</span>
                                        </div>
                                    </div>

                                    <!-- Estado Cancelado -->
                                    @if($orden->estado_entrega == 'cancelado')
                                        <div class="bg-red-500 text-white p-4 rounded-lg mt-4 flex items-center">
                                        <span
                                            class="bg-white mr-1 text-white h-10 w-10 icon-[flowbite--x-circle-solid]"></span>
                                            <span>ORDEN CANCELADA</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        {{--Fin estado de la orden--}}{{--Estado de la orden--}}
                    </div>
                </main>
            </div>
        </div>
        <!-- /.container -->
    </div>
@else
    <div class="bg-red-500 my-5 py-4">
        <div class="my-5 py-4">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-3xl font-bold text-center mb-2">No se encontró ningún pedido.</h1>
            </div>
        </div>
    </div>
@endif
