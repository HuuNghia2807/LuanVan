<?php

namespace App\Repositories\Size;

use App\Models\Sizes;
use App\Repositories\BaseRepository;

class SizeRepository extends BaseRepository implements SizeRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return Sizes::class;
    }
}
