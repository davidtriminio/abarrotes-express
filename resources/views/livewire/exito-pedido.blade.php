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
                    <div class="w-full md:w-96 p-6 bg-white rounded-lg shadow">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold">DETALLES DE LA ORDEN</h3>
                            <span class="text-xl font-bold">#{{$orden->id}}</span>
                        </div>
                        {{--Detalles del enviío>--}}
                        <div class="space-y-6">
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="font-semibold flex items-center">
                                        <span class="icon-[bi--truck] mr-1"></span>
                                        DIRECCIÓN DE ENVÍO
                                    </h4>
                                    {{--<a href="#" class="text-blue-500">Change Details</a>--}}
                                </div>
                                <p class="text-sm uppercase">
                                    @if(isset($orden->direccion->direccion_completa) and isset($orden->direccion->ciudad))
                                        {{$orden->direccion->direccion_completa . ', ' . $orden->direccion->ciudad}}
                                    @else
                                        No hay ninguna dirección
                                    @endif<br>
                                    <span
                                        class="text-green-900 font-bold">@if(isset($orden->direccion->municipio) and isset($orden->direccion->departamento))
                                            {{$orden->direccion->municipio . ', ' . $orden->direccion->departamento}}
                                        @else
                                            No hay ninguna dirección
                                        @endif</span>
                                </p>
                            </div>
                            <div>
                                <h4 class="font-semibold flex items-center mb-2 mr-1">
                                    <span class="icon-[ph--phone]"></span>
                                    DETALLES DE CONTACTO
                                </h4>
                                <p class="text-sm">
                                    @if (auth()->user()->email and isset($orden->direccion->telefono))
                                        {{auth()->user()->email}}<br>
                                        {{$orden->direccion->telefono}}<br>
                                    @else
                                        No hay ningún dato de contacto.
                                    @endif
                                </p>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="font-semibold"><span class="icon-[lets-icons--order] mr-1"></span>RESUMEN DE
                                        LA ORDEN ({{$orden->elementos->count()}})</h4>
                                </div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span>Sub Total</span>
                                    <span>{{Number::currency($orden->sub_total, 'lps')}}</span>
                                </div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span>Envío</span>
                                    <span>LPS 0</span>
                                </div>
                                <div class="flex justify-between font-bold mt-2">
                                    <span>Total</span>
                                    <span>{{Number::currency($orden->total_final, 'lps')}}</span>
                                </div>
                            </div>
                            <button action="click" onclick="location.href='/ordenes'"
                                    class="w-full bg-blue-500 text-white py-2 rounded mb-6">Ver mis ordenes
                            </button>
                        </div>
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
