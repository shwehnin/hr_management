<?php

namespace App\Http\Controllers;

use App\Models\RolesPermissions;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function companySettings()
    {
        return view('settings.companysettings');
    }

    public function rolesPermissions()
    {
        $rolesPermissions = RolesPermissions::all();
        return view('settings.rolespermissions', compact('rolesPermissions'));
    }
}
