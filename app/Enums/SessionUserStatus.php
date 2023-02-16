<?php

namespace App\Enums;

enum SessionUserStatus: string
{
  case TODO         = 'todo';
  case NEXT         = 'next';
  case IN_PGROGRESS = 'progress';
  case DONE         = 'done';
}
