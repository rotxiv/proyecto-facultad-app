<x-layouts.app :title="__('Lista de Asinaturas')">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

        <!-- Modal toggle -->
        <button id="open-add-modal" data-modal-target="authentication-modal" data-modal-toggle="authentication-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">Añadir Asignatura</button>

        <form class="space-y-4" action="{{ route('asignaturas.deleted-index') }}" method="GET">
            @csrf
           <button class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Asignaturas Eliminadas</button>
        </form>

        <!-- Main modal -->
        <div id="modal-open-button" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Ingrese la nueva Asignatura
                        </h3>
                        <button id="modal-close-button" type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="authentication-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5 max-h-[70vh] overflow-y-auto">
                        <form class="space-y-4" action="{{ route('asignatura.store') }}" method="POST">
                            @csrf
                            <div>
                                <label for="asignatura" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Número de Carnet</label>
                                <input type="text" name="asignatura" id="asignatura" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                                
                                <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Añadir Asignatura</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin Modal -->

        <!-- Tabla Principal -->
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                Lista de Asignaturas Activas
                <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">
                    Todas las asignaturas que se imparten actualmente.
                </p>
            </caption>
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Asignatura</th>
                    <th scope="col" class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($asignaturas as $asignatura)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <td class="px-6 py-4">{{ $asignatura->id ?? '—' }}</td>
                    <td class="px-6 py-4">{{ $asignatura->descripcion ?? '—' }}</td>
                    <td class="px-6 py-4 text-right">
                        <button id="open-edit-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">Editar Asignatura</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Script -->
    <script>
        const openAddModalButton = document.getElementById('modal-open-button');
        const addModal = document.getElementById('add-subject-modal');
        const closeAddModalButton = document.getElementById('modal-close-button');
        const openEditModalButton = document.getElementById('modal-open-add-button');
        const editModal = document.getElementById('edit-subject-modal');
        const closeEditModalButton = document.getElementById('modal-close-button');

        openAddModalButton.addEventListener('click', () => {
            addModal.classList.remove('hidden');
            addModal.classList.add('flex');
        });

        closeAddModalButton.addEventListener('click', () => {
            addModal.classList.add('hidden');
            addModal.classList.remove('flex');
        });

        openEditModalButton.addEventListener('click', () => {
            editModal.classList.remove('hidden');
            editModal.classList.add('flex');
        });

        closeEditModalButton.addEventListener('click', () => {
            editModal.classList.add('hidden');
            editModal.classList.remove('flex');
        });

        // Pasar la variable PHP a JavaScript
        let variable = @json($docentes);
        console.log(variable); // Imprimir en la consola

    </script>
</x-layouts.app>
