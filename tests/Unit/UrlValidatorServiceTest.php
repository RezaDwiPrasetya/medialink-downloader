<?php

namespace Tests\Unit;

use App\Services\UrlValidatorService;
use Tests\TestCase;

class UrlValidatorServiceTest extends TestCase
{
    protected UrlValidatorService $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new UrlValidatorService();
    }

    /**
     * Uji bahwa localhost dan loopback ditolak keras.
     */
    public function test_rejects_localhost_and_loopback(): void
    {
        $this->assertFalse($this->validator->isSafe('http://localhost'));
        $this->assertFalse($this->validator->isSafe('http://localhost:8000/secret'));
        $this->assertFalse($this->validator->isSafe('http://sub.localhost/admin'));
        $this->assertFalse($this->validator->isSafe('http://127.0.0.1:8080'));
        $this->assertFalse($this->validator->isSafe('http://127.0.0.2/test'));

        $this->assertNotNull($this->validator->getLastError());
    }

    /**
     * Uji bahwa IP privat jaringan lokal (LAN) ditolak.
     */
    public function test_rejects_private_ip_addresses(): void
    {
        $this->assertFalse($this->validator->isSafe('http://192.168.1.1'));
        $this->assertFalse($this->validator->isSafe('http://192.168.0.254/router'));
        $this->assertFalse($this->validator->isSafe('http://10.0.0.1'));
        $this->assertFalse($this->validator->isSafe('http://172.16.0.1'));
    }

    /**
     * Uji bahwa IP cloud metadata (169.254.x.x) ditolak.
     */
    public function test_rejects_cloud_metadata_ip(): void
    {
        $this->assertFalse($this->validator->isSafe('http://169.254.169.254/latest/meta-data/'));
    }

    /**
     * Uji bahwa domain publik yang sah diizinkan.
     */
    public function test_accepts_valid_public_domain(): void
    {
        // example.com selalu ada dan dijamin resolving ke IP publik
        $this->assertTrue($this->validator->isSafe('https://example.com/video.mp4'));
    }
}
