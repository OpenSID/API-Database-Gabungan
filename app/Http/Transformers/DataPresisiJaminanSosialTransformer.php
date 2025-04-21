<?php

namespace App\Http\Transformers;

use App\Models\DataPresisiJaminanSosial;
use League\Fractal\TransformerAbstract;

class DataPresisiJaminanSosialTransformer extends TransformerAbstract
{
    /**
     * Resources that can be included if requested.
     */
    protected array $availableIncludes = ['keluarga', 'rtm', 'penduduk', 'config', 'listAnggota'];
    public function transform(DataPresisiJaminanSosial $item)
    {
        $item->id = $item->uuid;
        return $item->attributesToArray();
    }

    public function includeKeluarga(DataPresisiJaminanSosial $item)
    {
        return $this->item($item->keluarga, function ($item) {
            return [
                'id' => $item->id ?? null,
                'no_kk' => $item->no_kk ?? null,
                'alamat' => $item->alamat ?? null,
                'wilayah' => $item?->wilayah->attributesToArray() ?? null,
            ];
        },'keluarga');
    }
    public function includePenduduk(DataPresisiJaminanSosial $item)
    {
        return $this->item($item->penduduk, function ($item) {
            return [
                'id' => $item->id ?? null,
                'nik' => $item->nik ?? null,
                'nama' => $item->nama ?? null,
            ];
        }, 'penduduk');
    }

    public function includeConfig(DataPresisiJaminanSosial $item)
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

    public function includeRtm(DataPresisiJaminanSosial $item)
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

    public function includeListAnggota(DataPresisiJaminanSosial $item)
    {
        return $this->collection($item->listAnggota, function ($item) {
            return array_merge($item->attributesToArray() ,[
                'id' => $item->uuid ?? null,
                'nik' => $item->penduduk->nik ?? null,
                'nama' => $item->penduduk->nama ?? null,
            ]);
        }, 'anggota');
    }
}
