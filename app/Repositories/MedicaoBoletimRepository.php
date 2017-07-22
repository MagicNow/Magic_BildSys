<?php

namespace App\Repositories;

use App\Models\MedicaoBoletim;
use App\Models\MedicaoBoletimStatusLog;
use InfyOm\Generator\Common\BaseRepository;

class MedicaoBoletimRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'obra_id',
        'contrato_id',
        'medicao_boletim_status_id',
        'obs',
        'user_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MedicaoBoletim::class;
    }

    public function create(array $attributes)
    {
        $attributes['user_id'] = auth()->id();
        $attributes['medicao_boletim_status_id'] = 1;
        $model = parent::create($attributes);

        // Cria status
        MedicaoBoletimStatusLog::create([
            'medicao_boletim_id' => $model->id,
            'medicao_boletim_status_id' => $model->medicao_boletim_status_id,
            'user_id' => auth()->id()
        ]);

        return $this->parserResult($model);
    }

    public function update(array $attributes, $id)
    {

        $model = parent::update($attributes, $id);

        return $this->parserResult($model);
    }
}
