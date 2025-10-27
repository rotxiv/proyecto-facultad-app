<x-layouts.app :title="__('Docentes Retirados')">
    <!-- Tabla Principal -->
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                Lista de Docentes Activos
                <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">
                    Todos los docentes que actualmente estan dando alguna clase en el semestre.
                </p>
            </caption>
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Codigo
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Carnet
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nombre
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Telefono
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($docentes as $docente)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <td class="px-6 py-4">
                        {{ $docente->codigo ?? '—' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $docente->persona->carnet ?? '—' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $docente->persona->nombre ?? '—' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $docente->persona->telefono ?? '—' }}
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('docentes.reactivar', $docente->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:bg-red-700 dark:hover:bg-red-800">
                                Reactivar
                            </button>
                        </form> 
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.app>
