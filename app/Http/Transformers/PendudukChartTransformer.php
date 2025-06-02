<?php

namespace App\Http\Transformers;

use App\Models\Penduduk;
use League\Fractal\TransformerAbstract;

class PendudukChartTransformer extends TransformerAbstract
{
    public function transform(Penduduk $penduduk)
    {
        return [
            'umur' => $penduduk->umur,
            'kode_kecamatan' => $penduduk->config->kode_kecamatan ?? null,
            'nama_kecamatan' => $penduduk->config->nama_kecamatan ?? null,
            'pendidikan_kk' => $penduduk->pendidikanKK->nama ?? null,
            'pendidikan' => $penduduk->pendidikan->nama ?? null,
            'agama' => $penduduk->agama->nama ?? null,
            'jenis_kelamin' => $penduduk->jenisKelamin->nama ?? null,
            'pekerjaan' => $penduduk->pekerjaan->nama ?? null,
            'status_kawin' => $penduduk->statusKawin->nama ?? null,
            'penduduk_hubungan' => $penduduk->pendudukHubungan->nama ?? null,
            'warga_negara' => $penduduk->wargaNegara->nama ?? null,
            'penduduk_status' => $penduduk->pendudukStatus->nama ?? null,
            'golongan_darah' => $penduduk->golonganDarah->nama ?? null,
            'cacat' => $penduduk->cacat->nama ?? null,
            'penyakit_menahun' => $penduduk->namaSakitMenahun ?? null,
            'akseptor-kb' => $penduduk->kb->nama ?? null,
            'status_rekam_ktp' => $penduduk->statusRekamKtp->nama ?? null,
            'asuransi_kesehatan' => $penduduk->namaAsuransi ?? null,
            'suku' => $penduduk->suku ?? null,
            'bpjs_ketenagakerjaan' => $penduduk->bpjs_ketenagakerjaan ?? null,
            'status_kehamilan' => $penduduk->statusHamil ?? null,
        ];
    }
}
