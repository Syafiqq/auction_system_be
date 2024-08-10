<?php

namespace App\Presentation\Http\Requests;

use App\Domain\Entity\Enum\UserTypeEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PlaceBidRequest extends FormRequest
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

        $role = UserTypeEnum::regular->value;
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
            'id' => ['required', 'integer', 'exists:App\Domain\Entity\AuctionItem,id'],
            'amount' => ['required', 'integer'],
            'last_bid_reference' => ['nullable', 'integer', 'exists:App\Domain\Entity\Bid,id'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
}
