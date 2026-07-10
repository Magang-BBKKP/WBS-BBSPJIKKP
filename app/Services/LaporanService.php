<?php

namespace App\Services;

use App\Models\Bukti;
use App\Models\Laporan;
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

            // 2. Sanitize data anonim — hapus identitas jika anonim
            if ($data['is_anonim'] ?? true) {
                $data['nama_pelapor']     = null;
                $data['email_pelapor']    = null;
                $data['telepon_pelapor']  = null;
            }

            // 3. Simpan laporan
            $laporan = $this->laporanRepository->create($data);

            // 4. Upload & simpan bukti
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
}
