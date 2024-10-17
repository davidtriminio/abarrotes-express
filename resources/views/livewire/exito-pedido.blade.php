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
                </div>
            </main>
        </div>
    </div>
    <!-- /.container -->
</div>
