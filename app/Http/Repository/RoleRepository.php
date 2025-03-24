<?php

namespace App\Http\Repository;

use App\Models\Role;
use App\Http\Repository\BaseRepository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RoleRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'guard_name'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Role::class;
    }

    /**
     * @return bool|mixed|null
     *
     * @throws \Exception
     */
    public function delete(int $id)
    {
        $query = $this->model->newQuery();

        $model = $query->findOrFail($id);
        if ($model->users->count() > 0) {
            throw new \Exception('Peran sudah digunakan');
        }
        return $model->delete();
    }

    public function listRole()
    {
        return QueryBuilder::for(Role::with('users'))
            ->allowedFields('*')
            ->allowedFilters([
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($q) use ($value) {
                        $q->orWhere('name', 'LIKE', '%'.$value.'%');
                        $q->orWhere('guard_name', 'LIKE', '%'.$value.'%');
                    });
                }),
            ])->allowedSorts($this->getFieldsSearchable())
            ->jsonPaginate();
    }
}
