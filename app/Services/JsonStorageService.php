<?php

namespace App\Services;

class JsonStorageService
{
    private string $baseDir;

    public function __construct(?string $baseDir = null)
    {
        $this->baseDir = $baseDir ?: storage_path('app/data');
        if (!is_dir($this->baseDir)) {
            mkdir($this->baseDir, 0755, true);
        }
    }

    private function path(string $filename): string
    {
        $filename = basename($filename);
        $file = $this->baseDir . DIRECTORY_SEPARATOR . $filename;
        if (!file_exists($file)) {
            file_put_contents($file, json_encode([], JSON_PRETTY_PRINT));
        }
        return $file;
    }

    public function read(string $filename, array $default = []): array
    {
        $file = $this->path($filename);
        $handle = fopen($file, 'c+');
        if (!$handle) {
            return $default;
        }

        $records = $default;
        if (flock($handle, LOCK_SH)) {
            $size = filesize($file);
            $raw = $size > 0 ? fread($handle, $size) : '';
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $records = $decoded;
            }
            flock($handle, LOCK_UN);
        }
        fclose($handle);

        return $records;
    }

    public function write(string $filename, array $records): bool
    {
        $file = $this->path($filename);
        $handle = fopen($file, 'c+');
        if (!$handle) {
            return false;
        }

        $success = false;
        if (flock($handle, LOCK_EX)) {
            ftruncate($handle, 0);
            rewind($handle);
            $encoded = json_encode(array_values($records), JSON_PRETTY_PRINT);
            if ($encoded !== false && fwrite($handle, $encoded) !== false) {
                fflush($handle);
                $success = true;
            }
            flock($handle, LOCK_UN);
        }
        fclose($handle);

        return $success;
    }

    public function atomicInsert(string $filename, callable $callback): array
    {
        $file = $this->path($filename);
        $handle = fopen($file, 'c+');
        if (!$handle) {
            return ['success' => false, 'message' => 'Unable to open storage file.'];
        }

        $result = ['success' => false, 'message' => 'Unknown error'];
        if (flock($handle, LOCK_EX)) {
            $size = filesize($file);
            $raw = $size > 0 ? fread($handle, $size) : '';
            $records = json_decode($raw, true);
            if (!is_array($records)) {
                $records = [];
            }

            $result = $callback($records);
            if (!empty($result['success'])) {
                ftruncate($handle, 0);
                rewind($handle);
                $encoded = json_encode(array_values($records), JSON_PRETTY_PRINT);
                if ($encoded !== false) {
                    fwrite($handle, $encoded);
                    fflush($handle);
                }
            }
            flock($handle, LOCK_UN);
        }
        fclose($handle);

        return $result;
    }
}