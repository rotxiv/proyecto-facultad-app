<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Docente;
use Illuminate\Http\Request;

class DocenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //retorna todos los docentes que estan visibles
        $docentes = Docente::with(['persona:id,carnet,nombre,telefono']) // solo esos campos de persona
            ->where('estado', true)
            ->select('id', 'codigo', 'persona_id') // campos de docente
            ->get();

        return view(
            'application.docente.index', compact('docentes')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('application.docente.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'carnet'=> 'required|string|max:15|unique:personas,carnet',
            'nombre' => 'required|string|max:100',
            'sexo' => 'required|string|size:1|in:M,F',
            'telefono' => 'nullable|string|max:15',
            'direccion' => 'required|string|max:100',
            'fecha_ingreso' => 'required|date',
            'codigo' => 'required|string|max:10|unique:docentes,codigo',
            'correo' => 'required|string|email|max:100|unique:docentes,correo',
            'carga_horaria' => 'required|integer|min:1',
        ]);

        // Crear la persona asociada al docente
        $persona = Persona::create([
            'carnet' => $request->carnet,
            'nombre' => $request->nombre,
            'sexo' => $request->sexo,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'fecha_ingreso' => $request->fecha_ingreso
        ]);

        // Crear al docente
        $docente_temp = Docente::create([
            'persona_id' => $persona->id,
            'codigo' => $request->codigo,
            'correo' => $request->correo,
            'carga_horaria' => $request->carga_horaria
        ]);

        // obtener el docente creado con la relacion de persona
        $docente = Docente::where('estado', true)
            ->with('persona')
            ->find($docente_temp->id)
            ->get();

        // uso de la bitacora
        registrar_bitacora(
            "Se creó un nuevo docente: {$request->codigo}"
        );

        return view('application.docente.show', compact('docente'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $docente = Docente::where('estado', true)
            ->with('persona')
            ->find($id);

        if (!$docente) {
            return redirect()->route('docentes.index')
                ->with('error', 'Docente no encontrado.');
        }

        return view('application.docente.show', compact('docente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $docente = Docente::where('estado', true)
            ->with('persona')
            ->find($id);

        return view('application.docente.edit', compact('docente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Obtener el docente de forma temporal
        $docente = Docente::with('persona')->findOrFail($id);

        // Validar los datos del formulario
        $request->validate([
            'carnet' => 'required|unique:personas,carnet,' . $docente->persona->id,
            'nombre' => 'required|string|max:100',
            'sexo' => 'required|string|size:1|in:M,F',
            'telefono' => 'nullable|string|max:15',
            'direccion' => 'nullable|string|max:100',
            'fecha_ingreso' => 'required|date',
            'codigo' => 'required|string|max:10|unique:docentes,codigo,' . $docente->id,
            'correo' => 'required|string|email|max:100|unique:docentes,correo,' . $docente->id,
            'carga_horaria' => 'required|integer|min:1'
        ]);

        // Actualizar los datos de la Persona asociada al docente
        $docente->persona->update([
            'carnet' => $request->carnet,
            'nombre' => $request->nombre,
            'sexo' => $request->sexo,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'fecha_ingreso' => $request->fecha_ingreso
        ]);

        // Actualizar los datos del docente
        $docente->update([
            'codigo' => $request->codigo,
            'correo' => $request->correo,
            'carga_horaria' => $request->carga_horaria
        ]);

        // uso de la bitacora
        registrar_bitacora(
            "Se actualizó al docente: {$request->codigo}"
        );

        return view('application.docente.show', compact('docente'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $docente = Docente::where('estado', true)->find($id);

        if (!$docente) {
            return redirect()->route('docentes.index')
                ->with('error', 'Docente no encontrado.');
        }

        $docente->estado = false;
        
        $docente->save();

        // uso de la bitacora
        registrar_bitacora(
            "Se elimino al docente: {$docente->codigo}"
        );

        return redirect()->route('docentes.index')
            ->with('success', 'Docente eliminado correctamente.');
    }

    public function reactivate($id)
    {
        $docente = Docente::where('id', $id)
            ->where('estado', false)
            ->first();


        if (!$docente) {
            return redirect()->route('docentes.index')
                ->with('error', 'Docente no encontrado.');
        }

        $docente->estado = true;
        
        $docente->save();

        // uso de la bitacora
        registrar_bitacora(
            "Se reactivó al docente con codigo: {$docente->codigo}"
        );

        return redirect()->route('docentes.show', $docente->id)
            ->with('success', 'Docente eliminado correctamente.');
    }

    public function deletedIndex()
    {
        // Obtenemos todos los docentes retirados (estado = false)
        $docentes = Docente::where('estado', false)
            ->with(['persona:id,carnet,nombre,telefono'])
            ->get(['id', 'codigo', 'persona_id']);

        // Opcional: filtrar por si hay docentes sin persona asociada (evita errores en la vista)
        $docentes = $docentes->filter(fn($docente) => $docente->persona !== null);

        return view(
            'application.docente.deletedIndex', 
            compact('docentes')
        );
    }

}
