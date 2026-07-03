<?php

namespace App\Penunjang;

use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Ekspor data ke file CSV (UTF-8 BOM) untuk logbook, absensi, keuangan, laporan.
 * Sel-sel yang berpotensi formula injection (=, +, -, @) disanitasi.
 */
class EksporCsv
{
    /**
     * @param  list<string>  $headers
     * @param  iterable<int, list<string|int|float|null>>  $rows
     */
    public static function download(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows): void {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, array_map([self::class, 'sanitizeCell'], $headers));

            foreach ($rows as $row) {
                fputcsv($handle, array_map([self::class, 'sanitizeCell'], $row));
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /** Cegah CSV/formula injection saat dibuka di Excel. */
    public static function sanitizeCell(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        $string = (string) $value;

        if ($string !== '' && preg_match('/^[=+\-@\t\r]/', $string)) {
            return "'".$string;
        }

        return $string;
    }
}
