<?php

namespace App\Models;

use App\Models\Traits\ConfigIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DokumenHidup extends \Illuminate\Database\Eloquent\Model
{
    use ConfigIdTrait;
    use HasFactory;

    /** {@inheritdoc} */
    protected $table = 'dokumen_hidup';

    /** {@inheritdoc} */
    protected $casts = [
        'attr' => 'json',
    ];
}
