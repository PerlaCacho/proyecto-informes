<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Campus;
use App\Models\Facultad;

class TernaController extends Controller
{
     public function index()
    {
        // $alumnos = User::where('role', 'alumno')->get();
        // $docentes = User::where('role', 'docente')->get();
        //         return view ('Administrador.AsignarTerna.create', compact('alumnos', 'docentes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //obtener usuarios con role 'alumno'
        $alumnos = User::whereHas('role', function($query) {
            $query->where('nombre_role', 'alumno');
        })->get();
    
        // Obtener solo usuarios con role 'docente'
        $docentes = User::whereHas('role', function($query) {
            $query->where('nombre_role', 'docente');
        })->get();
    
        // Obtener todas las ternas existentes con sus usuarios
        $ternas = \App\Models\Terna::all();
    
        // Obtener los campus y las facultades
        $campus = Campus::all();
        $facultades = Facultad::all();

        // enviar datos a la vista
        return view ('Administrador.AsignarTerna.create', compact('alumnos', 'docentes', 'ternas', 'campus', 'facultades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos enviados por el formulario
        $request->validate([
            'estudiante' => 'required|exists:users,id',
            'docente1' => 'required|exists:users,id',
            'docente2' => 'required|exists:users,id',
            'docente3' => 'required|exists:users,id',
            'docente4' => 'nullable|exists:users,id',
        ]);
        
        // Crear una nueva terna
        $terna = \App\Models\Terna::create([
            'estado_terna' => 'Pendiente'
        ]);
        
        // Asignar el estudiante a la terna
        \App\Models\UserTernaTransitiva::create([
            'id_user' => $request->estudiante,
            'id_terna' => $terna->id
        ]);
        
        // Asignar los docentes a la terna
        \App\Models\UserTernaTransitiva::create([
            'id_user' => $request->docente1,
            'id_terna' => $terna->id
        ]);
        
        \App\Models\UserTernaTransitiva::create([
            'id_user' => $request->docente2,
            'id_terna' => $terna->id
        ]);
        
        \App\Models\UserTernaTransitiva::create([
            'id_user' => $request->docente3,
            'id_terna' => $terna->id
        ]);
        
        // Asignar el docente opcional si se proporcionó
        if ($request->docente4) {
            \App\Models\UserTernaTransitiva::create([
                'id_user' => $request->docente4,
                'id_terna' => $terna->id
            ]);
        }
        
        return redirect()->route('AsignarTerna.create')
            ->with('success', 'Terna asignada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $terna = \App\Models\Terna::findOrFail($id);
        
        // Eliminar primero los informes asociados a esta terna
        \App\Models\Informe::where('id_terna', $id)->delete();
        
        // Eliminar las relaciones en la tabla pivote
        \App\Models\UserTernaTransitiva::where('id_terna', $id)->delete();
        
        // Eliminar la terna
        $terna->delete();
        
        return redirect()->route('AsignarTerna.create')
            ->with('success', 'Terna eliminada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'estudiante' => 'required|exists:users,id',
            'docente1' => 'required|exists:users,id',
            'docente2' => 'required|exists:users,id',
            'docente3' => 'required|exists:users,id',
            'docente4' => 'nullable|exists:users,id',
        ]);
    
        $terna = \App\Models\Terna::findOrFail($id);
        
        // Eliminar las relaciones existentes
        \App\Models\UserTernaTransitiva::where('id_terna', $id)->delete();
        
        // Asignar el estudiante a la terna
        \App\Models\UserTernaTransitiva::create([
            'id_user' => $request->estudiante,
            'id_terna' => $terna->id
        ]);
        
        // Asignar los docentes a la terna
        \App\Models\UserTernaTransitiva::create([
            'id_user' => $request->docente1,
            'id_terna' => $terna->id
        ]);
        
        \App\Models\UserTernaTransitiva::create([
            'id_user' => $request->docente2,
            'id_terna' => $terna->id
        ]);
        
        \App\Models\UserTernaTransitiva::create([
            'id_user' => $request->docente3,
            'id_terna' => $terna->id
        ]);
        
        // Asignar el docente opcional si se proporcionó
        if ($request->docente4) {
            \App\Models\UserTernaTransitiva::create([
                'id_user' => $request->docente4,
                'id_terna' => $terna->id
            ]);
        }
        
        return redirect()->route('AsignarTerna.create')
            ->with('success', 'Terna actualizada correctamente.');
    }



}
