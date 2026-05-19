<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departamento;
use App\Models\Municipio;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Estructura de departamentos con sus municipios clave asignados
        $datosGeograficos = [
            'Boaco'         => ['Boaco', 'Camoapa', 'San Lorenzo'],
            'Carazo'        => ['Diriamba', 'Jinotepe', 'San Marcos'],
            'Chinandega'    => ['Chinandega', 'Corinto', 'El Viejo'],
            'Chontales'     => ['Juigalpa', 'Acoyapa', 'Santo Tomás'],
            'Estelí'        => ['Estelí', 'Condega', 'San Juan de Limay'],
            'Granada'       => ['Granada', 'Nandaime', 'Diriá', 'Diriomo'],
            'Jinotega'      => ['Jinotega', 'San Rafael del Norte', 'Wiwilí'],
            'León'          => ['León', 'Nagarote', 'La Paz Centro'],
            'Madriz'        => ['Somoto', 'Totogalpa', 'Yalagüina'],
            'Managua'       => ['Managua', 'Ciudad Sandino', 'Tipitapa', 'El Crucero'],
            'Masaya'        => ['Masaya', 'Nindirí', 'Masatepe', 'Catarina', 'Tisma'],
            'Matagalpa'     => ['Matagalpa', 'Sébaco', 'San Ramón'],
            'Nueva Segovia' => ['Ocotal', 'Jalapa', 'Dipilto'],
            'Río San Juan'  => ['San Carlos', 'El Castillo', 'Morrito'],
            'Rivas'         => ['Rivas', 'San Juan del Sur', 'Tola', 'Moyogalpa']
        ];

        foreach ($datosGeograficos as $deptoNombre => $municipios) {
            // 1. Creamos o buscamos el Departamento
            $departamento = Departamento::firstOrCreate([
                'nombre' => $deptoNombre
            ]);

            // 2. Creamos cada uno de sus municipios vinculados
            foreach ($municipios as $muniNombre) {
                Municipio::firstOrCreate([
                    'departamento_id' => $departamento->id,
                    'nombre'          => $muniNombre
                ]);
            }
        }
    }
}