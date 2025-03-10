<?php

namespace DenCreative\FeatureToggle\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreFeature extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:DenCreative\FeatureToggle\Models\Feature'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $name = str_replace(' ', '_', $this->name);
        $this->merge([
            'name' => Str::of($name)->lower()->snake()
        ]);
    }
}
