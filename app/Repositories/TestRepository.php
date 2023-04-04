<?php

namespace App\Repositories;

use App\Interfaces\TestRepositoryInterface;
use App\Models\Test;

class TestRepository implements TestRepositoryInterface
{
  public function getTests()
  {
    return Test::query()->orderBy('created_at', 'ASC')->get();
  }
}
