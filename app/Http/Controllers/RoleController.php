<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Controllers\AppBaseController;
use App\Http\Repository\RoleRepository;
use App\Http\Transformers\RoleTransformer;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;

class RoleController extends AppBaseController
{
    /** @var RoleRepository $roleRepository*/
    private $roleRepository;

    public function __construct(RoleRepository $roleRepo)
    {
        $this->roleRepository = $roleRepo;
    }

    /**
     * Display a listing of the Peran.
     */
    public function index(Request $request)
    {
        if ($request->ajax()){
            return $this->fractal($this->roleRepository->listRole(), new RoleTransformer, 'roles')->respond();
        }

        return view('roles.index');
    }


    /**
     * Show the form for creating a new Peran.
     */
    public function create()
    {
        return view('roles.create', $this->getOptionItems());
    }

    /**
     * Store a newly created Peran in storage.
     */
    public function store(CreateRoleRequest $request)
    {
        $input = $request->all();

        $role = $this->roleRepository->create($input);

        Session::flash('success','Peran berhasil disimpan.');

        return redirect(route('roles.index'));
    }

    /**
     * Show the form for editing the specified Peran.
     */
    public function edit($id)
    {
        $role = $this->roleRepository->find($id);

        if (empty($role)) {
            Session::flash('error','Peran tidak ditemukan');

            return redirect(route('roles.index'));
        }

        return view('roles.edit', $this->getOptionItems($id))->with('role', $role);
    }

    /**
     * Update the specified Peran in storage.
     */
    public function update($id, UpdateRoleRequest $request)
    {
        $role = $this->roleRepository->find($id);

        if (empty($role)) {
            Session::flash('error','Peran tidak ditemukan');

            return redirect(route('roles.index'));
        }

        $role = $this->roleRepository->update($request->all(), $id);

        Session::flash('success','Peran berhasil diperbarui.');

        return redirect(route('roles.index'));
    }

    /**
     * Remove the specified Peran from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $role = $this->roleRepository->find($id);

        if (empty($role)) {
            Session::flash('error','Peran tidak ditemukan');

            return redirect(route('roles.index'));
        }

        $this->roleRepository->delete($id);
        if(request()->ajax()){
            return $this->sendSuccess('Peran berhasil dihapus.');
        }
        Session::flash('success','Peran berhasil dihapus.');

        return redirect(route('roles.index'));
    }

    protected function getOptionItems($id = null)
    {
        return [];
    }
}
