<?php

namespace App\Livewire;

use Jantinnerezo\LivewireAlert\LivewireAlert;

// Importar la clase
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class Backup extends Component
{
    use LivewireAlert;

    // Agregar el trait LivewireAlert

    public $backups = [];

    public function mount()
    {
        $this->cargarBackups();
    }

    public function cargarBackups()
    {
        $this->backups = Storage::disk('local')->files('backups');
    }

    public function crearBackup()
    {
        // Obtener los valores de las variables de entorno
        $dbHost = env('DB_HOST');
        $dbUsername = env('DB_USERNAME');
        $dbPassword = env('DB_PASSWORD');
        $dbDatabase = env('DB_DATABASE');

        // Definir el nombre del archivo de respaldo SQL
        $timestamp = now()->format('Ymd_His');
        $sqlFilename = "backup_{$timestamp}.sql";
        $path = storage_path("app/backups/{$sqlFilename}");

        // Comando para generar el backup de la base de datos usando las variables de entorno
        $command = "mysqldump --user={$dbUsername} --password={$dbPassword} --host={$dbHost} {$dbDatabase} > {$path}";

        // Ejecutar el comando mysqldump
        exec($command);

        // Crear un archivo ZIP para comprimir tanto el SQL como los archivos de la carpeta 'public'
        $zipFilename = "backup_{$timestamp}.zip";
        $zipPath = storage_path("app/backups/{$zipFilename}");

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            // Añadir el archivo SQL al ZIP
            $zip->addFile($path, $sqlFilename);

            // Obtener los archivos de la carpeta 'public'
            $publicPath = storage_path('app/public');
            $files = $this->getFilesFromDirectory($publicPath);

            // Añadir archivos al ZIP manteniendo solo la estructura de carpetas dentro de 'public'
            foreach ($files as $file) {
                $relativePath = str_replace(storage_path('app/public') . DIRECTORY_SEPARATOR, '', $file);
                $folderName = basename(dirname($relativePath));
                $zip->addEmptyDir($folderName);
                $zip->addFile($file, "$folderName/" . basename($file));
            }
            // Cerrar el archivo ZIP
            $zip->close();
        }

        // Eliminar el archivo SQL no comprimido si lo deseas
        unlink($path);

        $this->cargarBackups(); // Actualiza la lista de backups

        // Mostrar alerta con LivewireAlert
        $this->alert('success', 'Backup creado y comprimido exitosamente.', [
            'position' => 'bottom-end',
            'timer' => 2000,
            'toast' => true,
            'timerProgressBar' => true,
        ]);
    }

    public function getFilesFromDirectory($directory)
    {
        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory)
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $files[] = $file->getRealPath();
            }
        }

        return $files;
    }


    public function descargarBackup($filename)
    {
        $path = storage_path("app/{$filename}");
        return response()->download($path);
    }

    public function borrarBackup($filename)
    {
        // Comprobar si el archivo existe antes de intentar eliminarlo
        $path = storage_path("app/{$filename}");
        if (file_exists($path)) {
            unlink($path);
            $this->cargarBackups();
            $this->alert('success', 'Backup eliminado exitosamente.', [
                'position' => 'bottom-end',
                'timer' => 2000,
                'toast' => true,
                'timerProgressBar' => true,
            ]);
        } else {
            $this->alert('error', 'El archivo no se encontró.', [
                'position' => 'bottom-end',
                'timer' => 2000,
                'toast' => true,
                'timerProgressBar' => true,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.backup');
    }
}
