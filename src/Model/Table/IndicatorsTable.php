<?php
namespace App\Model\Table;

use App\Model\Entity\Indicator;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;

/**
 * Indicators Model
 *
 * @property \Cake\ORM\Association\HasMany $Months
 */
class IndicatorsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('indicators');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->hasMany('Months', [
            'foreignKey' => 'indicator_id'
        ]);

        $this->hasOne('CurrentMonth', [
            'foreignKey' => 'indicator_id',
            'className' => 'Months',
            'conditions' => ['AND' => [
                                'CurrentMonth.month' => Configure::read("Conf")['month'],
                                'CurrentMonth.year' => Configure::read("Conf")['year']
                            ]]
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create')
            ->add('id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->allowEmpty('name');

        $validator
            ->allowEmpty('formula');

        $validator
            ->allowEmpty('source');

        $validator
            ->allowEmpty('goal');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['id']));
        return $rules;
    }
}
