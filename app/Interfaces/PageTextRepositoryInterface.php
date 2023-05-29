<?php

namespace App\Interfaces;

interface PageTextRepositoryInterface
{
  public function getText(string $entity, int $record_id, string $role, string $block = null);
}
