<?php

namespace App\Http\Transformers;

use App\Models\Rtm;
use League\Fractal\TransformerAbstract;

class RtmKetenagakerjaanTransformer extends TransformerAbstract
{
    public function transform(Rtm $item)
    {
        $statuses = optional($item->dataPresisiKesehatans)->pluck('status_pengisian') ?? collect();
        $status = $statuses->every(fn ($s) => $s === 'lengkap') ? 'Sudah Diisi' : 'Belum Diisi';

        $tanggal = optional($item->dataPresisiKesehatan)->tanggal_pengisian;

        return [
            'id' => $item->id,
            'config_id' => $item->config_id,
            'nik' => optional($item->kepalaKeluarga)->nik ?? 'TIDAK TAHU',
            'no_kk' => $item->no_kk,
            'kepala_keluarga' => optional($item->kepalaKeluarga)->nama ?? 'TIDAK TAHU',
            'dtks' => $item->terdaftar_dtks ? 'Terdaftar' : 'Tidak Terdaftar',
            'jumlah_anggota' => $item->anggota->count(),
            'jenis_pekerjaan' => $item->ho_anggota?->dataPresisiKetenagakerjaan?->jenis_pekerjaan ?? 'TIDAK TAHUN',
            'tempat_kerja' => $item->ho_anggota?->dataPresisiKetenagakerjaan?->tempat_kerja ?? 'TIDAK TAHUN',
            'frekwensi_mengikuti_pelatihan_setahun' => $item->ho_anggota?->dataPresisiKetenagakerjaan?->frekwensi_mengikuti_pelatihan_setahun ?? 'TIDAK TAHUN',
            'jenis_pelatihan_diikuti_setahun' => $item->ho_anggota?->dataPresisiKetenagakerjaan?->jenis_pelatihan_diikuti_setahun ?? 'TIDAK TAHUN',
            'jumlah_kk' => $item->jumlah_kk,
            'alamat' => $item->kepalaKeluarga->alamat_wilayah,
            'dusun' => $item->kepalaKeluarga->keluarga->wilayah->dusun,
            'rw' => $item->kepalaKeluarga->keluarga->wilayah->rw,
            'rt' => $item->kepalaKeluarga->keluarga->wilayah->rt,
            'status' => $status,
            'status_html' => '<span class="label label-' . ($status === 'Sudah Diisi' ? 'primary' : 'danger') . '">' . $status . '</span>',
            'tanggal_pengisian' => $tanggal ? date('d-m-Y', strtotime($tanggal)) : '-',
            'tgl_daftar' => $item->tgl_daftar ?? 'TIDAK TAHU',
        ];

    }
}
