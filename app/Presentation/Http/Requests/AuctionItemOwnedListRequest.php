<?php

namespace App\Presentation\Http\Requests;

use App\Domain\Entity\Enum\BidStatusEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuctionItemOwnedListRequest extends FormRequest
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
        return $this->user() != null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'page' => ['nullable', 'integer'],
            'name' => ['nullable'],
            'description' => ['nullable'],
            'types.*' => ['nullable', 'integer', Rule::in([1, 2, 3, 4])],
        ];
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        $this->replace(['types' => array_map(fn($type) => $this->bidStatusTransform($type), $this->input('types') ?? [])]);
    }

    private function bidStatusTransform(int $id): BidStatusEnum
    {
        switch ($id) {
            case 1:
                return BidStatusEnum::win;
            case 2:
                return BidStatusEnum::lose;
            case 3:
                return BidStatusEnum::inProgress;
            case 4:
                return BidStatusEnum::inProgressLeading;
        }
        return BidStatusEnum::win;
    }
}
