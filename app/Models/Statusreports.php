<?php

namespace App\Models;

use OwenIt\Auditing\Contracts\Auditable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statusreports extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\StatusreportsFactory> */

    protected $fillable = ["title","basis","total_sites","done","cant_be_reached","offline","not_found","users_id","isTrash"]; 


    use \OwenIt\Auditing\Auditable;

    protected $auditExclude = ['id'];

    public function generateTags(): array
    {
        return [
            'id:' . $this->id,
        ];
    }
                                            
    use HasFactory;
}
