<?php

namespace App\Interfaces;

interface TestRepositoryInterface
{
  public function getTests();
  public function deleteTest($sessionId);
  public function createTest(array $Details);
  public function updateTest($TestId, array $Details);
}
