<?php

namespace App\Helpers;

class MapMarkerHelper
{
    public static function formatType(string $type): string
    {
        $typeLabels = [
            'area_koleksi' => 'Area Koleksi',
            'fasilitas_umum' => 'Fasilitas Umum',
            'kantor_pengelola' => 'Kantor Pengelola',
            'pos_keamanan' => 'Pos Keamanan'
        ];

        return $typeLabels[$type] ?? ucwords(str_replace('_', ' ', $type));
    }
}
