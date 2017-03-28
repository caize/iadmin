<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Services\MenuService;
use Services\PermissionsService;
use Services\RolesService;

class RolesController extends Controller
{
    protected $roles;
    public function __construct(RolesService $roles)
    {
        $this->roles = $roles;
    }

    public function index()
    {
//        //$this->roles->perm(1);
//        //$this->roles->menu(1);
//        $this->roles->savePermission(1, [10,11]);
////        //$this->roles->savePermission(1, '');
////        $this->roles->detachPermission(1, [10,11,12,13,14,15]);
//        exit;
        return view('admin.roles.index')->with('roles', $this->roles->all());
    }

    public function store(Request $request)
    {
        $message = '添加失败';
        if ($this->roles->store($request->all())) {
            $message = '添加成功';
        }
        showMessage($message);
        return redirect()->back();
    }

    public function permissions(PermissionsService $permission, MenuService $menu, $id)
    {
        return view('admin.roles.permissions')
            ->withPermissions($permission->tree())
            ->withMenus(getTree($menu->getList()))
            ->withId($id)
            ->withPerm($this->roles->perm($id))
            ->withMenu($this->roles->menu($id));
    }

    public function perm(Request $request, $id)
    {
        $data = $request->all();
        $menu = empty($data['menu']) ? '' : $data['menu'];
        $permission = empty($data['permission']) ? '' : $data['permission'];
        $this->roles->savePermission($id, $permission);
        $this->roles->saveMenu($id, $menu);
        showMessage('权限设置成功');
        return back();
    }

    public function destroy($id)
    {
        $this->roles->destroy($id);
        showMessage('删除成功');
        return redirect()->back();
    }
}