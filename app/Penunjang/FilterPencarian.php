<?php

namespace App\Penunjang;

use Illuminate\Database\Eloquent\Builder;

/** Helper pencarian & filter daftar data panel. */
class FilterPencarian
{
    public static function kataKunci(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value !== '' ? $value : null;
    }

    /**
     * Terapkan pencarian OR ke beberapa kolom / relasi.
     *
     * @param  array<int, string|callable(Builder, string): void>  $handlers
     */
    public static function terapkan(Builder $query, ?string $q, array $handlers): Builder
    {
        if (! $q) {
            return $query;
        }

        return $query->where(function (Builder $sub) use ($q, $handlers) {
            foreach ($handlers as $handler) {
                if (is_callable($handler)) {
                    $handler($sub, $q);
                } else {
                    $sub->orWhere($handler, 'like', '%'.$q.'%');
                }
            }
        });
    }
}
