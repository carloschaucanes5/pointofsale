<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

use App\Http\Requests\CustomerFormRequest;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class TaskController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        if ($request) {
            $query = trim($request->get('searchText'));
            $tasks = DB::table('task as ta')
                ->join('users as us', 'ta.assignee_id', '=', 'us.id')
                ->leftJoin('users as ua', 'ta.assigner_id', '=', 'ua.id')
                ->where('us.name', 'like', '%' . $query . '%')
                ->select(
                    'us.id as users_id',
                    'us.name as user_name',
                    'ta.id as task_id',
                    'ta.name as task_name',
                    'ta.detail',
                    'ta.start_at',
                    'ta.updated_at',
                    'ta.created_at',
                    'ta.status',
                    'ta.end_at',
                    'ua.name as assigner_name'
                )
                ->orderBy('ta.created_at', 'desc')
                ->paginate(7);
            return view('user.task.index', ['tasks' => $tasks, 'searchText' => $query]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fechaInicial = now()->startOfMonth()->format('Y-m-d');
        $fechaFinal = now()->endOfMonth()->format('Y-m-d');
        $users = DB::table('users as us')
            ->select("id", "name", "role")
            ->where('us.status', "=", 1)
            ->get();

        $tasks = DB::table('config')
            ->select('key', 'value')
            ->where('key', '=', 'tasks_users')
            ->first()->value;

        $status = DB::table('config')
            ->select('key', 'value')
            ->where('key', '=', 'status_task')
            ->first()->value;
        return view("user.task.create", ['status' => explode(",", $status), 'users' => $users, 'tasks' => explode(",", $tasks), 'fechaInicial' => $fechaInicial, 'fechaFinal' => $fechaFinal]);
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        try {

            // Validación de los campos
            $request->validate([
                'assignee_id' => 'required|exists:users,id',
                'name' => 'required|string|max:255',
                'start_at' => 'required|date',
                'end_at' => 'required|date|after_or_equal:start_at',
                'detail' => 'required|string|max:1000',
            ], [
                'assignee_id.required' => 'Debes seleccionar un usuario.',
                'assignee_id.exists' => 'El usuario seleccionado no existe.',

                'name.required' => 'Debes seleccionar una tarea.',
                'name.string' => 'La tarea debe ser válida.',
                'name.max' => 'El nombre de la tarea es demasiado largo.',

                'start_at.required' => 'La fecha de inicio es obligatoria.',
                'start_at.date' => 'La fecha de inicio no es válida.',

                'end_at.required' => 'La fecha final es obligatoria.',
                'end_at.date' => 'La fecha final no es válida.',
                'end_at.after_or_equal' =>
                    'La fecha final debe ser igual o posterior a la fecha de inicio.',

                'detail.required' => 'El detalle de la tarea es obligatorio.',
                'detail.string' => 'El detalle debe ser texto.',
                'detail.max' => 'El detalle no puede superar los 1000 caracteres.',
            ]);

            // Validación adicional
            if (auth()->id() == $request->assignee_id) {

                return back()
                    ->withErrors([
                        'general' => 'No puedes asignarte esta tarea a ti mismo.'
                    ])
                    ->withInput();
            }

            // Crear tarea
            $task = new Task();

            $task->name = $request->name;
            $task->status = 'ASIGNADA';
            $task->assigner_id = auth()->id();
            $task->assignee_id = $request->assignee_id;
            $task->start_at = $request->start_at;
            $task->end_at = $request->end_at;
            $task->detail = $request->detail;

            $task->save();

            return redirect()
                ->route('user.task.index')
                ->with('success', 'Tarea creada correctamente.');

        } catch (\Exception $e) {

            // Guardar el error real en el log
            Log::error('Error al crear tarea: ' . $e->getMessage());

            // Mostrar un mensaje amigable
            return back()
                ->withErrors([
                    'general' => 'Ocurrió un error al crear la tarea.'
                ])
                ->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('sale.customer.show', ['customer' => Customer::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $users = DB::table('users as us')
            ->select("id", "name", "role")
            ->where('us.status', "=", 1)
            ->get();

        $tasks = DB::table('config')
            ->select('key', 'value')
            ->where('key', '=', 'tasks_users')
            ->first()->value;

        $status = DB::table('config')
            ->select('key', 'value')
            ->where('key', '=', 'status_task')
            ->first()->value;

        $task = Task::findOrFail($id)->first();
        return view("user.task.edit", ["users" => $users, "tasks" => explode(",", $tasks), "status" => explode(",", $status), "task" => $task]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {

            // Validación de los campos
            $request->validate([
                'assignee_id' => 'required|exists:users,id',
                'name' => 'required|string|max:255',
                'status' => 'required|string|max:255',
                'start_at' => 'required|date',
                'end_at' => 'required|date|after_or_equal:start_at',
                'detail' => 'required|string|max:1000',
            ], [
                'assignee_id.required' => 'Debes seleccionar un usuario.',
                'assignee_id.exists' => 'El usuario seleccionado no existe.',

                'name.required' => 'Debes seleccionar una tarea.',
                'name.string' => 'La tarea debe ser válida.',
                'name.max' => 'El nombre de la tarea es demasiado largo.',

                'status.required' => 'Debes seleccionar un estado.',
                'status.string' => 'El estado debe ser válido.',
                'status.max' => 'El estado no es válido.',

                'start_at.required' => 'La fecha de inicio es obligatoria.',
                'start_at.date' => 'La fecha de inicio no es válida.',

                'end_at.required' => 'La fecha final es obligatoria.',
                'end_at.date' => 'La fecha final no es válida.',
                'end_at.after_or_equal' =>
                    'La fecha final debe ser igual o posterior a la fecha de inicio.',

                'detail.required' => 'El detalle de la tarea es obligatorio.',
                'detail.string' => 'El detalle debe ser texto.',
                'detail.max' => 'El detalle no puede superar los 1000 caracteres.',
            ]);

            // Validación adicional
            if (auth()->id() == $request->assignee_id) {

                return back()
                    ->withErrors([
                        'general' => 'No puedes asignarte esta tarea a ti mismo.'
                    ])
                    ->withInput();
            }

            // Buscar la tarea
            $task = Task::findOrFail($id);

            // Actualizar los datos
            $task->name = $request->name;
            $task->status = $request->status;
            $task->assignee_id = $request->assignee_id;
            $task->start_at = $request->start_at;
            $task->end_at = $request->end_at;
            $task->detail = $request->detail;

            $task->update();

            return redirect()
                ->route('user.task.index')
                ->with('success', 'Tarea actualizada correctamente.');

        } catch (\Exception $e) {

            // Guardar el error real en el log
            Log::error('Error al actualizar tarea: ' . $e->getMessage());

            // Mostrar mensaje amigable
            return back()
                ->withErrors([
                    'general' => 'Ocurrió un error al actualizar la tarea.'
                ])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->status = 0;
        $customer->update();
        return Redirect::to("sale/customer")->with("success", "Cliente eliminado correctamente");
    }
}
