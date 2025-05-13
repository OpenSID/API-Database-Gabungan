<?php

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;

class KesehatanWebsiteTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(array $kesehatan)
    {
        return $kesehatan;
    }
}
