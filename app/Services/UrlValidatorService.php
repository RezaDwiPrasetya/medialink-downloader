<?php

namespace App\Services;

class UrlValidatorService
{
    protected ?string $lastError = null;

    /**
     * Memeriksa apakah URL aman dari ancaman SSRF (Server-Side Request Forgery).
     * Menolak localhost, IP loopback, IP privat LAN, dan link-local/cloud metadata.
     */
    public function isSafe(string $url): bool
    {
        $this->lastError = null;

        $parsed = parse_url($url);
        if (! $parsed || empty($parsed['host'])) {
            $this->lastError = 'URL tidak memiliki hostname yang valid.';
            return false;
        }

        $host = strtolower($parsed['host']);

        // 1. Tolak hostname terlarang langsung (localhost dan variasinya)
        if ($this->isForbiddenHostname($host)) {
            $this->lastError = 'Akses ke localhost atau host internal tidak diizinkan.';
            return false;
        }

        // 2. Jika host berupa alamat IP langsung (IPv4 / IPv6)
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            if (! $this->isPublicIp($host)) {
                $this->lastError = 'URL mengarah ke alamat IP jaringan privat atau lokal yang dilarang.';
                return false;
            }
            return true;
        }

        // 3. Resolusi DNS hostname ke IP address
        $ips = @gethostbynamel($host);
        if ($ips === false || empty($ips)) {
            $this->lastError = 'Hostname tidak dapat diresolusi ke alamat IP (DNS gagal).';
            return false;
        }

        // 4. Periksa setiap IP hasil resolusi DNS
        foreach ($ips as $ip) {
            if (! $this->isPublicIp($ip)) {
                $this->lastError = "URL teresolusi ke IP jaringan privat/lokal ({$ip}) yang tidak diizinkan.";
                return false;
            }
        }

        return true;
    }

    /**
     * Mengambil pesan error terakhir saat validasi gagal.
     */
    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    /**
     * Memeriksa apakah hostname termasuk yang dilarang keras.
     */
    protected function isForbiddenHostname(string $host): bool
    {
        $forbidden = [
            'localhost',
            'localhost.localdomain',
            '127.0.0.1',
            '::1',
            '0.0.0.0',
            'metadata.google.internal', // GCP metadata
            'instance-data',            // AWS metadata
        ];

        if (in_array($host, $forbidden, true)) {
            return true;
        }

        // Tolak subdomain localhost (*.localhost)
        if (str_ends_with($host, '.localhost') || str_ends_with($host, '.local')) {
            return true;
        }

        return false;
    }

    /**
     * Memeriksa apakah sebuah IP merupakan IP publik yang sah (bukan privat/loopback/reserved).
     */
    protected function isPublicIp(string $ip): bool
    {
        // FILTER_FLAG_NO_PRIV_RANGE menolak 10.0.0.0/8, 172.16.0.0/12, 192.168.0.0/16
        // FILTER_FLAG_NO_RES_RANGE menolak 0.0.0.0/8, 127.0.0.0/8, 169.254.0.0/16 (link-local/metadata), ::1, dll.
        $flags = FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;

        if (filter_var($ip, FILTER_VALIDATE_IP, $flags) === false) {
            return false;
        }

        // Pengecekan ekstra untuk IP loopback / cloud metadata 169.254.169.254
        if (str_starts_with($ip, '127.') || str_starts_with($ip, '169.254.') || str_starts_with($ip, '0.')) {
            return false;
        }

        return true;
    }
}
