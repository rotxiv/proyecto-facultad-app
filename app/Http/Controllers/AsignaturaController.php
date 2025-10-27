<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use Illuminate\Http\Request;

class AsignaturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asignaturas = Asignatura::where('estado', true)->get();

        return view('application.asignatura.index', compact('asignaturas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //return view('application.grupo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'descripcion'=> 'required|string|max:50'
        ]);

        // Crear el rol
        $asig = Asignatura::create([
            'descripcion' => $request->descripcion
        ]);

        // obtener la asignatura creada
        $asignatura = Asignatura::where('id', $asig->id)
            ->where('estado', true)
            ->first();

        return redirect()->route('asignatura.index')
            ->with('success', 'Asignatura agregada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        /* $grupo = Grupo::where('estado', true)->find($id);

        return view('application.grupo.show', compact('grupo')); */
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        /* $grupo = Grupo::where('id', $id)
            ->where('estado', true)
            ->first();
        
        return view('application.grupo.edit', compact('grupo')); */
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'descripcion'=> 'required|string|max:50'
        ]);

        $asignatura = Asignatura::where('id', $id)
            ->where('estado', true)
            ->first();

        // Actualizar los datos de la asignatura
        $asignatura->update([
            'descripcion' => $request->descripcion
        ]);

        return redirect()->route('asignatura.index')
            ->with('success', 'Asignatura actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $asignatura = Asignatura::where('id', $id)
            ->where('estado', true)
            ->first();

        if (!$asignatura) {
            return redirect()->route('asignatura.index')
                ->with('error', 'Asignatura no encontrada.');
        }

        $asignatura->estado = false;
        
        $asignatura->save();

        return redirect()->route('asignatura.index')
            ->with('success', 'Asignatura eliminada correctamente.');
    }

    public function reactivate($id)
    {
        $asignatura = Asignatura::where('id', $id)
            ->where('estado', false)
            ->first();

        if (!$asignatura) {
            return redirect()->route('asignatura.index')
                ->with('error', 'Asignatura no encontrada.');
        }

        $asignatura->estado = true;
        
        $asignatura->save();

        return redirect()->route('asignatura.index')
            ->with('success', 'Asignatura agregado correctamente.');
    }

    public function deletedIndex()
    {
        // Obtenemos todos las asignaturas retiradas (estado = false)
        $asignaturas = Asignatura::where('estado', false)->get();

        return view(
            'application.asignatura.deletedIndex', compact('asignaturas')
        );
    }
}
