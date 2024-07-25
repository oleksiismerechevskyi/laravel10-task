<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="CreateUserRequest",
 *     required={"name", "email", "position_id", "password", "photo"}
 * )
 */
class CreateUserRequest extends FormRequest
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
        $emailPattern = '/^(?:[a-z0-9!#$%&\'*+\/=?^_`{|}~\-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~\-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9\-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9\-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9\-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$/';

        return [
            'name' => 'required|string|min:2|max:60',
            'email' => ['required', 'unique:users,email', 'min:6', 'max:100', 'regex:' . $emailPattern ],
            'phone' => 'required|regex:/^\+380\d{9}$/',
            'position_id' => 'required|int|min:1|exists:positions,id',
            'photo' => 'required|file|mimes:jpg,jpeg|max:5120|dimensions:min_width=70,min_height=70'
        ];
    }
}
