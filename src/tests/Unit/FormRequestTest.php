<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Requests\StoreTranslationRequest;
use App\Http\Requests\UpdateTranslationRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Validator;

class FormRequestTest extends TestCase
{
    public function test_login_request_rules(): void
    {
        $rules = (new LoginRequest())->rules();
        $this->assertArrayHasKey('email', $rules);
        $this->assertArrayHasKey('password', $rules);

        $validator = Validator::make([], $rules);
        $this->assertTrue($validator->fails());
    }

    public function test_store_translation_request_rules(): void
    {
        $rules = (new StoreTranslationRequest())->rules();
        $this->assertArrayHasKey('locale_id', $rules);
        $this->assertArrayHasKey('key', $rules);
        $this->assertArrayHasKey('value', $rules);

        $validator = Validator::make([], $rules);
        $this->assertTrue($validator->fails());
    }

    public function test_update_translation_request_rules(): void
    {
        $rules = (new UpdateTranslationRequest())->rules();
        $this->assertArrayHasKey('value', $rules);

        $validator = Validator::make([], $rules);

        $this->assertFalse($validator->fails());
    }
}
