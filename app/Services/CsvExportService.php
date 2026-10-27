<?php

namespace App\Services;

class CsvExportService
{
    public function download(string $filename, array $headers, iterable $rows)
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $stream = fopen('php://output', 'w');
            fputcsv($stream, $headers);
            foreach ($rows as $row) {
                fputcsv($stream, array_map(function ($value) {
                    $value = (string) ($value ?? '');
                    return preg_match('/^[=+@\-\t\r]/', $value) ? "'" . $value : $value;
                }, $row));
            }
            fclose($stream);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
