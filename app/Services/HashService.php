<?php

namespace App\Services;

use Illuminate\Contracts\Hashing\Hasher;

/**
 * Service responsisble for hashing sensitive data
 */
class HashService extends BaseService implements Hasher
{
	/**
	 * @var string|int|null $algorithm The hashing algorithm used by the service
	 */
	protected $algorithm = PASSWORD_DEFAULT;

	/**
	 * @inheritDoc
	 */
	public function info($hashedValue): array
	{
		return password_get_info($hashedValue);
	}

	/**
	 * @inheritDoc
	 */
	public function make($value, array $options = []): string
	{
		return password_hash($this->pepper($value), $this->algorithm, $options);
	}

	/**
	 * @inheritDoc
	 */
	public function check($value, $hashedValue, array $options = []): bool
	{
		if (is_null($hashedValue) || strlen($hashedValue) === 0) {
			return false;
		}

		return password_verify($this->pepper($value), $hashedValue);
	}

	/**
	 * @inheritDoc
	 */
	public function needsRehash($hashedValue, array $options = []): bool
	{
		return password_needs_rehash($hashedValue, $this->algorithm, $options);
	}

	/**
	 * It is recommended that passwords are pepperred as a secondary form of protection
	 * @link https://www.php.net/manual/en/function.password-hash.php#124138 A detailed explanation on the topic
	 *
	 * @param  string $value
	 * @return string
	 */
	protected function pepper(string $value): string
	{
		return hash_hmac('sha256', $value, $this->config['pepper']);
	}
}
