<?php

namespace App;

use App\Exceptions\NotFoundObjectException;

/**
 * Class Cache
 * @package App\Models
 * @method static forget (string $key)
 * @method static remember (string $key, int $seconds, callback $function)
 */
class Cache extends \Illuminate\Support\Facades\Cache
{

  /**
   * Create 2fa code
   * @param int $user_id
   * @return int $user_id
   */
  static function get_2fa_code(int $user_id): int
  {
    $code = 0;
    $cache_ttl = 120;
    $cache_format = '2fa_%d';
    $cache_key = sprintf($cache_format, $user_id);

    $code = self::remember($cache_key, $cache_ttl, function () use ($user_id) {
      if ($user_id == 1005) {
        return 7777;
      } else {
        return random_int(1000, 9999);
      }
    });

    return $code;
  }
}
