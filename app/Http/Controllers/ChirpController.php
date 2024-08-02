<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\Request;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('chirps.index', [
            # Con with('user') indicamos la relacion que queremos pre-cargar. Esto ahorra recursos, tiempo y memoria.
            # Al precargar al usuario, no se generan consultas adicionales.
            'chirps' => Chirp::with('user')->latest()->get() # Obtenemos TODOS los registros de la tabla de chirps, y los ordenamos por la columna 'created at'. A su vez, los ordenamos por forma DESCENDENTE. (Es decir, los mas nuevos primero)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([ # Mostramos los campos que queremos validar de la peticion.
            # 'message' = Nombre del 'textarea' de nuestro formulario.
            'message' => ['required', 'min: 3', 'max: 255'], # Requerimos que exista un mensaje, el cual tenga como minimo 3 caracteres hasta un maximo de 255..
        ]);

        /*
            auth()->user(): Accedemos al usuario ACTUALMENTE autenticado.
            chirps(): Accedemos a la RELACION con sus chirps
            create(): Creamos un chirp con el valor que recibimos del formulario. (En este caso, lo que esta en el campo 'message')
            INTERNAMENTE se asignara un user_id. Por eso no es necesario declararlo.
        */
        # Al recibir el envio del formulario, lo enviamos.
        auth()->user()->chirps()->create($validated); # Guardamos tanto la validacion como el mensaje en la variable $validated. Si todos los campos son correctos, se enviara el mensaje.

        return to_route('chirps.index') # Luego de enviar el mensaje, lo redireccionamos al index.
            ->with('status', '¡Chirp creado exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp) # Laravel reconoce a Chirp en la BDD.
    {
        # Verificamos si realmente el usuario que quiera editar el chirp, es el autor del mismo.
        $this->authorize('update', $chirp);

        return view('chirps.edit', [
            'chirp' => $chirp, # Le pasamos el chirp que queremos editar.
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp)
    {
        # Verificamos si realmente el usuario que quiera editar el chirp, es el autor del mismo.
        $this->authorize('update', $chirp);

        $validated = $request->validate([ # Mostramos los campos que queremos validar de la peticion.
            # 'message' = Nombre del 'textarea' de nuestro formulario.
            'message' => ['required', 'min: 3', 'max: 255'], # Requerimos que exista un mensaje, el cual tenga como minimo 3 caracteres hasta un maximo de 255..
        ]);

        # Ahora actualizamos mediante metodo update()
        $chirp->update($validated);

        return to_route('chirps.index')
            ->with('status', __('Chirp correctamente actualizado'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp)
    {
        # Autorizamos la eliminacion del chirp. Enviamos la accion y luego el chirp como parametro.
        $this->authorize('delete', $chirp);

        # Luego eliminamos el chirp de la BDD.
        $chirp->delete();

        return to_route('chirps.index')
            ->with('status', __('Chirp deleted succesfully!'));
    }
}
