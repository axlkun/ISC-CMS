<?php

namespace App\Models;

use App\Traits\Imageable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Contracts\ImageableContract as ContractsImageableContract;

class Project extends Model implements ContractsImageableContract
{
    use HasFactory;
    use Imageable;

    protected $guarded = ['id'];

    public function uploadFolder():string{
        return "public/projects";
        // return "projects";
    }

    
    public function services(): BelongsToMany {
        return $this->belongsToMany(Service::class);
    }
}
