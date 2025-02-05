<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class LogPerubahanPenduduk extends BaseModel
{
    use HasFactory;

    /**
     * {@inheritdoc}
     */
    protected $table = 'log_perubahan_penduduk';

    /**
     * {@inheritdoc}
     */
    protected $guarded = [];

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;
}
