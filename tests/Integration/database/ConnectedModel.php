<?php

namespace Kdabrow\TimeMachine\Tests\Integration\Database;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class ConnectedModel extends EloquentModel
{
    public function model()
    {
        $this->belongsTo(Model::class);
    }
}
