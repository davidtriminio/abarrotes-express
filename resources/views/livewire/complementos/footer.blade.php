<div>
    <!-- Footer -->
    <footer class="border-t border-gray-line mt-16">

    <!-- Top part -->
        <div class="container mx-auto px-4 py-10">
            <div class="flex flex-wrap place-content-between mx-4 w-full">
                <!-- Menu 2 -->
                <div class="lg:w-1/4 sm:w-1/3 px-4 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Paginas</h3>
                    <ul>
                        <li class="mb-2"><a href="{{ route('inicio') }}" class="hover:text-primary hover:underline"
                                            style="transition: color 0.3s ease;">Inicio</a></li>
                        <li class="mb-2"><a href="{{ route('categorias') }}" class="hover:text-primary hover:underline"
                                            style="transition: color 0.3s ease;">Categorias</a></li>
                        <li class="mb-2"><a href="{{ route('marcas') }}" class="hover:text-primary hover:underline"
                                            style="transition: color 0.3s ease;">Marcas</a></li>
                        <li class="mb-2"><a href="{{ route('productos') }}" class="hover:text-primary hover:underline"
                                            style="transition: color 0.3s ease;">Productos</a></li>
                        @auth
                            <li class="mb-2"><a href="{{ route('cupones') }}" class="hover:text-primary hover:underline" style="transition: color 0.3s ease;">Cupones</a></li>
                        @endauth
                    </ul>
                </div>

                <!-- Menu 3 -->
                <div class="lg:w-1/4 sm:w-1/3 px-4 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Cuenta</h3>
                    <ul>
                        <li class="mb-2">
                            <a href="{{ route('carrito') }}" class="hover:text-primary hover:underline"
                               style="transition: color 0.3s ease;">Carrito</a>
                        </li>
                        @guest
                            <li class="mb-2">
                                <a href="{{ route('login') }}" class="hover:text-primary hover:underline" style="transition: color 0.3s ease;">
                                    Iniciar Sesión
                                </a>
                            </li>
                        @endguest

                        @auth
                            <li class="mb-2">
                                <a href="{{ route('ordenes') }}" class="hover:text-primary hover:underline" style="transition: color 0.3s ease;">
                                    Órdenes
                                </a>
                            </li>
                        @endauth

                        @auth
                            <li class="mb-2">
                                <a href="{{ route('perfil') }}" class="hover:text-primary hover:underline" style="transition: color 0.3s ease;">
                                    Perfil
                                </a>
                            </li>
                        @endauth

                        <li class="mb-2">
                            <a href="{{ route('quejasugerencia') }}" class="hover:text-primary hover:underline"
                               style="transition: color 0.3s ease;">Quejas y Sugerencias</a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('reporteproblema') }}" class="hover:text-primary hover:underline"
                               style="transition: color 0.3s ease;">Reportar un problema</a>
                        </li>
                    </ul>
                </div>

                <!-- Social Media -->
                <div class="lg:w-1/4 sm:w-1/3 px-4 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Siguenos</h3>
                    <ul>
                        <li class="flex items-center mb-2">
                            <i class="fab fa-facebook mr-2"></i><a href="#" class="hover:text-primary hover:underline"
                                                                   style="transition: color 0.3s ease;">Facebook</a>
                        </li>
                        <li class="flex items-center mb-2">
                            <i class="fab fa-twitter mr-2"></i><a href="#" class="hover:text-primary hover:underline"
                                                                  style="transition: color 0.3s ease;">Twitter</a>
                        </li>
                        <li class="flex items-center mb-2">
                            <i class="fab fa-instagram mr-2"></i><a href="#" class="hover:text-primary hover:underline"
                                                                    style="transition: color 0.3s ease;">Instagram</a>
                        </li>
                        <li class="flex items-center mb-2">
                            <i class="fab fa-pinterest mr-2"></i><a href="#" class="hover:text-primary hover:underline"
                                                                    style="transition: color 0.3s ease;">Pinterest</a>
                        </li>
                        <li class="flex items-center mb-2">
                            <i class="fab fa-youtube mr-2"></i><a href="#" class="hover:text-primary hover:underline"
                                                                  style="transition: color 0.3s ease;">YouTube</a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Information -->
                <div class="lg:w-1/4 sm:w-full px-4 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Contactanos</h3>
                    <div class="flex items-center mb-4">
                        <img src="/imagen/logo1.jpeg" alt="Logo" width="80px" height="80px" class="rounded-2xl">
                        <p class="ml-4">Barrio El Carmelo, Frente al Marchante N°2, Danlí El Paraíso</p>
                    </div>
                    <p class="text-xl font-bold my-4" style="white-space: nowrap;">Telefono: +504 3333 9999</p>
                    <a href="#"  style="white-space: nowrap;">Email: equipo.abarrotes.express@gmail.com</a>
                </div>
            </div>
        </div>

        <!-- Bottom part -->
        <div class="py-4 border-t border-gray-line">
            <div class="container w-full mx-auto px-4 flex flex-wrap justify-around align-middle">
                <!-- Copyright and Links -->
                <div class="w-full lg:w-3/4 text-center lg:text-center mb-4 lg:mb-0">
                    <p class="mb-2 font-bold">&copy; 2024 Abarrotes Express. Todos los derechos
                        reservados.</p>
                    <ul class="flex justify-center lg:justify-center space-x-4 mb-4 lg:mb-0">
                        <li><a href="#" class="hover:text-primary hover:underline"
                               style="transition: color 0.3s ease;">Politicas de Privacidad</a></li>
                        <li><a href="#" class="hover:text-primary hover:underline"
                               style="transition: color 0.3s ease;">Terminos del Servicio</a></li>
                        <li><a href="#" class="hover:text-primary hover:underline"
                               style="transition: color 0.3s ease;">FAQ</a></li>
                    </ul>
                    <p class="text-sm mt-4">Tu tienda de abarrotes en línea, ofreciendo una amplia variedad
                        de productos de calidad para tu conveniencia.</p>
                </div>
            </div>
        </div>
    </footer>
</div>
