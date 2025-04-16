<?php

namespace App\Models;

class BaseUuidModel extends BaseModel
{
    /** {@inheritdoc} */
    protected $keyType = 'string';

    /** {@inheritdoc} */
    public $incrementing = false;

    protected $primaryKey = 'uuid';
}
