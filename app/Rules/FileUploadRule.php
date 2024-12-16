<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class FileUploadRule implements ValidationRule
{
    private $isRequired;
    private $allowedMimes;
    private $maxSize;

    public function __construct($isRequired = false)
    {
        $this->isRequired = $isRequired;
        $this->allowedMimes = ['image/png', 'image/jpg', 'image/jpeg', 'application/pdf'];
        $this->maxSize = 5120;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->isRequired && !$value) {
            $fail(__('validation.required_upload'));
            return;
        }

        if (!$value instanceof UploadedFile) {
            return;
        }

        if ($this->allowedMimes && !in_array($value->getMimeType(), $this->allowedMimes, true)) {
            $fail(__('validation.upload_format'));
        }

        if ($this->maxSize !== null && $value->getSize() > $this->maxSize * 1024) {
            $fail(__('validation.upload_size'));
        }
    }
}
