<?php

namespace App\Shared\Infrastructure\Symfony\Http;
use OpenApi\Attributes as OA;

#[OA\Schema()]
class BadRequestResponse implements \JsonSerializable
{
    public function __construct(
        #[OA\Property(type: 'string', example: 'bad_request')]
        public string $code,
        #[OA\Property(type: 'string', example: 'Bad request')]
        public string $message,
        #[OA\Property(type: 'string')]
        public mixed $data,
        #[OA\Property(type: 'integer', example: 400)]
        public mixed $status
    ) {
    }

    public function jsonSerialize(): mixed
    {
        $response = [
            'code' => $this->code,
            'message' => $this->message,
        ];

        if ($this->data) {
            $response['data'] = $this->data;
        }
        return $response;
    }
}
