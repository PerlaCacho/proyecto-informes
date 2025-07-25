<x-app-layout>
    <div class="min-h-screen bg-gray-100 flex items-start justify-center py-10">
        <div class="w-full md:w-4/5 lg:w-3/4 bg-white rounded-lg shadow p-6">
            <!-- Sección: Información de Usuario -->
            <div class="border border-gray-300 rounded-lg p-6 mb-6 bg-white">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        {{-- {{$alumno->name}} la variable $alumno viene de la funucion Show del controlador Almnouser. Se manda a llamar los datos como estan los campos en la BD --}}
                        <p class="text-sm"><strong>Nombre:</strong> {{$alumno->name}}</p>
                        <p class="text-sm"><strong>Numero de cuenta:</strong> {{$alumno->numero_cuenta}}</p>
                        <p class="text-sm"><strong>Facultad:</strong> {{$alumno->facultad()->first()->nombre ?? 'Sin facultad'}}</p>
                        <p class="text-sm"><strong>Numero de telefono:</strong> {{$alumno->telefono}}</p>
                    </div>
                    <div>
                        <p class="text-sm"><strong>Correo electronico:</strong> {{$alumno->email}}</p>
                        <p class="text-sm"><strong>Rol:</strong> Alumno</p>
                        <p class="text-sm"><strong>Campus:</strong> {{$alumno->campus()->first()->nombre ?? 'Sin campus'}}</p>
                    </div>
                </div>
            </div>

            <!-- Sección: Última actividad -->
            <div class="flex justify-center mb-6">
                <div class="text-black font-medium rounded-lg text-sm px-5 py-2.5 shadow"
                        style="background-color:#FFC436;">
                    Ultima actividad: 
                    @if(isset($fechaUltimoInforme))
                        {{ $fechaUltimoInforme->format('d/m/Y h:i a') }}
                    @else
                        Sin actividad registrada
                    @endif
                </div>
            </div>

            <!-- Sección: Terna -->
            <div class="border border-gray-300 rounded-lg p-6 bg-white mb-10">
                <p class="text-sm font-medium mb-4" style="color:#004CBE;">Terna:</p>
                <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                    @if($terna)
                        <p class="text-sm font-semibold mb-2" style="color:#004CBE;">Estado: {{ $terna->estado_terna }}</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @forelse($docentes as $index => $docente)
                                <div class="bg-white p-3 rounded-lg shadow border border-gray-200">
                                    <p class="text-sm font-semibold">Docente #{{ $index + 1 }}:</p>
                                    <p class="text-sm">{{ $docente->name }}</p>
                                </div>
                            @empty
                                <div class="col-span-4 text-center">
                                    <p class="text-sm text-gray-500">No hay docentes asignados a esta terna</p>
                                </div>
                            @endforelse
                            
                            @if(count($docentes) > 0 && count($docentes) < 4)
                                @for($i = count($docentes); $i < 4; $i++)
                                    <div class="bg-white p-3 rounded-lg shadow border border-gray-200">
                                        <p class="text-sm font-semibold">Docente #{{ $i + 1 }}:</p>
                                        <p class="text-sm text-gray-400">No asignado</p>
                                    </div>
                                @endfor
                            @endif
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-sm text-gray-500">Este alumno no tiene una terna asignada</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sección inferior: Informe PDF -->
            <div class="border border-gray-300 rounded-lg p-6 bg-white">
                <p class="text-sm font-medium mb-4" style="color:#004CBE;">Ultimo informe subido:</p>
                <div class="flex justify-center">
                    @if(isset($ultimoInforme))
                        <div class="border border-gray-300 rounded-lg p-6 flex flex-col items-center bg-gray-50 w-full">
                            <p class="text-lg font-semibold mb-2" style="color:#004CBE;">Visualizador web</p>
                            <p class="text-gray-600 mb-2">{{ basename($ultimoInforme) }}</p>
                            <iframe
                                src="{{ route('admin.observarInforme.pdf', ['nombreArchivo' => basename($ultimoInforme)]) }}"
                                class="w-full h-[600px] rounded border border-gray-300"
                                frameborder="0"
                                scrolling="auto"
                                allowfullscreen>
                            </iframe>
                        </div>
                    @else
                        <div class="border border-gray-300 rounded-lg p-6 flex flex-col items-center bg-gray-50">
                            <p class="text-lg font-semibold mb-2" style="color:#004CBE;">Sin informes</p>
                            <svg class="w-16 h-16" fill="none" stroke="#004CBE" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="mt-2 text-gray-600">El alumno no ha subido ningún informe</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
