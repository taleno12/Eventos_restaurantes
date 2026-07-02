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
        // Estructura completa de los 17 departamentos/regiones de Nicaragua
        // con TODOS sus municipios oficiales (según INIFOM).
        $datosGeograficos = [
            'Boaco' => [
                'Boaco', 'Camoapa', 'San José de los Remates', 'San Lorenzo',
                'Santa Lucía', 'Teustepe',
            ],
            'Carazo' => [
                'Diriamba', 'Dolores', 'El Rosario', 'Jinotepe',
                'La Conquista', 'La Paz de Carazo', 'San Marcos', 'Santa Teresa',
            ],
            'Chinandega' => [
                'Chichigalpa', 'Chinandega', 'Cinco Pinos', 'Corinto',
                'El Realejo', 'El Viejo', 'Posoltega', 'Puerto Morazán',
                'San Francisco del Norte', 'San Pedro del Norte',
                'Santo Tomás del Norte', 'Somotillo', 'Villanueva',
            ],
            'Chontales' => [
                'Acoyapa', 'Comalapa', 'El Coral', 'Juigalpa', 'La Libertad',
                'San Francisco de Cuapa', 'San Pedro de Lóvago', 'Santo Domingo',
                'Santo Tomás', 'Villa Sandino',
            ],
            'Estelí' => [
                'Condega', 'Estelí', 'La Trinidad', 'Pueblo Nuevo',
                'San Juan de Limay', 'San Nicolás',
            ],
            'Granada' => [
                'Diriá', 'Diriomo', 'Granada', 'Nandaime',
            ],
            'Jinotega' => [
                'El Cuá', 'Jinotega', 'La Concordia', 'San Rafael del Norte',
                'San Sebastián de Yalí', 'Santa María de Pantasma',
                'Wiwilí de Jinotega',
            ],
            'León' => [
                'Achuapa', 'El Jicaral', 'El Sauce', 'La Paz Centro',
                'Larreynaga (Malpaisillo)', 'León', 'Nagarote', 'Quezalguaque',
                'Santa Rosa del Peñón', 'Telica',
            ],
            'Madriz' => [
                'Las Sabanas', 'Palacagüina', 'San José de Cusmapa',
                'San Juan de Río Coco', 'San Lucas', 'Somoto', 'Telpaneca',
                'Totogalpa', 'Yalagüina',
            ],
            'Managua' => [
                'Ciudad Sandino', 'El Crucero', 'Managua', 'Mateare',
                'San Francisco Libre', 'San Rafael del Sur', 'Ticuantepe',
                'Tipitapa', 'Villa El Carmen',
            ],
            'Masaya' => [
                'Catarina', 'La Concepción', 'Masatepe', 'Masaya', 'Nandasmo',
                'Nindirí', 'Niquinohomo', 'San Juan de Oriente', 'Tisma',
            ],
            'Matagalpa' => [
                'Ciudad Darío', 'El Tuma - La Dalia', 'Esquipulas', 'Matagalpa',
                'Matiguás', 'Muy Muy', 'Rancho Grande', 'Río Blanco',
                'San Dionisio', 'San Isidro', 'San Ramón', 'Sébaco', 'Terrabona',
            ],
            'Nueva Segovia' => [
                'Ciudad Antigua', 'Dipilto', 'El Jícaro', 'Jalapa', 'Macuelizo',
                'Mozonte', 'Murra', 'Ocotal', 'Quilalí', 'San Fernando',
                'Santa María', 'Wiwilí',
            ],
            'Río San Juan' => [
                'El Almendro', 'El Castillo', 'Morrito', 'San Carlos',
                'San Juan del Norte', 'San Miguelito',
            ],
            'Rivas' => [
                'Altagracia', 'Belén', 'Buenos Aires', 'Cárdenas',
                'Moyogalpa', 'Potosí', 'Rivas', 'San Jorge',
                'San Juan del Sur', 'Tola',
            ],

            // ── REGIONES AUTÓNOMAS DE LA COSTA CARIBE ──
            // Quitá estos dos bloques si tu app no maneja las regiones autónomas
            // como "departamentos".
            'Región Autónoma de la Costa Caribe Norte' => [
                'Bonanza', 'Mulukukú', 'Prinzapolka', 'Puerto Cabezas',
                'Rosita', 'Siuna', 'Waslala', 'Waspán',
            ],
            'Región Autónoma de la Costa Caribe Sur' => [
                'Bluefields', 'Bocana de Paiwas', 'Corn Island',
                'Desembocadura de Río Grande', 'El Rama', 'El Tortuguero',
                'Kukra Hill', 'La Cruz de Río Grande', 'Laguna de Perlas',
                'Muelle de los Bueyes', 'Nueva Guinea',
            ],
        ];

        foreach ($datosGeograficos as $deptoNombre => $municipios) {
            // 1. Creamos o buscamos el Departamento
            $departamento = Departamento::firstOrCreate([
                'nombre' => $deptoNombre,
            ]);

            // 2. Creamos cada uno de sus municipios vinculados
            foreach ($municipios as $muniNombre) {
                Municipio::firstOrCreate([
                    'departamento_id' => $departamento->id,
                    'nombre'          => $muniNombre,
                ]);
            }
        }
    }
}
