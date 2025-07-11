<?php

namespace App\Services;

use App\Models\Book;

class BookCardService
{
  public static function generateCardData(Book $book): array
  {
    return [
      'classification_code' => self::generateClassificationCode($book),
      'title_code' => self::generateTitleCode($book),
      'writer_code' => self::generateWriterCode($book),
      'book' => $book
    ];
  }

  private static function generateClassificationCode(Book $book): string
  {
    $rackLocationParts = explode('.', $book->rack_location ?? '');
    $rackCode = isset($rackLocationParts[0]) && !empty($rackLocationParts[0])
      ? str_pad($rackLocationParts[0], 3, '0', STR_PAD_LEFT)
      : '';

    if (empty($rackCode)) {
      return '-';
    }

    $bookNumber = str_pad($book->id, 3, '0', STR_PAD_LEFT);
    return "{$rackCode}.{$bookNumber}";
  }

  private static function generateTitleCode(Book $book): string
  {
    return !empty($book->title)
      ? strtoupper(substr($book->title, 0, 1))
      : '-';
  }

  private static function generateWriterCode(Book $book): string
  {
    if (!$book->writer || empty($book->writer->name)) {
      return '-';
    }

    $writerParts = explode(' ', $book->writer->name);
    return strtoupper(substr(implode('', array_map(function ($part) {
      return substr($part, 0, 1);
    }, $writerParts)), 0, 3));
  }

  public static function generateLibraryCardCode(Book $book): string
  {
    $cardData = self::generateCardData($book);
    return "{$cardData['classification_code']} {$cardData['writer_code']} {$cardData['title_code']}";
  }
}
