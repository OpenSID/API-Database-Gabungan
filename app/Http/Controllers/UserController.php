<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Controllers\AppBaseController;
use App\Http\Repository\RoleRepository;
use App\Http\Repository\UserRepository;
use App\Http\Transformers\UserTransformer;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;

class UserController extends AppBaseController
{
    /** @var UserRepository $userRepository*/
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     */
    public function index(Request $request)
    {
        if ($request->ajax()){
            return $this->fractal($this->userRepository->listUser(), new UserTransformer, 'users')->respond();
        }

        return view('users.index');
    }


    /**
     * Show the form for creating a new User.
     */
    public function create()
    {
        return view('users.create', $this->getOptionItems());
    }

    /**
     * Store a newly created Pengguna in storage.
     */
    public function store(CreateUserRequest $request)
    {
        $input = $request->all();

        $user = $this->userRepository->create($input);

        Session::flash('success', 'Pengguna berhasil disimpan.');

        return redirect(route('users.index'));
    }

    /**
     * Show the form for editing the specified User.
     */
    public function edit($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Session::flash('error','Pengguna tidak ditemukan');

            return redirect(route('users.index'));
        }
        $roleName = $user?->roles->first()->name ?? null;

        return view('users.edit', $this->getOptionItems($id))->with(['user' => $user, 'roleName' => $roleName]);
    }

    /**
     * Update the specified Pengguna in storage.
     */
    public function update($id, UpdateUserRequest $request)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Session::flash('error','Pengguna tidak ditemukan');

            return redirect(route('users.index'));
        }

        $user = $this->userRepository->update($request->all(), $id);

        Session::flash('success','Pengguna berhasil disimpan.');

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified Pengguna from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Session::flash('error','Pengguna tidak ditemukan');

            return redirect(route('users.index'));
        }

        $this->userRepository->delete($id);
        if(request()->ajax()){
            return $this->sendSuccess('Pengguna berhasil dihapus.');
        }
        Session::flash('success','Pengguna berhasil dihapus.');

        return redirect(route('users.index'));
    }

    protected function getOptionItems($id = null)
    {
        return [
            'roles' => (new RoleRepository)->pluck(['name','name']),
        ];
    }
}
