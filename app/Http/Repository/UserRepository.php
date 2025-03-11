<?php

namespace App\Http\Repository;

use App\Models\User;
use App\Http\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'email',
        'name',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return User::class;
    }

    public function listUser()
    {
        return QueryBuilder::for(User::with('roles'))
            ->allowedFields('*')
            ->allowedFilters([
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($q) use ($value) {
                        $q->orWhere('email', 'LIKE', '%'.$value.'%');
                        $q->orWhere('name', 'LIKE', '%'.$value.'%');
                    });
                }),
            ])->allowedSorts($this->getFieldsSearchable())
            ->jsonPaginate();
    }

    /**
     * Create model record.
     */
    public function create(array $input): Model
    {
        $role = $input['role'];
        unset($input['role']);
        $model = parent::create($input);
        $model->assignRole($role);
        return $model;
    }


    /**
     * Update model record for given id.
     *
     * @return Builder|Builder[]|Collection|Model
     */
    public function update(array $input, int $id)
    {
        $role = $input['role'];
        unset($input['role']);
        $model = parent::update($input, $id);
        $model->syncRoles($role);
        return $model;
    }
}
