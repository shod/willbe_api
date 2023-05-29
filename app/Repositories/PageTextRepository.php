<?php

namespace App\Repositories;

use App\Interfaces\PageTextRepositoryInterface;
use App\Models\PageText;

class PageTextRepository implements PageTextRepositoryInterface
{
  public function getText(string $entity, int $record_id, string $role, string $block = null)
  {

    $query = PageText::query()
      ->where(['entity' => $entity, 'record_id' => $record_id, 'role' => $role]);

    if ($block) {
      $query->where('block', $block);
      $res = $query->first();
      return ($res == null) ? '' : $res->text;
    } else {
      return $query->get();
    }
  }
}
