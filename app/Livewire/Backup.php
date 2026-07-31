<?php

namespace App\Livewire;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class Backup extends Component
{
    use LivewireAlert;

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
        $dbHost = env('DB_HOST');
        $usuarioBD = env('DB_USERNAME');
        $contraseniaDB = env('DB_PASSWORD');
        $basededatosUsada = env('DB_DATABASE');

        $timestamp = now()->format('Ymd_His');
        $archivoSQL = "backup_{$timestamp}.sql";
        $rutadelSQL = storage_path("app/backups/{$archivoSQL}");

        $command = "mysqldump --user={$usuarioBD} --password={$contraseniaDB} --host={$dbHost} {$basededatosUsada} > {$rutadelSQL}";

        /*Validamos  que los datos de conexión a la base de datos sean correctos*/
        try {
            exec($command, $output, $result);
            if ($result !== 0) {
                throw new \Exception("Error al ejecutar mysqldump. Verifica las credenciales o la configuración del entorno.");
            }
        } catch (\Exception $e) {
            $this->alert('error', $e->getMessage(), [
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true,
            ]);
            return;
        }
        /*Definimios el nombre del archivo ZIP y la ruta donde se guardará*/
        $nombreZip = "backup_{$timestamp}.zip";
        $rutaZip = storage_path("app/backups/{$nombreZip}");

        /*Creamos el ZIP y lo abrimos para agregar los archivos*/
        $zip = new ZipArchive();
        if ($zip->open($rutaZip, ZipArchive::CREATE) === TRUE) {

            $zip->addFile($rutadelSQL, $archivoSQL);

            /*Crear el directiorio 'app/public' dentro del ZIP*/
            $zip->addEmptyDir('app/public');

            /*Agregar las carpetas y archivos dentro de ellas*/
            $folders = ['categorias', 'marcas', 'productos'];
            foreach ($folders as $folder) {
                $rutaCarpeta = storage_path("app/public/{$folder}");
                if (is_dir($rutaCarpeta)) {
                    $files = $this->obtenerDirectorioArchivos($rutaCarpeta);
                    foreach ($files as $file) {
                       /*Mover los archivos dentro de la carpeta que le corresponde*/
                        if ($folder === 'marcas') {
                            $relativePath = "app/public/marca/" . basename($file);
                        } else {
                            $relativePath = "app/public/{$folder}/" . basename($file);
                        }
                        $zip->addFile($file, $relativePath);
                    }
                }
            }
            /*Cerramos el archivo ZIP*/
            $zip->close();
        } else {
            $this->alert('error', 'No se pudo crear el archivo ZIP.', [
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true,
            ]);
            return;
        }
        /*Eliminamos el archivo SQL no comprimido*/
        unlink($rutadelSQL);
        $this->cargarBackups();

        $this->alert('success', 'Backup creado y comprimido exitosamente.', [
            'position' => 'bottom-end',
            'timer' => 2000,
            'toast' => true,
        ]);
    }

    public function obtenerDirectorioArchivos($directory)
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
