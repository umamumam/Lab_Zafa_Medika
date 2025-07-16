<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'metode',
        'nilai_normal',
        'type',
        'min',
        'max',
        'satuan',
        'harga_umum',
        'harga_bpjs',
        'grup_test',
        'sub_grup',
        'jenis_sampel',
        'interpretasi',
        'status'
    ];

    public static function rules(): array
    {
        $rules = [
            'kode' => 'required|string|max:50',
            'nama' => 'required|string|max:255',
            'metode' => 'required|string|max:255',
            'nilai_normal' => 'required|string|max:255',
            'type' => ['required', Rule::in(['Single', 'Range'])],
            'min' => 'nullable|numeric|decimal:0,2',
            'max' => 'nullable|numeric|decimal:0,2',
            'satuan' => 'required|string|max:50',
            'harga_umum' => 'required|integer|min:0',
            'harga_bpjs' => 'required|integer|min:0',
            'grup_test' => ['required', Rule::in(['Hematologi', 'Kimia Klinik', 'Imunologi / Serologi', 'Mikrobiologi', 'Khusus', 'Lainnya'])],
            'sub_grup' => ['required', Rule::in(['Cairan dan Parasitologi (E1)', 'Elektrometri (D1)', 'Endokrin Metabolik (B1)', 'Faal Ginjal (B3)', 'Faal Hati (B2)', 'Faal Hemotsasis (A2)', 'Faal Tiroid (B5)', 'Hematologi (A1)', 'Imunologi / Serologi (B4)', 'Marker Infeksi / Inflamasi (C1)', 'Marker Jantung (C2)', 'Lain - Lain (D2)'])],
            'jenis_sampel' => ['required', Rule::in(['Whole Blood EDTA', 'Whole Blood Heparin', 'Serum', 'Plasma Citrat', 'Urin', 'Feaces', 'Sputum', 'Cairan', 'LCS', 'Preparat', 'Swab'])],
            'interpretasi' => 'nullable|string',
            'status' => ['required', Rule::in(['Aktif', 'Tidak Aktif'])]
        ];

        // Dynamic validation for Range type
        if (request()->input('type') === 'Range') {
            $rules['min'] = 'required|numeric|lt:max';
            $rules['max'] = 'required|numeric|gt:min';
        }

        return $rules;
    }

    public static function validate(array $data): array
    {
        return validator($data, static::rules())->validate();
    }
    public function detailTests()
    {
        return $this->hasMany(DetailTest::class)->orderBy('urutan');
    }
    public function visitTests()
    {
        return $this->hasMany(VisitTest::class);
    }
    public function hasilLabs()
    {
        return $this->hasMany(HasilLab::class, 'test_id');
    }
}
