<?php
/**
 * BcryptPlayground
 * - Create a bcrypt hash with a specific cost and 22-character salt.
 * - Verify using password_verify (compatible with Laravel's Hash::check).
 *
 * Usage:
 *   $hash = BcryptPlayground::make('MySecret123');
 *   var_dump(BcryptPlayground::verify('MySecret123', $hash)); // true
 *
 * NOTE: The provided salt must be 22 characters from the bcrypt alphabet: "./0-9A-Za-z"
 */

class BcryptPlayground
{
    /**
     * Create a bcrypt hash using a given salt and cost.
     *
     * @param string $password Plain password to hash
     * @param string|null $salt 22-character bcrypt salt (if null uses example salt)
     * @param int $cost bcrypt cost/work factor (default 10)
     * @return string bcrypt hash (60 chars) or throws InvalidArgumentException
     */
    public static function make(string $password, ?string $salt = null, int $cost = 10): string
    {
        // default salt from your message
        $defaultSalt = '3vfYtD8vrHZapi5haxL/c.';

        $salt = $salt ?? $defaultSalt;

        // Validate salt length and allowed chars for bcrypt
        if (strlen($salt) !== 22 || preg_match('/[^\.\/A-Za-z0-9]/', $salt)) {
            throw new InvalidArgumentException('Salt must be exactly 22 chars from the bcrypt alphabet ./0-9A-Za-z');
        }

        if ($cost < 4 || $cost > 31) {
            throw new InvalidArgumentException('Cost must be between 4 and 31 for bcrypt.');
        }

        // Build the full salt prefix for crypt() -> "$2y$10$" + 22-char salt
        $saltPrefix = sprintf('$2y$%02d$%s', $cost, $salt);

        // Use PHP's crypt() to create a bcrypt hash using the provided salt
        $hash = crypt($password, $saltPrefix);

        // crypt returns a 60-character bcrypt string on success; do a quick sanity check
        if (!is_string($hash) || strlen($hash) < 60) {
            throw new RuntimeException('crypt() failed to create a valid bcrypt hash.');
        }

        return $hash;
    }

    /**
     * Verify a plain password against a stored bcrypt hash.
     * (password_verify() works with bcrypt hashes produced by crypt())
     *
     * @param string $password
     * @param string $storedHash
     * @return bool
     */
    public static function verify(string $password, string $storedHash): bool
    {
        // Use PHP's built-in function which is safe and timing-attack resistant
        return password_verify($password, $storedHash);
    }
}

/* ---------- Example usage ---------- */
if (php_sapi_name() === 'cli' && basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    echo "Example run\n";
    $pw = 'MySecret123';
    $salt = '3vfYtD8vrHZapi5haxL/c.'; // your salt
    $cost = 10;

    $hash = BcryptPlayground::make($pw, $salt, $cost);
    echo "Generated hash: $hash\n";
    echo "Verify correct password: " . (BcryptPlayground::verify($pw, $hash) ? 'YES' : 'NO') . PHP_EOL;
    echo "Verify wrong password: " . (BcryptPlayground::verify('wrong', $hash) ? 'YES' : 'NO') . PHP_EOL;
}
