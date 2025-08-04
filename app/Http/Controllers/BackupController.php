<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log; // Import Log facade

class BackupController extends Controller
{
    public function backup()
    {
        try {
            Artisan::call('backup:run', ['--only-db' => true]);
            $output = Artisan::output();
            Log::info('Backup command output: ' . $output);

            return response()->json(['message' => 'Backup berhasil dijalankan!']);
        } catch (\Exception $e) {
            Log::error('Backup failed via HTTP: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal menjalankan backup: ' . $e->getMessage()], 500);
        }
    }
}
