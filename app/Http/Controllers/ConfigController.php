<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Process;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ConfigController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {

        $configs = Config::orderBy('key')->get();
        return view('config.index', compact('configs'));
    }

    public function create()
    {
        return view('config.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => [
                'required',
                'string',
                'max:100',
                'unique:config,key',
            ],
            'value' => [
                'nullable',
                'string',
            ],
            'alias' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);

        Config::create($data);

        return redirect()
            ->route('config.index')
            ->with('success', 'Configuración creada correctamente.');
    }

    public function edit($key)
    {
        $config = Config::where('key', $key)->firstOrFail();

        return view('config.edit', compact('config'));
    }

    public function update(Request $request, $key)
    {
        $config = Config::where('key', $key)->firstOrFail();

        $data = $request->validate([
            'key' => [
                'required',
                'string',
                'max:100',
                Rule::unique('config', 'key')->ignore($config->key, 'key'),
            ],
            'value' => [
                'nullable',
                'string',
            ],
            'alias' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);

        $config->update($data);

        return redirect()
            ->route('config.index')
            ->with('success', 'Configuración actualizada correctamente.');
    }


    public function backup()
    {
        return view('config.backup');
    }



    public function exportarBackup()
    {
        $filename = 'pointofsale_' . date('Ymd_Hi') . '.sql';
        $path = storage_path('app/' . $filename);

        // Misma ruta que utilizas en tu tarea programada
        $mysqldump = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';

        // Datos de la base de datos
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        // Verificar que mysqldump exista
        if (!file_exists($mysqldump)) {
            return back()->with(
                'error',
                'No se encontró mysqldump en: ' . $mysqldump
            );
        }

        // Como tu contraseña está vacía
        $passwordOption = '';

        if (!empty($password)) {
            $passwordOption = '-p' . escapeshellarg($password);
        }

        // Crear comando
        $command = sprintf(
            '"%s" -u %s %s %s > "%s" 2>&1',
            $mysqldump,
            escapeshellarg($username),
            $passwordOption,
            escapeshellarg($database),
            $path
        );

        // Ejecutar
        exec($command, $output, $result);

        // Verificar resultado
        if ($result !== 0 || !file_exists($path) || filesize($path) === 0) {

            $error = implode("\n", $output);

            return back()->with(
                'error',
                'No se pudo generar el archivo SQL. ' . $error
            );
        }

        // Descargar y eliminar después
        return response()
            ->download($path, $filename)
            ->deleteFileAfterSend(true);
    }


    public function importarBackup(Request $request)
    {
        /*$request->validate([
            'backup' => 'required|file|mimes:sql|max:102400',
        ]);*/

        $file = $request->file('backup');

        $host = config('database.connections.mysql.host');
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $port = config('database.connections.mysql.port', 3306);

        // XAMPP
        $mysql = 'C:\\xampp\\mysql\\bin\\mysql.exe';

        if (!file_exists($mysql)) {
            return back()->with(
                'error',
                'No se encontró mysql.exe en: ' . $mysql
            );
        }

        $sqlFile = $file->getRealPath();

        /*
        |--------------------------------------------------------------------------
        | 1. Obtener todas las tablas
        |--------------------------------------------------------------------------
        */

        $pdo = DB::connection()->getPdo();

        $tables = DB::select(
            "SELECT TABLE_NAME 
         FROM information_schema.TABLES 
         WHERE TABLE_SCHEMA = ?",
            [$database]
        );

        /*
        |--------------------------------------------------------------------------
        | 2. Eliminar las tablas existentes
        |--------------------------------------------------------------------------
        */

        try {

            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            foreach ($tables as $table) {
                $tableName = $table->TABLE_NAME;

                DB::statement(
                    'DROP TABLE IF EXISTS `' . str_replace('`', '``', $tableName) . '`'
                );
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

        } catch (\Throwable $e) {

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            return back()->with(
                'error',
                'No se pudieron eliminar las tablas existentes: ' . $e->getMessage()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Restaurar el SQL
        |--------------------------------------------------------------------------
        */

        $passwordOption = '';

        if (!empty($password)) {
            $passwordOption = '-p' . escapeshellarg($password);
        }

        $command = sprintf(
            'cmd /c ""%s" -h%s -P%s -u%s %s %s < "%s""',
            $mysql,
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            $passwordOption,
            escapeshellarg($database),
            $sqlFile
        );

        exec($command, $output, $result);

        /*
        |--------------------------------------------------------------------------
        | 4. Verificar resultado
        |--------------------------------------------------------------------------
        */

        if ($result !== 0) {

            $error = implode("\n", $output);

            return back()->with(
                'error',
                'Las tablas fueron eliminadas, pero ocurrió un error al restaurar el backup: ' . $error
            );
        }

        return back()->with(
            'success',
            'La base de datos fue limpiada y restaurada correctamente.'
        );
    }
}
