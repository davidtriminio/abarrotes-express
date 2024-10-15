<?php
$departamentos = json_decode(file_get_contents(resource_path('data/departamentos.json')), true);
$municipios = json_decode(file_get_contents(resource_path('data/municipios.json')), true);

/*Inicializar las opciones y almacenar los departamentos y municipios en opciones y arreglos*/
$departamentoOpciones = '';
foreach ($departamentos['departamentos'] as $key => $value) {
    $departamentoOpciones .= "<option value=\"$key\">$value</option>";
}

$municipioOpciones = '<option value="">Seleccione un municipio</option>';
foreach ($municipios['municipios'] as $departamento => $mun) {
    foreach ($mun as $key => $value) {
        $municipioOpciones .= "<option class=\"municipio-option $departamento\" data-departamento=\"$departamento\" value=\"$key\">$value</option>";
    }
}
?>
<div>
    <div class="container mx-auto bg-gradient-to-r from-blue-50 to-blue-100 p-6">
        <h1 class="mb-6 text-3xl font-bold text-blue-600">Proceder al pago</h1>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-2">
            <!-- Columna izquierda: Detalles del envío y pago -->
            <div class="space-y-6 md:grid-cols-1 lg:grid-cols-1">
                <form wire:submit.prevent="realizarPedido">
                    <div class="rounded-lg border-2 border-blue-200 bg-white shadow-md">
                        <div class="rounded-t-lg bg-blue-100 px-6 py-4">
                            <h2 class="text-xl font-semibold text-blue-700">Detalles del envío</h2>
                        </div>
                        <div class="space-y-4 p-6">
                            {{--Nombres y apellidos--}}
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label for="nombres" class="block text-sm font-medium text-blue-700">Nombres</label>
                                    <input wire:model="nombres" type="text" id="nombres" name="nombres" maxlength="100"
                                           class="w-full rounded-md border @error('nombres') border-red-500 @enderror border-blue-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           oninput="updateCounter('nombres', 'counterNombres', 100)"
                                           value="{{old('nombres')}}"/>
                                    <p id="counterNombres" class="text-sm text-blue-600">100/100 caracteres
                                        restantes</p>
                                    @error('nombres')
                                    <p class=" text-xs text-red-600 mt-2" id="nombres-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="apellidos"
                                           class="block text-sm font-medium text-blue-700">Apellidos</label>
                                    <input wire:model="apellidos" type="text" id="apellidos" name="apellidos"
                                           maxlength="100"
                                           class="w-full rounded-md border @error('apellidos') border-red-500 @enderror border-blue-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           oninput="updateCounter('apellidos', 'counterApellidos', 100)"
                                           value="{{old('apellidos')}}"/>
                                    <p id="counterApellidos" class="text-sm text-blue-600">100/100 caracteres
                                        restantes</p>
                                    @error('apellidos')
                                    <p class=" text-xs text-red-600 mt-2" id="apellidos-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            {{--Telefono--}}
                            <div class="space-y-2">
                                <label for="telefono" class="block text-sm font-medium text-blue-700">Teléfono</label>
                                <input wire:model="telefono" type="tel" id="telefono" name="telefono" maxlength="8"
                                       minlength="8"
                                       class="w-full rounded-md border @error('telefono') border-red-500 @enderror border-blue-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       oninput="updateCounter('telefono', 'counterTelefono', 8)"
                                       value="{{old('telefono')}}"/>
                                <p id="counterTelefono" class="text-sm text-blue-600">8/8 caracteres restantes</p>
                                @error('telefono')
                                <p class=" text-xs text-red-600 mt-2" id="telefono-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label for="departamento"
                                       class="block text-sm font-medium text-blue-700">Departamento</label>
                                <select wire:model="departamento" id="departamento"
                                        class="w-full rounded-md border @error('departamento') border-red-500 @enderror border-blue-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Seleccione un departamento</option>
                                        <?php echo $departamentoOpciones; ?>
                                </select>
                                @error('departamento')
                                <p class=" text-xs text-red-600 mt-2" id="departamento-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label for="municipio" class="block text-sm font-medium text-blue-700">Municipio</label>
                                <select wire:model="municipio" id="municipio"
                                        class="w-full rounded-md border @error('municipio') border-red-500 @enderror border-blue-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <?php echo $municipioOpciones; ?>
                                </select>
                                @error('municipio')
                                <p class=" text-xs text-red-600 mt-2" id="municipio-error">{{ $message }}</p>
                                @enderror
                            </div>
                            {{--Ciudad y dirección completa--}}
                            <div class="space-y-2">
                                <label for="ciudad" class="block text-sm font-medium text-blue-700">Ciudad</label>
                                <input wire:model="ciudad" type="text" id="ciudad" name="ciudad" maxlength="100"
                                       class="w-full rounded-md border @error('ciudad') border-red-500 @enderror border-blue-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       oninput="updateCounter('ciudad', 'counterCiudad', 100)"
                                       value="{{old('ciudad')}}"/>
                                <p id="counterCiudad" class="text-sm text-blue-600">100/100 caracteres restantes</p>
                                @error('ciudad')
                                <p class=" text-xs text-red-600 mt-2" id="ciudad-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="direccion" class="block text-sm font-medium text-blue-700">Dirección
                                    completa</label>
                                <textarea wire:model="direccion_completa" id="direccion" name="direccion"
                                          maxlength="300" rows="7"
                                          class="w-full resize-none rounded-md border @error('direccion_completa') border-red-500 @enderror border-blue-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                          oninput="updateCounter('direccion', 'counterDireccion', 300)"
                                          value="{{old('direccion_completa')}}"></textarea>
                                <p id="counterDireccion" class="text-sm text-blue-600">300/300 caracteres restantes</p>
                                @error('direccion_completa')
                                <p class=" text-xs text-red-600 mt-2" id="direccion_completa-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Columna derecha: Resumen del carrito -->
            <div class="md:grid-cols-1 lg:grid-cols-1">
                <!-- Metodo de pago -->
                <div class="rounded-lg border-2 border-blue-200 bg-white shadow-md">
                    <div class="rounded-t-lg bg-blue-100 px-6 py-4">
                        <h2 class="text-xl font-semibold text-blue-700">Método de pago</h2>
                    </div>
                    <div class="p-6">
                        <label for="metodo-pago" class="block text-sm font-medium text-blue-700">Método de pago.</label>
                        <select wire:model="metodo_pago" id="metodo-pago"
                                class="w-full rounded-md border @error('metodo_pago') border-red-500 @enderror border-blue-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccione un método de pago</option>
                            <option value="tarjeta">Tarjeta de crédito/débito</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="par">Pago al recibir</option>
                        </select>
                        @error('metodo_pago')
                        <p class=" text-xs text-red-600 mt-2" id="metodo_pago-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="rounded-lg border-2 border-blue-200 bg-white shadow-md">
                    <div class="rounded-t-lg bg-blue-100 px-6 py-4">
                        <h2 class="text-xl font-semibold text-blue-700">Resumen del pedido</h2>
                    </div>
                    <div class="space-y-4 p-6">
                        @foreach($elementos_carrito as $key => $item)
                            <div
                                class="flex items-center space-x-4 @if($item['en_oferta']) rounded-md bg-green-50 p-2 @endif">
                                <div
                                    class="h-16 w-16 overflow-hidden">
                                    <img
                                        src="{{isset($item['imagen']) ? (url( 'storage', $item['imagen'])) : url(asset('imagen/no-photo.jpg'))}}"
                                        alt="{{$item['nombre']}}"
                                        class="h-full w-full object-cover"/>
                                </div>
                                <div class="flex-grow">
                                    <h3 class="font-medium text-blue-700">{{$item['nombre']}} @if($item['en_oferta'])
                                            (Oferta)
                                        @endif</h3>
                                    <p class="text-blue-600"> {{Number::currency($item['monto_total'],  'lps.')}}
                                        {{--@if($item['en_oferta'])
                                            <span class="text-gray-500 line-through">LPS 40.00</span>
                                        @endif--}}
                                    </p>
                                </div>
                                @if($item['en_oferta'])
                                    <div class="rounded bg-green-500 px-2 py-1 text-xs font-bold text-white">
                                        -{{ number_format($item['porcentaje_oferta'], 2, '.', ',') }}%
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        {{--Detalles de la compra--}}
                        <div class="border-t border-blue-200 pt-4">
                            <div class="flex justify-between text-blue-700">
                                <span>Subtotal</span>
                                <span>{{Number::currency($total_final, 'lps')}}</span>
                            </div>
                            <div class="flex justify-between text-red-600">
                                <span>Descuentos</span>
                                <span>-</span>
                            </div>
                            <div class="mt-4 flex justify-between text-lg font-bold text-blue-800">
                                <span>Total</span>
                                <span>{{Number::currency($total_final, 'lps')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-b-lg bg-gray-50 px-6 py-4">
                        <button wire:click="realizarPedido"
                                class="w-full rounded-md bg-blue-600 px-4 py-2 font-semibold text-white transition duration-200 hover:bg-blue-700">
                            <span wire:loading.remove>Realizar pedido</span> <span wire:loading>Ordenando</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
