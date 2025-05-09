<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function generateLogPenduduk($config_id)
    {
        try {
            $sql = "insert into log_keluarga (config_id, id_kk, id_peristiwa, tgl_peristiwa, updated_by)
            select {$config_id} as config_id, id as id_kk, 1 as id_peristiwa, tgl_daftar as tgl_peristiwa, 1 as updated_by
            from tweb_keluarga where config_id = '{$config_id}'";

            DB::statement($sql);

            return response()->json([
                'success' => true,
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function generateLogKeluarga($config_id)
    {
        try {
            $sql = "insert into log_keluarga (config_id, id_kk, id_peristiwa, tgl_peristiwa, updated_by)
            select {$config_id} as config_id, id as id_kk, 1 as id_peristiwa, tgl_daftar as tgl_peristiwa, 1 as updated_by
            from tweb_keluarga where config_id = '{$config_id}'";

            DB::statement($sql);

            return response()->json([
                'success' => true,
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
