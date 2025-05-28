<?php

namespace App\Http\Controllers\Api;

use App\Http\Repository\KeluargaRepository;
use App\Http\Transformers\RincianKeluargaTransformer;
use App\Models\Keluarga;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class KeluargaController extends Controller
{
    public function __construct(protected KeluargaRepository $keluarga)
    {
    }

    public function __invoke()
    {
    }

    public function keluarga()
    {
        return $this->fractal($this->keluarga->listKeluarga(), new RincianKeluargaTransformer(), 'keluarga')->respond();
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return $this->fractal($this->keluarga->rincianKeluarga(), new RincianKeluargaTransformer(), 'rincian keluarga')->respond();
    }

    public function summary()
    {
        return QueryBuilder::for(Keluarga::class)->count();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'config_id' => 'required',
            'nik_kepala' => 'required',
            'no_kk' => 'required',
            'tgl_daftar' => 'nullable',
            'kelas_sosial' => 'nullable',
            'alamat' => 'nullable',
            'id_cluster' => 'nullable',
            'updated_by' => 'nullable',
        ]);

        try {
            $rtm = Keluarga::create($data);

            return response()->json([
                'success' => true,
                'data' => $rtm,
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
