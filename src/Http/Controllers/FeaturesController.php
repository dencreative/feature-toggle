<?php

namespace CharlGottschalk\FeatureToggle\Http\Controllers;

use CharlGottschalk\FeatureToggle\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeaturesController extends BaseController
{
    public function index()
    {
        $features = Feature::on(config('features.connection', config('database.default')))
                                ->orderBy('name')
                                ->paginate(20);
        return view('feature-toggle::index', compact('features'));
    }

    public function edit(Request $request, $id)
    {
        $roles = config('features.roles.model')::orderBy(config('features.roles.column'))->get();
        $feature = Feature::on(config('features.connection', config('database.default')))
                            ->with('roles')
                            ->find($id);

        $linkedRoles = [];
        foreach ($roles as $role) {
            $linked = false;

            if($feature->roles->contains(function ($value) use ($role) {
                return $role->id == $value->id;
            })) {
                $linked = true;
            }

            $linkedRoles[] = [
                'linked' => $linked,
                'role' => $role
            ];
        }

        return view('feature-toggle::edit', compact('feature', 'linkedRoles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:CharlGottschalk\FeatureToggle\Models\Feature'
        ]);

        if ($validator->fails()) {
            session()->flash('test', 'Test');
            return redirect()->route('features.toggle.index')
                ->withErrors($validator)
                ->withInput();
        }

        $feature = new Feature;
        $feature->name = $request->input('name');
        $feature->enabled = $request->has('enabled');
        $feature->on(config('features.connection', config('database.default')))
                ->save();
        return redirect()->route('features.toggle.index')->with('alert', ['type' => 'success', 'message' => "{$feature->name} added"]);
    }

    public function update(Request $request, $id)
    {
        $feature = Feature::on(config('features.connection', config('database.default')))
                            ->find($id);
        $feature->roles()->sync($request->input('roles'));
        return redirect()->route('features.toggle.index')->with('alert', ['type' => 'success', 'message' => "{$feature->name} roles updated"]);
    }

    public function delete(Request $request, $id)
    {
        $feature = Feature::on(config('features.connection', config('database.default')))
                            ->find($id);
        $feature->roles()->detach();
        $feature->delete();
        return redirect()->route('features.toggle.index')->with('alert', ['type' => '', 'message' => "{$feature->name} removed"]);
    }

    public function enable(Request $request, $id)
    {
        $feature = Feature::on(config('features.connection', config('database.default')))
                            ->find($id);
        $feature->enable();
        return redirect()->route('features.toggle.index')->with('alert', ['type' => 'default', 'message' => "{$feature->name} enabled"]);
    }

    public function disable(Request $request, $id)
    {
        $feature = Feature::on(config('features.connection', config('database.default')))
                            ->find($id);
        $feature->disable();
        return redirect()->route('features.toggle.index')->with('alert', ['type' => '', 'message' => "{$feature->name} disabled"]);
    }
}
