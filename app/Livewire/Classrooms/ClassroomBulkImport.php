<?php

namespace App\Livewire\Classrooms;

use App\Models\Birim;
use App\Models\Bolum;
use App\Models\Building;
use App\Models\Campus;
use App\Models\Classroom;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ClassroomBulkImport extends Component
{

    public function readExcel($filePath)
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        $header = array_shift($data);

        foreach ($data as $row) {
            [$campusName, $buildingName, $isActive, $classroomName,$mekSisId, $type, $bagliOlanBirim, $capacity,  $examCapacity] = $row;

            $campus = Campus::firstOrCreate(['name' => $campusName]);

            $building = Building::firstOrCreate([
                'campus_id' => $campus->id,
                'name' => $buildingName,

            ]);
            $classroom = Classroom::firstOrCreate([
                'name' => $classroomName,
                'building_id' => $building->id,
                'type' => $type,
                'is_active'=> strtolower($isActive) == 'aktif',
            ], [
                'class_capacity' => $capacity,
                'exam_capacity' => $examCapacity,
            ]);

            $bolum = Bolum::where('name',$bagliOlanBirim)->first();
            $birim=  Birim::where('name',$bagliOlanBirim)->first();

            if ($bolum) {
                $classroom->bolums()->syncWithoutDetaching([$bolum->id]);
            }

            if ($birim) {
                $classroom->birims()->syncWithoutDetaching([$birim->id]);
            }

        }

        session()->flash('success', 'Veriler başarıyla kaydedildi!');
    }
    public function testReadExcel()
    {
        $this->readExcel(('C:\Users\NEVU\Downloads/ExcelFile.xls'));
    }

    public function render()
    {
        return view('livewire.classrooms.classrooms-bulk-import');
    }
}
