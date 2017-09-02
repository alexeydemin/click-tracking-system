<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    public function placement()
    {
        return $this->belongsTo(Placement::class);
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}
