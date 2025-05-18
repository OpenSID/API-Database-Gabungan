<?php

namespace App\Http\Controllers\Api;

use App\Http\Repository\RtmRepository;
use App\Models\Rtm;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RtmController extends Controller
{
    public function __construct(protected RtmRepository $rtm)
    {
    }

    public function index()
    {
        // return $this->fractal($this->rtm->listRtm(), new RtmTransformer(), 'rtm')->respond();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'config_id' => 'required',
            'nik_kepala' => 'required',
            'no_kk' => 'required',
            'tgl_daftar' => 'nullable',
            'kelas_sosial' => 'nullable',
            'bdt' => 'nullable',
        ]);

        // Konversi tgl_daftar ke format Y-m-d jika valid
        if (!empty($data['tgl_daftar'])) {
            $tgl = \DateTime::createFromFormat('d-m-Y', $data['tgl_daftar']);
            if ($tgl && $tgl->format('d-m-Y') === $data['tgl_daftar']) {
                $data['tgl_daftar'] = $tgl->format('Y-m-d');
            } else {
                $data['tgl_daftar'] = null; // atau return error
            }
        }

        try {
            $rtm = Rtm::create($data);

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

