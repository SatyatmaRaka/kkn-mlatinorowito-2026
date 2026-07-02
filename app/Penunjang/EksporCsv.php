<?php

namespace App\Penunjang;

use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Ekspor data ke file CSV (UTF-8 BOM) untuk logbook & absensi.
 */
class EksporCsv
{
    /**
     * @param  list<string>  $headers
     * @param  iterable<int, list<string>>  $rows
     */
    public static function download(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows): void {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, $headers);

            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
