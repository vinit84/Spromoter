<?php

namespace App\Http\Requests\Admin\Business\Plans;

use App\Models\Plan;
use App\Rules\PlanFeatureType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'alpha_dash', 'max:255'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'yearly_price' => ['required', 'numeric', 'min:0'],
            'monthly_order' => ['required', 'numeric', 'min:1'],
            'trial_days' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string'],
            'is_active' => ['required', 'boolean'],
            'features' => ['required', 'array'],
        ];

        foreach(Plan::getFeatures() as $feature){
            $rules['features.' . $feature['slug']] = ['nullable', new PlanFeatureType($feature)];
        }

        return $rules;
    }
}
