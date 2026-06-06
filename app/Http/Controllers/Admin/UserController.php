<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;

/**
 * Controlador de Usuarios.
 * Administra el CRUD de los operadores y administradores en la plataforma,
 * delegando la encriptación y el estado activo/inactivo al UserService.
 */
class UserController extends Controller
{
    /**
     * Constructor del controlador.
     * Inyecta el servicio de negocio encargado de la gestión de usuarios.
     */
    public function __construct(private UserService $userService)
    {
    }

    /**
     * Muestra la lista paginada de usuarios en el sistema.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $usuarios = $this->userService->listar();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Muestra la vista con el formulario para registrar un nuevo usuario.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.usuarios.create');
    }

    /**
     * Almacena un nuevo usuario registrando su hash de contraseña inicial.
     *
     * @param \App\Http\Requests\StoreUserRequest $request Petición con datos validados.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreUserRequest $request)
    {
        $this->userService->crear($request->validated());
        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Muestra la vista con el formulario para editar a un usuario.
     *
     * @param \App\Models\User $usuario Modelo del usuario a editar.
     * @return \Illuminate\View\View
     */
    public function edit(User $usuario)
    {
        return view('admin.usuarios.edit', compact('usuario'));
    }

    /**
     * Actualiza la información del usuario. Si se reseteó la clave, se forzará su cambio.
     *
     * @param \App\Http\Requests\UpdateUserRequest $request Petición con datos validados.
     * @param \App\Models\User $usuario Modelo del usuario a actualizar.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserRequest $request, User $usuario)
    {
        $this->userService->actualizar($usuario, $request->validated());
        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Realiza un borrado lógico (Soft Delete) del usuario.
     *
     * @param \App\Models\User $usuario Modelo del usuario a eliminar.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $usuario)
    {
        $this->userService->eliminar($usuario);
        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}