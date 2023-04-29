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

  public function createTest(array $Details)
  {
    return Test::create($Details);
  }

  public function updateTest($TestId, array $Details)
  {
    try {
      Test::findOrFail($TestId)
        ->update($Details);
      return true;
    } catch (\Exception $e) {
      return $e;
    }
  }

  public function deleteTest($TestId)
  {
    Test::destroy($TestId);
  }
}
