{{--Navbar--}}
<header class="flex z-50 sticky top-0 flex-wrap md:justify-start md:flex-nowrap w-full bg-slate-800 text-sm py-0.5 md:py-0.5 dark:bg-gray-800 shadow-md">
    <nav class="max-w-[85rem] w-full mx-auto px-4 md:px-6 lg:px-8" aria-label="Global">
        <div class="relative md:flex md:items-center md:justify-between">
            <div class="flex items-center justify-between">
                <a
                    href="{{ route('inicio') }}" class="p-1" aria-label="Brand">
                    <img src="{{url(asset('/imagen/logo1.jpeg'))}}" alt="logo_abarrotes_express" class="rounded"
                         width="60px" height="60px">
                </a>

                {{--Menu Móvil--}}
                <div class="md:hidden">
                    <button type="button"
                            class="hs-collapse-toggle flex justify-center items-center w-9 h-9 text-sm font-semibold rounded-lg border border-gray-200 text-gray-800 hover:bg-gray-200  disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                            data-hs-collapse="#navbar-collapse-with-animation"
                            aria-controls="navbar-collapse-with-animation" aria-label="Toggle navigation">
                        <svg class="hs-collapse-open:hidden stroke-white hover:stroke-black flex-shrink-0 w-4 h-4"
                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round">
                            <line x1="3" x2="21" y1="6" y2="6"/>
                            <line x1="3" x2="21" y1="12" y2="12"/>
                            <line x1="3" x2="21" y1="18" y2="18"/>
                        </svg>
                        <svg class="hs-collapse-open:block hidden flex-shrink-0 w-4 h-4"
                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round">
                            <path d="M18 6 6 18"/>
                            <path d="m6 6 12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div id="navbar-collapse-with-animation"
                 class="hs-collapse hidden overflow-hidden transition-all duration-300 basis-full grow md:block">
                <div
                    class="overflow-hidden overflow-y-auto max-h-[75vh] [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-slate-700 dark:[&::-webkit-scrollbar-thumb]:bg-slate-500">
                    <div
                        class="flex flex-col gap-x-0 mt-5 divide-y divide-dashed divide-gray-200 md:flex-row md:items-center md:justify-end md:gap-x-7 md:mt-0 md:ps-7 md:divide-y-0 md:divide-solid dark:divide-gray-700">

                        <a wire:navigate
                           class="font-medium {{request() -> is('/') ? 'text-blue-600' : 'text-white' }} hover:text-gray-400 py-3 md:py-6 dark:text-blue-500 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                           href="{{ route('inicio') }}" aria-current="page">Inicio</a>

                        <a wire:navigate
                           class="font-medium {{request() -> is('productos') ? 'text-blue-600' : 'text-white' }} hover:text-gray-400 py-3 md:py-6 dark:text-gray-400 dark:hover:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                           href="{{ route('productos') }}">
                            Productos
                        </a>

                        <a wire:navigate
                           class="font-medium {{request() -> is('categorias') ? 'text-blue-600' : 'text-white' }} hover:text-gray-400 py-3 md:py-6 dark:text-gray-400 dark:hover:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                           href="{{ route('categorias') }}">
                            Categorias
                        </a>


                        <a wire:navigate
                           class="font-medium {{request() -> is('marcas') ? 'text-blue-600' : 'text-white' }} hover:text-gray-400 py-3 md:py-6 dark:text-gray-400 dark:hover:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                           href="{{ route('marcas') }}">
                            Marcas
                        </a>
                        @auth
                            <a wire:navigate
                               class="font-medium {{request() -> is('cupones') ? 'text-blue-600' : 'text-white' }} hover:text-gray-400 py-3 md:py-6 dark:text-gray-400 dark:hover:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                               href="{{route('cupones')}}">Cupones
                            </a>
                        @endauth


                        <a wire:navigate
                           class="font-medium flex items-center text-white hover:text-gray-400 py-3 md:py-6 dark:text-gray-400 dark:hover:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                           href="{{ route('carrito') }}">
                            <i class="fa-solid fa-bag-shopping"></i>
                            <span
                                class="mr-2 ml-2 {{request() -> is('carrito') ? 'text-blue-600' : 'text-white' }} hover:text-gray-400">Carrito</span>
                            <span
                                class="py-0.5 px-1.5 rounded-full text-xs font-medium bg-blue-50 border border-blue-200 text-blue-600">{{$conteo_total}}</span>
                        </a>

                        @guest()
                            <div class="pt-3 md:pt-0">
                                <a wire:navigate
                                   class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                   href="/login">
                                    <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                         height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                    Iniciar Sesión
                                </a>
                            </div>
                        @endguest

                        @auth
                            {{--Dropdown --}}
                            <div
                                class="hs-dropdown [--strategy:static] md:[--strategy:fixed] [--adaptive:none] md:[--trigger:hover] md:py-4 transition ease-in-out hover:transition hover:ease-in-out">
                                <button id="hs-dropdown-with-title" type="button"
                                        class="hs-dropdown-toggle py-0.5 px-4 inline-flex items-center gap-x-2 text-sm font-medium  text-white hover:text-gray-400 focus:outline-none disabled:opacity-50 disabled:pointer-events-none transition ease-in-out hover:transition hover:ease-in-out"
                                        aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                    {{ auth()->user()->name }}
                                    <svg class="hs-dropdown-open:rotate-180 size-4"
                                         xmlns="http://www.w3.org/2000/svg"
                                         width="24" height="24" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6"/>
                                    </svg>
                                </button>

                                <div
                                    class="hs-dropdown-menu transition-[opacity,gin] duration-[0.1ms] md:duration-[150ms] ease-in-out hs-dropdown-open:opacity-100 opacity-0 md:w-48 hidden z-10 min-w-60 bg-white shadow-md rounded-lg p-1 space-y-0.5 mt-2 divide-y divide-gray-200 before:absolute top-full md:border before:-top-5 before:start-0 before:w-full before:h-5">
                                    <div class="py-2 first:pt-0 last:pb-0">
            <span class="block py-2 px-3 text-xs font-medium uppercase text-gray-400">
                Ajustes
            </span>
                                        <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                           href="#">
                                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                                 width="24"
                                                 height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="8" cy="21" r="1"/>
                                                <circle cx="19" cy="21" r="1"/>
                                                <path
                                                    d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                                            </svg>
                                            Compras
                                        </a>
                                        <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                           href="/perfil">
                                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                                 width="24"
                                                 height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                                <circle cx="9" cy="7" r="4"/>
                                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                            </svg>
                                            Perfil
                                        </a>
                                        <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                           href="/quejasugerencia">
                                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                                 width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <!-- Icono de comentario para Quejas y Sugerencias -->
                                                <path d="M21 15a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h4l5 5v-5h7z"/>
                                            </svg>
                                            Quejas y sugerencias
                                        </a>
                                        <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                           href="/reporteproblema">
                                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                                 width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <!-- Icono de advertencia para Reportar un problema -->
                                                <path d="M12 2L2 22h20L12 2z"/>
                                                <line x1="12" y1="16" x2="12" y2="12"/>
                                                <line x1="12" y1="20" x2="12" y2="20"/>
                                            </svg>
                                            Reportar un problema
                                        </a>

                                    </div>
                                    @if(auth()->user()->hasPermissionTo('ver:admin'))
                                        <div class="py-2 first:pt-0 last:pb-0">
      <span class="block py-2 px-3 text-xs font-medium uppercase text-gray-400">
        Administración
      </span>

                                            <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                               href="/admin">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                     viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                     class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/>
                                                </svg>
                                                Ir al Panel Administrativo
                                            </a>
                                            @endif
                                            <span
                                                class="block py-2 px-3 text-xs font-medium uppercase text-gray-400">
        Sesión
      </span>
                                            <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500"
                                               href="/logout">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                     fill="current"
                                                     class="size-6">
                                                    <path fill-rule="evenodd"
                                                          d="M9.53 2.47a.75.75 0 0 1 0 1.06L4.81 8.25H15a6.75 6.75 0 0 1 0 13.5h-3a.75.75 0 0 1 0-1.5h3a5.25 5.25 0 1 0 0-10.5H4.81l4.72 4.72a.75.75 0 1 1-1.06 1.06l-6-6a.75.75 0 0 1 0-1.06l6-6a.75.75 0 0 1 1.06 0Z"
                                                          clip-rule="evenodd"/>
                                                </svg>

                                                Cerrar Sesión
                                            </a>
                                        </div>
                                </div>
                            </div>
                            {{--Fin dropdown--}}
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
