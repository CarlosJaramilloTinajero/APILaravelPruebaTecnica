<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductFilterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'in_stock' => 'nullable|boolean',
            'name' => 'nullable|string',
            'per_page' => 'nullable|integer'
        ];
    }

    public function messages()
    {
        return [
            'min_price.numeric' => 'El precio mínimo debe ser un número.',
            'min_price.min' => 'El precio mínimo no puede ser negativo.',
            'max_price.numeric' => 'El precio máximo debe ser un número.',
            'max_price.min' => 'El precio máximo no puede ser negativo.',
            'in_stock.boolean' => 'El campo "in_stock" debe ser verdadero (true) o falso (false).',
            'name.string' => 'El nombre debe ser un texto.',
            'per_page.integer' => 'El número de elementos por página debe ser un número entero.',
        ];
    }
}
