<?php

namespace App\Presentation\Http\Requests;

use App\Domain\Entity\Enum\UserTypeEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class AuctionItemCreateRequest extends FormRequest
{
    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $role = UserTypeEnum::admin->value;
        return $this->user() != null && $this->user()->tokenCan("role:$role");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'description' => ['required'],
            'starting_price' => ['required', 'integer'],
            'end_time' => ['required', 'date_format:d-m-Y'],
            'images.*' => ['required', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation(): void
    {
        $this->merge([
            'end_time' => Carbon::parse($this->input('end_time'))->setTime(23, 59, 59),
        ]);
    }
}
