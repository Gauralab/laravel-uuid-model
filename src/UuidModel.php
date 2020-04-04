<?php


namespace Gauralab\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Query\Builder;
use Ramsey\Uuid\Uuid;

/**
 * Class UuidModel
 *  Eloquent model with uuid as primary key
 */
class UuidModel extends Model
{

    public $incrementing = false;
    protected $keyType = 'uuid';
    public $uuid_version = 'uuid4';

    protected $uuid_versions = [
        'uuid1',
        'uuid3',
        'uuid4',
        'uuid5',
        'uuid6',
        'ordered',
    ];

    protected static function boot()
    {
        parent::boot();

        /**
         * When creating model we have to generate primary key
         */
        static::creating(function (UuidModel $model) {
            if ($model->{$model->getKeyName()} == null) {
                $model->{$model->getKeyName()} = (string) $model->generateNewId();
            }
        });
    }

    /**
     * Generating UUID
     *
     * @return Uuid
     * @throws \Exception
     */
    public function generateNewId()
    {
        if (!(property_exists($this, 'uuid_version') && in_array($this->uuid_version, $this->uuid_versions))) {
            $this->uuid_version = 'uuid4';
        }
        return call_user_func([Uuid::class, $this->uuid_version]);
    }
}
