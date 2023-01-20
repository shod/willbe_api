<?php

declare(strict_types=1);

namespace App\Http\Traits;

use Illuminate\Support\Arr;

/**
 * Class BitwiseFlagTrait
 * @author Shod
 */
trait BitwiseFlagTrait
{

  // const MASK_ADDRESS         = 1 << 0; // 1
  // const MASK_PARCEL          = 1 << 1; // 2
  // const MASK_GEOCODE         = 1 << 2; // 4

  /**
   * Bitwise field
   */
  public $bitwise_field = 'status_bit';

  /**
   * $field - Name of the field
   */
  public function getFlag(int $flag): bool
  {
    return ($this->bitwise_field & $flag) === $flag;
  }

  /**
   * Set bit
   */
  public function setFlag(int $flag, bool $value): bool
  {
    if ($value) {
      $this->bitwise_field |= $flag;
    } else {
      $this->bitwise_field &= ~$flag;
    }

    return ($this->bitwise_field & $flag) === $flag;
  }

  protected function toggleFlag(int $flag): bool
  {
    $this->bitwise_field ^= $flag;

    return $this->getFlag($flag);
  }

  /**
   * These two methods have been status.
   * $query = Property::query()
   *  
   *  ->isSet([
   *      static::MASK_ADDRESS,
   *      static::MASK_PARCEL,
   *  ])
   *  
   *  
   *  ->notSet([
   *      static::MASK_GEOCODE,
   *  ]);
   * dump($query->toSql());
   */
  public function scopeIsSet($query, $bits)
  {
    $this->queryBitmask($query, $bits, $isSet = true);
  }

  public function scopeNotSet($query, $bits)
  {
    $this->queryBitmask($query, $bits, $isSet = false);
  }

  protected function queryBitmask($query, $bits, $isSet)
  {
    // Sum up all the bits that were given to us.
    $bits = array_sum(Arr::wrap($bits));

    // Set our operator based on the whether we're looking
    // for the presence or absence of bits.
    $operator = $isSet ? '=' : '!=';

    // Add the raw SQL.
    $query->whereRaw($this->bitwise_field . " & $bits $operator $bits");
  }
}
