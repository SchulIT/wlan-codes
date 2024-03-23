<?php

namespace App\Import;

use Symfony\Component\Validator\Constraints as Assert;

readonly class ImportCodeRequest {

    public function __construct(
        #[Assert\GreaterThan(0)]
        public int $duration,

        /**
         * @var string[]
         */
        #[Assert\Count(min: 1)]
        public array $codes
    ) { }
}