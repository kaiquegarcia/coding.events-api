<?php

namespace App\Infrastructure\EntityModels;

use App\Domain\Entities\AbstractEntity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

abstract class AbstractModel extends Model
{
    use SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;
    /**
     * Classe da Entity relacionada a esse Model
     * @var string $entityClass
     */
    public string $entityClass;

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->id = Str::uuid();
            $model->created_at = Carbon::now()->format(DATE_ISO8601);
        });
    }

    public function getEntity(): AbstractEntity
    {
        return call_user_func("{$this->entityClass}::fromArray", $this->toArray());
    }
}
