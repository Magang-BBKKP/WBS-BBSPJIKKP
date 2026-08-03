<?php

namespace App\Services;

use App\Models\Bukti;
use App\Models\Kategori;
use App\Models\Laporan;
use App\Models\ReportFormField;
use App\Repositories\Contracts\LaporanRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LaporanService extends BaseService
{
    public function __construct(
        protected LaporanRepositoryInterface $laporanRepository
    ) {}

    /**
     * Proses submit laporan baru + upload bukti
     *
     * @param array $data  Validated form data
     * @param array $files Array of UploadedFile (from $request->file('bukti'))
     * @return Laporan
     */
    public function submitLaporan(array $data, array $files = []): Laporan
    {
        return DB::transaction(function () use ($data, $files) {
            // 1. Generate nomor registrasi & tracking token
            $data['nomor_registrasi'] = $this->laporanRepository->generateNomorRegistrasi();
            $data['tracking_token']   = $this->laporanRepository->generateTrackingToken();

            // 2. Simpan identitas asli pelapor di database dari user yang sedang login
            $user = auth()->user();
            if ($user) {
                if ($data['is_anonim'] ?? false) {
                    $data['nama_pelapor']    = $user->name;
                    $data['email_pelapor']   = $user->email;
                    $data['telepon_pelapor'] = $user->phone_number;
                } else {
                    $data['nama_pelapor']    = $data['nama_pelapor'] ?: $user->name;
                    $data['email_pelapor']   = $data['email_pelapor'] ?: $user->email;
                    $data['telepon_pelapor'] = $data['telepon_pelapor'] ?: $user->phone_number;
                }
            }

            // 3. Lengkapi nilai internal jika field bawaan disembunyikan dari form.
            $data['kategori_id'] = $data['kategori_id'] ?? Kategori::aktif()->value('id') ?? Kategori::query()->value('id');
            $data['judul'] = trim((string) ($data['judul'] ?? '')) ?: 'Laporan tanpa judul';
            $data['deskripsi'] = trim((string) ($data['deskripsi'] ?? '')) ?: 'Deskripsi tidak diisi melalui formulir.';
            $data['is_anonim'] = (bool) ($data['is_anonim'] ?? true);

            // 4. Simpan laporan
            $data['custom_fields'] = $this->formatCustomFields((array) ($data['custom_fields'] ?? []));

            $laporan = $this->laporanRepository->create($data);

            // 5. Upload & simpan bukti
            if (!empty($files)) {
                foreach ($files as $file) {
                    if ($file instanceof UploadedFile && $file->isValid()) {
                        $this->simpanBukti($laporan, $file);
                    }
                }
            }

            return $laporan;
        });
    }

    /**
     * Upload satu file bukti ke storage dan simpan record
     */
    private function simpanBukti(Laporan $laporan, UploadedFile $file): Bukti
    {
        $namaAsli = $file->getClientOriginalName();
        $namaFile = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path     = $file->storeAs('bukti/' . $laporan->id, $namaFile, 'public');

        return Bukti::create([
            'laporan_id' => $laporan->id,
            'nama_asli'  => $namaAsli,
            'nama_file'  => $namaFile,
            'path_file'  => $path,
            'mime_type'  => $file->getMimeType(),
            'ukuran'     => $file->getSize(),
        ]);
    }

    private function formatCustomFields(array $values): array
    {
        return ReportFormField::forLaporanForm()
            ->mapWithKeys(function (ReportFormField $field) use ($values) {
                $value = $values[$field->name] ?? null;

                if (is_array($value)) {
                    $value = collect($value)
                        ->map(fn ($item) => trim((string) $item))
                        ->filter()
                        ->values()
                        ->all();
                } elseif ($value !== null) {
                    $value = trim((string) $value);
                }

                return [$field->name => [
                    'label' => $field->label,
                    'type' => $field->type,
                    'value' => $value,
                ]];
            })
            ->filter(fn ($item) => $item['value'] !== null && $item['value'] !== '' && $item['value'] !== [])
            ->all();
    }
}
