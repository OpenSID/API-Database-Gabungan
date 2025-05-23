<?php

namespace App\Http\Transformers;

use App\Enums\AgamaEnum;
use App\Enums\FrekwensiAktivitasKeagamaanEnum;
use App\Models\DataPresisiAgama;
use League\Fractal\TransformerAbstract;

class DataPresisiAgamaTransformer extends TransformerAbstract
{
    /**
     * Resources that can be included if requested.
     */
    protected array $availableIncludes = ['keluarga', 'rtm', 'penduduk', 'config', 'listAnggota'];
    public function transform(DataPresisiAgama $item)
    {
        $item->id = $item->uuid;
        $item->frekwensi = FrekwensiAktivitasKeagamaanEnum::getDescription($item->frekwensi_mengikuti_kegiatan_setahun) ?? null;
        $item->agama = AgamaEnum::getDescription($item->agama_id) ?? null;
        return $item->attributesToArray();
    }

    public function includeKeluarga(DataPresisiAgama $item)
    {
        return $this->item($item->keluarga, function ($item) {
            return [
                'id' => $item?->id ?? null,
                'no_kk' => $item?->no_kk ?? null,
                'alamat' => $item?->alamat ?? null,
                'wilayah' => $item?->wilayah->attributesToArray() ?? null,
            ];
        },'keluarga');
    }
    public function includePenduduk(DataPresisiAgama $item)
    {
        return $this->item($item->penduduk, function ($item) {
            return [
                'id' => $item->id ?? null,
                'nik' => $item->nik ?? null,
                'nama' => $item->nama ?? null,
                'keluarga' => $item->keluarga?->attributesToArray() ?? null,
            ];
        }, 'penduduk');
    }

    public function includeConfig(DataPresisiAgama $item)
    {
        return $this->item($item->config, function ($item) {
            return [
                'id' => $item->id ?? null,
                'nama_desa' => $item->nama_desa ?? null,
                'kode_desa' => $item->kode_desa ?? null,
                'nama_kecamatan' => $item->nama_kecamatan ?? null,
            ];
        }, 'config');
    }

    public function includeRtm(DataPresisiAgama $item)
    {
        return $this->item($item->rtm, function ($item) {
            return [
                'id' => $item->id ?? null,
                'jumlah_kk' => $item->jumlah_kk ?? null,
                'nik_kepala' => $item->nik_kepala ?? null,
                'no_kk' => $item->no_kk ?? null,
                'dtks' => $item->dtks ? [
                    'id' => $item->dtks->id ?? null,
                    'versi_kuisioner' => $item->dtks->versi_kuisioner ?? null,
                    'created_at' => $item->dtks->created_at ?? null,
                    'updated_at' => $item->dtks->updated_at ?? null,
                ] : null,
                'tgl_daftar' => $item->tgl_daftar ?? null,
                'nama_kepala' => $item->kepalaKeluargaSaja?->nama ?? null,
            ];
        }, 'rtm');
    }

    public function includeListAnggota(DataPresisiAgama $item)
    {
        return $this->collection($item->listAnggota, function ($item) {
            return array_merge($item->attributesToArray() ,[
                'id' => $item->uuid ?? null,
                'nik' => $item->penduduk->nik ?? null,
                'nama' => $item->penduduk->nama ?? null,
                'agama' => AgamaEnum::getDescription($item->agama_id) ?? null,
                'frekwensi' => FrekwensiAktivitasKeagamaanEnum::getDescription($item->frekwensi_mengikuti_kegiatan_setahun) ?? null
            ]);
        }, 'anggota');
    }
}
