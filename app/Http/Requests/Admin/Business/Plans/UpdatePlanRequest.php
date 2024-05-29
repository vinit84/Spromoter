<?php

namespace App\Http\Requests\Admin\Business\Plans;

use App\Models\Plan;
use App\Rules\PlanFeatureType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanRequest extends FormRequest
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
            'description' => ['required', 'string'],
            'features' => ['required', 'array'],
            'card_features' => ['required', 'array'],
            'is_active' => ['required', 'boolean'],
        ];

        foreach(Plan::getFeatures() as $feature){
            $rules['features.' . $feature['slug']] = ['nullable', new PlanFeatureType($feature)];
        }

        foreach(Plan::getFeatures() as $feature){
            $rules['card_features.' . $feature['slug']] = ['nullable', new PlanFeatureType($feature)];
        }

        return $rules;
    }
}
