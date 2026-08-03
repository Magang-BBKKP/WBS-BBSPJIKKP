<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ReportFormField extends Model
{
    use HasFactory;

    public const TYPES = [
        'text' => 'Jawaban singkat',
        'textarea' => 'Paragraf',
        'number' => 'Angka',
        'date' => 'Tanggal',
        'select' => 'Dropdown',
        'radio' => 'Pilihan ganda',
        'checkbox' => 'Kotak centang',
    ];

    public const SOURCE_BASE = 'base';
    public const SOURCE_CUSTOM = 'custom';

    protected $fillable = [
        'label',
        'name',
        'type',
        'placeholder',
        'help_text',
        'options',
        'is_required',
        'is_active',
        'sort_order',
        'source',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('label');
    }

    public function scopeBase($query)
    {
        return $query->where('source', self::SOURCE_BASE);
    }

    public function scopeCustom($query)
    {
        return $query->where('source', self::SOURCE_CUSTOM);
    }

    public static function forLaporanForm()
    {
        return static::custom()->active()->get();
    }

    public static function baseForLaporanForm()
    {
        return static::base()->active()->get();
    }

    public static function baseDefaults(): array
    {
        return [
            [
                'label' => 'Kategori Laporan',
                'name' => 'kategori_id',
                'type' => 'select',
                'placeholder' => null,
                'help_text' => 'Pilih jenis utama pelanggaran yang ingin Anda laporkan.',
                'options' => null,
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 1,
                'source' => self::SOURCE_BASE,
            ],
            [
                'label' => 'Judul Laporan',
                'name' => 'judul',
                'type' => 'text',
                'placeholder' => 'Ringkasan singkat pelanggaran yang dilaporkan',
                'help_text' => null,
                'options' => null,
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 2,
                'source' => self::SOURCE_BASE,
            ],
            [
                'label' => 'Deskripsi Kejadian',
                'name' => 'deskripsi',
                'type' => 'textarea',
                'placeholder' => 'Ceritakan secara detail: apa yang terjadi, kapan, di mana, siapa yang terlibat, dan bagaimana kejadiannya. (minimal 50 karakter)',
                'help_text' => null,
                'options' => null,
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 3,
                'source' => self::SOURCE_BASE,
            ],
            ['label' => 'Tanggal Kejadian', 'name' => 'tanggal_kejadian', 'type' => 'date', 'placeholder' => null, 'help_text' => null, 'options' => null, 'is_required' => false, 'is_active' => true, 'sort_order' => 4, 'source' => self::SOURCE_BASE],
            ['label' => 'Lokasi Kejadian', 'name' => 'lokasi', 'type' => 'text', 'placeholder' => 'Gedung / Unit / Lokasi', 'help_text' => null, 'options' => null, 'is_required' => false, 'is_active' => true, 'sort_order' => 5, 'source' => self::SOURCE_BASE],
            ['label' => 'Nama Terlapor', 'name' => 'nama_terlapor', 'type' => 'text', 'placeholder' => 'Nama lengkap', 'help_text' => null, 'options' => null, 'is_required' => false, 'is_active' => true, 'sort_order' => 6, 'source' => self::SOURCE_BASE],
            ['label' => 'Jabatan Terlapor', 'name' => 'jabatan_terlapor', 'type' => 'text', 'placeholder' => 'Jabatan', 'help_text' => null, 'options' => null, 'is_required' => false, 'is_active' => true, 'sort_order' => 7, 'source' => self::SOURCE_BASE],
            ['label' => 'Unit / Bagian Terlapor', 'name' => 'unit_terlapor', 'type' => 'text', 'placeholder' => 'Unit kerja', 'help_text' => null, 'options' => null, 'is_required' => false, 'is_active' => true, 'sort_order' => 8, 'source' => self::SOURCE_BASE],
            ['label' => 'Lapor Secara Anonim', 'name' => 'is_anonim', 'type' => 'checkbox', 'placeholder' => null, 'help_text' => 'Identitas Anda akan disembunyikan dan tidak diketahui siapapun.', 'options' => null, 'is_required' => false, 'is_active' => true, 'sort_order' => 9, 'source' => self::SOURCE_BASE],
            ['label' => 'Nama Pelapor', 'name' => 'nama_pelapor', 'type' => 'text', 'placeholder' => 'Nama lengkap Anda', 'help_text' => null, 'options' => null, 'is_required' => true, 'is_active' => true, 'sort_order' => 10, 'source' => self::SOURCE_BASE],
            ['label' => 'Email Pelapor', 'name' => 'email_pelapor', 'type' => 'text', 'placeholder' => 'email@contoh.com', 'help_text' => null, 'options' => null, 'is_required' => true, 'is_active' => true, 'sort_order' => 11, 'source' => self::SOURCE_BASE],
            ['label' => 'No. Telepon Pelapor', 'name' => 'telepon_pelapor', 'type' => 'text', 'placeholder' => '08xx-xxxx-xxxx', 'help_text' => null, 'options' => null, 'is_required' => false, 'is_active' => true, 'sort_order' => 12, 'source' => self::SOURCE_BASE],
            ['label' => 'Upload Bukti', 'name' => 'bukti', 'type' => 'text', 'placeholder' => null, 'help_text' => 'Lampirkan dokumen, foto, atau file pendukung laporan Anda. (Opsional, maks. 10 file @ 10 MB)', 'options' => null, 'is_required' => false, 'is_active' => true, 'sort_order' => 13, 'source' => self::SOURCE_BASE],
        ];
    }

    public function isBaseField(): bool
    {
        return $this->source === self::SOURCE_BASE;
    }

    public static function makeName(string $label): string
    {
        $base = Str::slug($label, '_') ?: 'field';
        $name = $base;
        $counter = 2;

        while (self::where('name', $name)->exists()) {
            $name = $base . '_' . $counter;
            $counter++;
        }

        return $name;
    }

    public function usesOptions(): bool
    {
        return in_array($this->type, ['select', 'radio', 'checkbox'], true);
    }
}
