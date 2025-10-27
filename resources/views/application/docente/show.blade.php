<x-layouts.app :title="__('Docente')">
    <div class="w-full max-w-md mx-auto bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 mt-6">
        <div class="flex flex-col items-center p-6">
            <img class="w-24 h-24 mb-4 rounded-full shadow-lg" src="/images/default-user.png" alt="Foto de docente"/>

            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ $docente->persona->nombre ?? 'Nombre no disponible' }}
            </h2>

            <!-- Información del docente -->
            <ul class="w-full text-sm text-gray-700 dark:text-gray-300">
                <li class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-600">
                    <span class="font-medium">Carnet:</span>
                    <span>{{ $docente->persona->carnet ?? '—' }}</span>
                </li>
                <li class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-600">
                    <span class="font-medium">Teléfono:</span>
                    <span>{{ $docente->persona->telefono ?? '—' }}</span>
                </li>
                <li class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-600">
                    <span class="font-medium">Dirección:</span>
                    <span>{{ $docente->persona->direccion ?? '—' }}</span>
                </li>
                <li class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-600">
                    <span class="font-medium">Correo:</span>
                    <span>{{ $docente->correo ?? '—' }}</span>
                </li>
                <li class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-600">
                    <span class="font-medium">Género:</span>
                    <span>{{ $docente->persona->sexo === 'M' ? 'Masculino' : 'Femenino' }}</span>
                </li>
                <li class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-600">
                    <span class="font-medium">Código:</span>
                    <span>{{ $docente->codigo ?? '—' }}</span>
                </li>
                <li class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-600">
                    <span class="font-medium">Carga Horaria:</span>
                    <span>{{ $docente->carga_horaria ?? '—' }} hrs</span>
                </li>
                <li class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-600">
                    <span class="font-medium">Fecha de Ingreso:</span>
                    <span>{{ \Carbon\Carbon::parse($docente->fecha_ingreso)->format('d/m/Y') }}</span>
                </li>
            </ul>

            <!-- Botones de acción -->
            <div class="flex gap-3 mt-6">
                <a href="{{ route('docentes.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700">
                    Volver
                </a>
                <a href="{{ route('docentes.edit', $docente->id) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700">
                    Editar
                </a>
                <form action="{{ route('docentes.destroy', $docente->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este docente?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:bg-red-700 dark:hover:bg-red-800">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
