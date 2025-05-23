<?php

namespace App\Models;

use App\Models\Enums\SasaranEnum;
use App\Models\Traits\ConfigIdTrait;
use Illuminate\Support\Facades\DB;

class BantuanPeserta extends BaseModel
{
    use ConfigIdTrait;

    /** {@inheritdoc} */
    protected $table = 'program_peserta';

    /** {@inheritdoc} */
    protected $appends = [
        'nik',
        'no_kk',
        'jenis_kelamin',
        'keterangan',
    ];

    public function getNikAttribute()
    {
        return $this->penduduk?->nik;
    }

    public function getNoKKAttribute()
    {
        return $this->penduduk?->keluarga?->no_kk;
    }

    public function getJenisKelaminAttribute()
    {
        return $this->penduduk?->jenisKelamin;
    }

    public function getKeteranganAttribute()
    {
        return $this->penduduk?->pendudukStatusDasar;
    }

    /**
     * Define a one-to-many relationship.
     *
     * @return BelongsTo
     */
    public function bantuan()
    {
        return $this->belongsTo(Bantuan::class, 'program_id');
    }

    /**
     * Define a one-to-many relationship.
     *
     * @return BelongsTo
     */
    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'kartu_id_pend')->withRef();
    }

    public function bantuanPenduduk()
    {
        return $this->belongsTo(Bantuan::class, 'program_id')->where(['sasaran' => SasaranEnum::PENDUDUK]);
    }

    /**
     * Get the phone associated with the config.
     */
    public function config()
    {
        return $this->hasOne(Config::class, 'id', 'config_id');
    }

    public static function getPesertaProgram($cat, $id)
    {
        $data_program = DB::table('program_peserta as o')
            ->select(
                'p.id',
                'o.peserta as nik',
                'o.id as peserta_id',
                'p.nama',
                'p.sdate',
                'p.edate',
                'p.ndesc',
                'p.sasaran',
                DB::raw("
                    CASE
                        WHEN p.sdate <= CURDATE() AND p.edate >= CURDATE() THEN 1
                        ELSE 0
                    END as status
                ")
            )
            ->join('program as p', 'p.id', '=', 'o.program_id')
            ->where('o.peserta', $id)
            ->where('p.sasaran', $cat)
            ->get();

        if ($data_program->isEmpty()) {
            return null;
        }

        $profil = match ((int) $cat) {
            // Penduduk
            SasaranEnum::PENDUDUK => DB::table('tweb_penduduk as o')
                    ->select('o.nama', 'o.foto', 'o.nik', 'w.rt', 'w.rw', 'w.dusun')
                    ->join('tweb_wil_clusterdesa as w', 'w.id', '=', 'o.id_cluster')
                    ->where('o.nik', $id)
                    ->first(),
            // KK
            SasaranEnum::KELUARGA => DB::table('tweb_keluarga as o')
                    ->select('o.nik_kepala', 'o.no_kk', 'p.nama', 'w.rt', 'w.rw', 'w.dusun')
                    ->join('tweb_penduduk as p', 'o.nik_kepala', '=', 'p.id')
                    ->join('tweb_wil_clusterdesa as w', 'w.id', '=', 'p.id_cluster')
                    ->where('o.no_kk', $id)
                    ->first(),
            // RTM
            SasaranEnum::RUMAH_TANGGA => DB::table('tweb_rtm as r')
                    ->select('r.id', 'r.no_kk', 'o.nama', 'o.nik', 'w.rt', 'w.rw', 'w.dusun')
                    ->join('tweb_penduduk as o', 'o.id', '=', 'r.nik_kepala')
                    ->join('tweb_wil_clusterdesa as w', 'w.id', '=', 'o.id_cluster')
                    ->where('r.no_kk', $id)
                    ->first(),
            // Kelompok
            SasaranEnum::KELOMPOK => DB::table('kelompok as k')
                    ->select('k.id', 'k.nama', 'p.nama as ketua', 'p.nik', 'w.rt', 'w.rw', 'w.dusun')
                    ->join('tweb_penduduk as p', 'p.id', '=', 'k.id_ketua')
                    ->join('tweb_wil_clusterdesa as w', 'w.id', '=', 'p.id_cluster')
                    ->where('k.id', $id)
                    ->first(),
            default => null,
        };

        $profil_collection = collect(match ((int) $cat) {
            SasaranEnum::PENDUDUK => $profil ? [
                'id'    => $id,
                'nama'  => $profil->nama . ' - ' . $profil->nik,
                'ndesc' => 'Alamat: RT ' . strtoupper($profil->rt) . ' / RW ' . strtoupper($profil->rw) . ' ' . strtoupper($profil->dusun),
                'foto'  => $profil->foto,
            ] : [],
            SasaranEnum::KELUARGA => $profil ? [
                'id'    => $id,
                'nama'  => 'Kepala KK : ' . $profil->nama . ', NO KK: ' . $profil->no_kk,
                'ndesc' => 'Alamat: RT ' . strtoupper($profil->rt) . ' / RW ' . strtoupper($profil->rw) . ' ' . strtoupper($profil->dusun),
                'foto'  => '',
            ] : [],
            SasaranEnum::RUMAH_TANGGA => $profil ? [
                'id'    => $id,
                'nama'  => 'Kepala RTM : ' . $profil->nama . ', NIK: ' . $profil->nik,
                'ndesc' => 'Alamat: RT ' . strtoupper($profil->rt) . ' / RW ' . strtoupper($profil->rw) . ' ' . strtoupper($profil->dusun),
                'foto'  => '',
            ] : [],
            SasaranEnum::KELOMPOK => $profil ? [
                'id'    => $id,
                'nama'  => $profil->nama,
                'ndesc' => 'Ketua: ' . $profil->ketua . ' [' . $profil->nik . ']<br />Alamat: RT ' . strtoupper($profil->rt) . ' / RW ' . strtoupper($profil->rw) . ' ' . strtoupper($profil->dusun),
                'foto'  => '',
            ] : [],
            default => [],
        });

        return collect([
            'programkerja' => $data_program,
            'profil'       => $profil_collection,
        ]);
    }

}
