<?php

namespace App\Http\Transformers;

use App\Models\Config;
use App\Models\Rtm;
use League\Fractal\TransformerAbstract;

class RtmPanganTransformer extends TransformerAbstract
{
    public function transform(Rtm $item)
    {
        $statuses = optional($item->dataPresisiKesehatans)->pluck('status_pengisian') ?? collect();
        $status = $statuses->every(fn ($s) => $s === 'lengkap') ? 'Sudah Diisi' : 'Belum Diisi';

        $tanggal = optional($item->dataPresisiKesehatan)->tanggal_pengisian;

        $pangan = $item->dataPresisiPangan;

        return [
            'id' => $item->id,
            'config_id' => $item->config_id,
            'nik' => optional($item->kepalaKeluarga)->nik ?? 'TIDAK TAHU',
            'no_kk' => $item->no_kk,
            'kepala_keluarga' => optional($item->kepalaKeluarga)->nama ?? 'TIDAK TAHU',
            'dtks' => $item->terdaftar_dtks ? 'Terdaftar' : 'Tidak Terdaftar',
            'jumlah_anggota' => $item->anggota->count(),
            'jenis_lahan' => $pangan?->jenis_lahan ?? 'TIDAK TAHU',
            'luas_lahan' => $pangan?->luas_lahan ?? 'TIDAK TAHU',
            'luas_tanam' => $pangan?->luas_tanam ?? 'TIDAK TAHU',
            'status_lahan' => $pangan?->status_lahan ?? 'TIDAK TAHU',
            'komoditi_utama_tanaman_pangan' => $pangan?->komoditi_utama_tanaman_pangan ?? 'TIDAK TAHU',
            'komoditi_tanaman_pangan_lainnya' => $pangan?->komoditi_tanaman_pangan_lainnya ?? 'TIDAK TAHU',
            'jumlah_berdasarkan_jenis_komoditi' => $pangan?->jumlah_berdasarkan_jenis_komoditi ?? 'TIDAK TAHU',
            'usia_komoditi' => $pangan?->usia_komoditi ?? 'TIDAK TAHU',
            'jenis_peternakan' => $pangan?->jenis_peternakan ?? 'TIDAK TAHU',
            'jumlah_populasi' => $pangan?->jumlah_populasi ?? 'TIDAK TAHU',
            'jenis_perikanan' => $pangan?->jenis_perikanan ?? 'TIDAK TAHU',
            'frekwensi_makanan_perhari' => $pangan?->frekwensi_makanan_perhari ?? 'TIDAK TAHU',
            'frekwensi_konsumsi_sayur_perhari' => $pangan?->frekwensi_konsumsi_sayur_perhari ?? 'TIDAK TAHU',
            'frekwensi_konsumsi_buah_perhari' => $pangan?->frekwensi_konsumsi_buah_perhari ?? 'TIDAK TAHU',
            'frekwensi_konsumsi_daging_perhari' => $pangan?->frekwensi_konsumsi_daging_perhari ?? 'TIDAK TAHU',
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
