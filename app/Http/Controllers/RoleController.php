<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $roles = Role::with('permissions')->where('name', '!=', 'super-admin')->get();

        return Inertia::render('Role/Index', [
            'roles' => $roles,
            'availablePermissions' => Permission::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //return  Inertia::render('Role/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $role_name = $request->input('name');
            $permissions = $request->input('permissions');
            $count = Role::where('name', $role_name)
                ->count();

            if ($count == 0) {
                $role = Role::create([
                    'name' => $role_name,
                    'guard_name' => 'web'
                ]);

                if (!empty($permissions)) {
                    $role->syncPermissions($permissions);
                }
                $output = ['success' => 1,
                    'msg' => __("user.role_added")
                ];
            } else {
                $output = ['success' => 0,
                    'msg' => __("user.role_already_exists")
                ];
            }

        } catch (Exception $e) {
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            $output = ['success' => 0,
                'msg' => __("messages.something_went_wrong")
            ];
        }


        return redirect()->back()->with('status', $output);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $role_name = $request->input('name');
            $permissions = $request->input('permissions');
            $count = Role::where('name', '!=', $role_name)->where('id', '!=', $id)->count();

            if ($count == 0) {
                $role = Role::findOrFail($id);
                $role->name = $role_name;
                $role->save();

                if (!empty($permissions)) {
                    $role->syncPermissions($permissions);
                }

                $output = ['success' => 1,
                    'msg' => __("user.role_updated")
                ];
            } else {
                $output = ['success' => 0,
                    'msg' => __("user.role_already_exists")
                ];
            }
        } catch (Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = ['success' => 0,
                'msg' => __("messages.something_went_wrong")
            ];
        }
        return redirect()->back()->with('status', $output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        //if (request()->ajax()) {
            try {
                $role = Role::findOrFail($id);
                $role->delete();

                $output = ['success' => true,
                    'msg' => __("user.role_deleted")
                ];
            } catch (\Exception $e) {
                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = ['success' => false,
                    'msg' => __("messages.something_went_wrong")
                ];
            }
        return redirect()->back()->with('status', $output);
        //}

    }
}
