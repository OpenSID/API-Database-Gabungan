<?php

namespace App\Http\Transformers;

use App\Models\Config;
use App\Models\Rtm;
use League\Fractal\TransformerAbstract;

class RtmTransformer extends TransformerAbstract
{
    public function transform(Rtm $item)
    {
        $statuses = $item->dataPresisiKesehatans->pluck('status_pengisian');
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
            'jumlah_pakaian_yang_dimiliki' => $item->ho_anggota?->sandang?->jml_pakaian_yg_dimiliki,
            'frekwensi_beli_pakaian' => $item->ho_anggota?->sandang?->frekwensi_beli_pakaian_pertahun,
            'jns_ansuransi' => $item->ho_anggota?->dataPresisiKesehatan?->jns_ansuransi,
            'jns_penggunaan_alat_kontrasepsi' => $item->ho_anggota?->dataPresisiKesehatan?->jns_penggunaan_alat_kontrasepsi,
            'jns_penyakit_diderita' => $item->ho_anggota?->dataPresisiKesehatan?->jns_penyakit_diderita,
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
