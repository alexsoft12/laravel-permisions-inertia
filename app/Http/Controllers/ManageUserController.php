<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

use Log;
use Spatie\Permission\Models\Role;


class ManageUserController extends Controller
{
    use PasswordValidationRules;

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {

        $roles = Role::with('permissions')->where('name', '!=', 'super-admin')->get();

        $users = User::with('roles')->get();

        return Inertia::render('ManageUser/Index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role_id' => ['required', 'integer'],
            'password' => $this->passwordRules(),
        ])->validate();

        try {
            $input['password'] = Hash::make($input['password']);

            $user = User::create($input);
            $role = Role::findOrFail($input['role_id']);
            $user->assignRole($role->name);
            $output = ['success' => 1,
                'msg' => __("user.user_added")
            ];

        } catch (Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = ['success' => 0,
                'msg' => __("messages.something_went_wrong")
            ];
        }
        return redirect()->back()->with('status', $output);

    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['confirmed'],
        ]);

        try {
            $user_data = $request->only(['name', 'email', 'role_id']);

            if (!empty($request->input('password'))) {
                $user_data['password'] = Hash::make($request->input('password'));
            }

            $user_update = User::findOrFail($user->id);

            $user_update->update($user_data);
            $role_id = $request->input('role_id');
            $user_role = $user_update->roles->first();

            $previous_role = !empty($user_role->id) ? $user_role->id : 0;

            if ($previous_role !== $role_id) {

                if (!empty($previous_role) && $previous_role !== 0) {
                    $user_update->removeRole($user_role->name);
                }

                $role = Role::findOrFail($role_id);


                $user_update->assignRole($role->name);
            }

            $output = ['success' => 1,
                'msg' => __("user.user_update_success")
            ];

        } catch (Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = ['success' => 0,
                'msg' => __('messages.something_went_wrong')
            ];
        }
        return redirect()->back()->with('status', $output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
