<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Models;

use App\Models\Rtm;
use App\Models\Keluarga;
use App\Models\BaseModel;

class Papan extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'data_presisi_papan';

    protected $casts = [
        'created_at'          => 'date:Y-m-d H:i:s',
        'updated_at'          => 'date:Y-m-d H:i:s',
    ];

    // List of attributes that should be checked in Dtks first
    private $dtksAttributes = [
        'kd_stat_bangunan_tinggal',
        'luas_lantai',
        'kd_jenis_lantai_terluas',
        'kd_jenis_dinding',
        'kd_kondisi_dinding',
        'kd_jenis_atap',
        'kd_kondisi_atap',
        'kd_sumber_air_minum',
        'kd_jarak_sumber_air_ke_tpl',
        'kd_pembuangan_akhir_tinja',
        'kd_sumber_penerangan_utama',
        'kd_daya_terpasang',
        'kd_daya_terpasang2',
        'kd_daya_terpasang3'
    ];

    public static $dtksFieldMapping = [
        '301a' => 'kd_stat_bangunan_tinggal',        
        '303' => 'kd_jenis_lantai_terluas',
        '304' => 'kd_jenis_dinding',
        '305' => 'kd_jenis_atap',
        '306a' => 'kd_sumber_air_minum',
        '306b' => 'kd_jarak_sumber_air_ke_tpl',
        '310' => 'kd_pembuangan_akhir_tinja',
        '307a' => 'kd_sumber_penerangan_utama',
        '307b1' => 'kd_daya_terpasang',
        '307b2' => 'kd_daya_terpasang2',
        '307b3' => 'kd_daya_terpasang3'
    ];

    /**
     * Define relationship with Dtks model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function dtks()
    {
        return $this->hasOne(\App\Models\DTKS::class, 'id_rtm', 'id_rtm')
            ->where('versi_kuisioner', \App\Enums\Dtks\DtksEnum::VERSION_CODE);
    }

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function rtm()
    {
        return $this->hasOne(Rtm::class, 'id', 'id_rtm');
    }

    public function keluarga()
    {
        return $this->hasOne(Keluarga::class, 'id', 'id_keluarga');
    }

    public function kepalaRumahTangga()
    {
        return $this->hasOneThrough(
            \App\Models\Penduduk::class,
            \App\Models\Rtm::class,
            'id', // Foreign key on Rtm table
            'id', // Foreign key on Penduduk table
            'id_rtm', // Local key on Papan table
            'nik_kepala' // Local key on Rtm table
        );
    }
    /**
     * Get attribute from Dtks if exists, otherwise from Papan
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key)
    {        
        // If the attribute exists in Dtks list, try to get from Dtks first
        if (in_array($key, $this->dtksAttributes)) {
            $this->loadMissing('dtks');

            if ($this->dtks && $this->dtks->{$key} !== null) {
                return $this->dtks->{$key};
            }
        }

        // If not found in Dtks or not a Dtks attribute, get from parent
        return parent::getAttribute($key);
    }

    public function getKepalaRumahTanggaAttribute()
    {
        return $this->kepalaRumahTangga;
    }
}
