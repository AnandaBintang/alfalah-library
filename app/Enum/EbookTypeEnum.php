<?php

namespace App\Enum;

enum EbookTypeEnum: string
{
  case LINK = 'link';
  case PDF = 'pdf';

  public function label(): string
  {
    return match ($this) {
      self::LINK => 'Link URL',
      self::PDF => 'Upload PDF',
    };
  }
}
