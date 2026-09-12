<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;



class TaskResponseController extends Controller
{
    /**
     * Guardar una respuesta de una tarea
     */
    public function store(Request $request, $task_id)
    {
        $request->validate([
            'response' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ], [
            'response.required' => 'Debe ingresar una respuesta.',
            'attachment.mimes' => 'El archivo debe ser PDF, Word o Excel.',
            'attachment.max' => 'El archivo no puede superar los 10 MB.',
        ]);

        // Buscar la tarea
        $task = Task::findOrFail($task_id);

        // Guardar documento
        $attachmentPath = null;

        if ($request->hasFile('attachment')) {

            $attachmentPath = $request->file('attachment')
                ->store('task_responses', 'public');
        }

        // Guardar respuesta
        TaskResponse::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'response' => $request->response,
            'attachment_path' => $attachmentPath,
        ]);

        return redirect()
            ->route('user.task.edit', $task->id)
            ->with(
                'success_response',
                'La respuesta fue registrada correctamente.'
            );
    }

    public function download($id)
    {
        $response = TaskResponse::findOrFail($id);

        if (!$response->attachment_path) {
            abort(404, 'La respuesta no tiene archivo adjunto.');
        }

        $disk = Storage::disk('public');

        if (!$disk->exists($response->attachment_path)) {
            abort(404, 'El archivo no existe en el almacenamiento.');
        }

        return $disk->download(
            $response->attachment_path,
            basename($response->attachment_path)
        );
    }


}