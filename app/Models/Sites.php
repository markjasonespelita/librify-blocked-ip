<?php

namespace App\Models;

use OwenIt\Auditing\Contracts\Auditable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sites extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\SitesFactory> */

    protected $fillable = ["name","municipalities_id","olts_id","statuses_id","ip_address","remarks","isTrash"];


    use \OwenIt\Auditing\Auditable;

    protected $auditExclude = ['id'];

    public function generateTags(): array
    {
        return [
            'id:' . $this->id,
        ];
    }

    use HasFactory;

    public function municipality()
    {
        return $this->belongsTo(Municipalities::class, 'municipalities_id');
    }

    public function olt()
    {
        return $this->belongsTo(Olts::class, 'olts_id');
    }

    public function status()
    {
        return $this->belongsTo(Statuses::class, 'statuses_id');
    }
}
