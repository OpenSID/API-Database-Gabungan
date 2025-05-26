<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PendudukRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'config_id' => 'required',
            'nik' => 'required',
            'nama' => 'required',
            // 'id_kk' => 'required',
            'kk_level' => 'required',
            'id_rtm' => 'required',
            'rtm_level' => 'required',
            'sex' => 'required',
            'tempatlahir' => 'required',
            'tanggallahir' => 'required',
            'agama_id' => 'required',
            'pendidikan_kk_id' => 'required',
            'pendidikan_sedang_id' => 'required',
            'pekerjaan_id' => 'required',
            'status_kawin' => 'required',
            'id_cluster' => 'required',
            'warganegara_id' => 'required',
            'alamat_sekarang' => 'required',
            'ayah_nik' => 'required',
            'nama_ayah' => 'required',
            'ibu_nik' => 'required',
            'nama_ibu' => 'required',
            'golongan_darah_id' => 'required',
            'status' => 'required',
            'status_dasar' => 'required',
            'created_by' => 'required',
            'updated_by' => 'required',
        ];
    }

}
