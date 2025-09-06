<?php

namespace App\Filament\Resources;

use App\Enum\RoleEnum;
use App\Enum\EbookTypeEnum;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\Writer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Filament\Forms\Components\{
  Section,
  TextInput,
  Textarea,
  Select,
  FileUpload,
  Toggle
};
use Filament\Tables\Columns\{
  ImageColumn,
  TextColumn,
  IconColumn
};
use Filament\Tables\Filters\{
  SelectFilter,
  Filter,
  TernaryFilter
};
use Filament\Tables\Actions\{
  ViewAction,
  EditAction,
  CreateAction,
  Action,
  BulkAction,
  BulkActionGroup,
  DeleteBulkAction
};
use App\Filament\Resources\BookResource\Pages\{
  ListBooks,
  CreateBook,
  ViewBook,
  EditBook
};

class BookResource extends Resource
{
  protected static ?string $model = Book::class;
  protected static ?string $navigationIcon = 'heroicon-o-book-open';
  protected static ?string $navigationLabel = 'Buku';
  protected static ?string $recordTitleAttribute = 'title';
  protected static ?string $pluralModelLabel = 'Buku';
  protected static ?string $modelLabel = 'Buku';
  protected static int $globalSearchResultsLimit = 20;

  public static function getNavigationGroup(): ?string
  {
    return 'Manajemen Buku';
  }

  public static function getGlobalSearchEloquentQuery(): Builder
  {
    return parent::getGlobalSearchEloquentQuery()->with(['writer', 'publisher']);
  }

  public static function getGloballySearchableAttributes(): array
  {
    return ['title', 'isbn', 'writer.name', 'publisher.name'];
  }

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Section::make('Informasi Dasar')
          ->schema([
            TextInput::make('title')
              ->label('Judul Buku')
              ->required()
              ->maxLength(255)
              ->columnSpanFull(),

            TextInput::make('subtitle')
              ->label('Subjudul')
              ->maxLength(255),

            TextInput::make('isbn')
              ->label('ISBN')
              ->unique(ignoreRecord: true)
              ->maxLength(17),

            TextInput::make('buku_edisi')
              ->label('Buku Edisi'),
          ])
          ->columns(2),



        Section::make('Penulis & Penerbit')
          ->schema([
            self::getWriterSelectField(),
            self::getPublisherSelectField(),
          ])
          ->columns(2),

        Section::make('Detail Buku')
          ->schema([
            TextInput::make('stock')
              ->label('Stok')
              ->numeric()
              ->minValue(0)
              ->default(0),

            TextInput::make('type')
              ->label('Jenis')
              ->maxLength(100),

            TextInput::make('publication_year')
              ->label('Tahun Terbit')
              ->numeric()
              ->minValue(1000)
              ->maxValue(date('Y') + 5),

            TextInput::make('rack_location')
              ->label('Lokasi Rak')
              ->integer()
              ->maxLength(50),

            Toggle::make('is_student_work')
              ->label('Karya Siswa')
              ->default(false),

            Toggle::make('is_ebook')
              ->label('Ebook')
              ->default(false)
              ->live()
              ->afterStateUpdated(function ($state, Forms\Set $set) {
                if (!$state) {
                  $set('ebook_type', null);
                  $set('ebook_link', null);
                  $set('ebook_file_path', null);
                }
              }),
          ])
          ->columns(3),

        Section::make('Pengaturan Ebook')
          ->schema([
            Select::make('ebook_type')
              ->label('Tipe Ebook')
              ->options([
                EbookTypeEnum::LINK->value => EbookTypeEnum::LINK->label(),
                EbookTypeEnum::PDF->value => EbookTypeEnum::PDF->label(),
              ])
              ->live()
              ->afterStateUpdated(function ($state, Forms\Set $set) {
                if ($state === EbookTypeEnum::LINK->value) {
                  $set('ebook_file_path', null);
                } elseif ($state === EbookTypeEnum::PDF->value) {
                  $set('ebook_link', null);
                }
              }),

            TextInput::make('ebook_link')
              ->label('Link Ebook')
              ->url()
              ->maxLength(500)
              ->placeholder('https://example.com/ebook.pdf')
              ->visible(fn(Forms\Get $get) => $get('ebook_type') === EbookTypeEnum::LINK->value)
              ->columnSpanFull(),

            FileUpload::make('ebook_file_path')
              ->label('Upload PDF Ebook')
              ->directory('ebooks')
              ->acceptedFileTypes(['application/pdf'])
              ->maxSize(50 * 1024) // 50MB
              ->downloadable()
              ->previewable()
              ->visible(fn(Forms\Get $get) => $get('ebook_type') === EbookTypeEnum::PDF->value)
              ->columnSpanFull(),
          ])
          ->visible(fn(Forms\Get $get) => $get('is_ebook'))
          ->columns(1),

        Section::make('Informasi Tambahan')
          ->schema([
            TextInput::make('source')
              ->label('Sumber')
              ->maxLength(255),

            TextInput::make('catalog_code')
              ->label('Kode Katalog')
              ->integer()
              ->maxLength(50),

            TextInput::make('subject')
              ->label('Subjek')
              ->maxLength(255),

            Textarea::make('abstract')
              ->label('Abstrak')
              ->rows(3)
              ->columnSpanFull(),

            Select::make('categories')
              ->label('Kategori')
              ->relationship('categories', 'name')
              ->multiple()
              ->preload()
              ->columnSpanFull(),
          ])
          ->columns(2),

        Section::make('Cover Buku')
          ->schema([
            FileUpload::make('cover_image_path')
              ->label('Cover Buku')
              ->directory('cover-books')
              ->image()
              ->imageEditor()
              ->imagePreviewHeight('200')
              ->downloadable()
              ->maxSize(2048)
              ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
              ->columnSpanFull(),
          ]),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        ImageColumn::make('cover_image_path')
          ->label('Cover'),

        TextColumn::make('title')
          ->label('Judul Buku')
          ->searchable()
          ->sortable()
          ->wrap()
          ->limit(50),

        TextColumn::make('writer.name')
          ->label('Penulis')
          ->searchable()
          ->sortable()
          ->default('-')
          ->limit(30),

        TextColumn::make('publisher.name')
          ->label('Penerbit')
          ->searchable()
          ->default('-')
          ->limit(30)
          ->toggleable(isToggledHiddenByDefault: true),

        TextColumn::make('publication_year')
          ->label('Tahun')
          ->sortable()
          ->default('-')
          ->alignCenter(),

        TextColumn::make('isbn')
          ->label('ISBN')
          ->searchable()
          ->default('-')
          ->toggleable(isToggledHiddenByDefault: true),

        TextColumn::make('stock')
          ->label('Stok')
          ->sortable()
          ->alignCenter()
          ->badge()
          ->color(fn($state) => $state > 0 ? 'success' : 'danger'),

        IconColumn::make('is_ebook')
          ->label('Ebook')
          ->boolean()
          ->alignCenter(),

        TextColumn::make('ebook_type')
          ->label('Tipe Ebook')
          ->badge()
          ->color(fn($state) => match ($state) {
            'link' => 'info',
            'pdf' => 'success',
            default => 'gray'
          })
          ->formatStateUsing(fn($state) => match ($state) {
            'link' => 'Link',
            'pdf' => 'PDF',
            default => '-'
          })
          ->toggleable(isToggledHiddenByDefault: true),

        TextColumn::make('rack_location')
          ->label('Lokasi Rak')
          ->default('-')
          ->alignCenter(),

        IconColumn::make('is_student_work')
          ->label('Karya Siswa')
          ->boolean()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->defaultSort('created_at', 'desc')
      ->filters([
        SelectFilter::make('writer_id')
          ->label('Penulis')
          ->relationship('writer', 'name')
          ->searchable()
          ->preload(),

        SelectFilter::make('publisher_id')
          ->label('Penerbit')
          ->relationship('publisher', 'name')
          ->searchable()
          ->preload(),

        Filter::make('has_stock')
          ->label('Ada Stok')
          ->query(fn(Builder $query) => $query->where('stock', '>', 0)),

        TernaryFilter::make('is_student_work')
          ->label('Karya Siswa'),

        TernaryFilter::make('is_ebook')
          ->label('Ebook'),

        SelectFilter::make('ebook_type')
          ->label('Tipe Ebook')
          ->options([
            'link' => 'Link URL',
            'pdf' => 'Upload PDF',
          ]),
      ])
      ->actions([
        ViewAction::make(),
        EditAction::make(),
        Action::make('view_ebook')
          ->label('Buka Ebook')
          ->icon('heroicon-o-eye')
          ->color('success')
          ->url(fn(Book $record): ?string => $record->ebook_url)
          ->openUrlInNewTab()
          ->visible(fn(Book $record): bool => $record->is_ebook_available),
        Action::make('print_card')
          ->label('Print Kartu')
          ->icon('heroicon-o-printer')
          ->color('info')
          ->url(fn(Book $record): string => route('book.print-card', $record))
          ->openUrlInNewTab(),
      ])
      ->bulkActions([
        BulkActionGroup::make([
          ExportBulkAction::make()
            ->label('Export ke Excel'),

          BulkAction::make('print_cards')
            ->label('Print Kartu Buku')
            ->icon('heroicon-o-printer')
            ->color('info')
            ->action(self::printCardsBulkAction())
            ->deselectRecordsAfterCompletion()
            ->requiresConfirmation()
            ->modalDescription('Maksimal 30 buku yang dapat dicetak sekaligus.'),

          DeleteBulkAction::make(),
        ]),
      ])
      ->emptyStateActions([
        CreateAction::make(),
      ]);
  }

  public static function getRelations(): array
  {
    return [];
  }

  public static function getPages(): array
  {
    return [
      'index' => ListBooks::route('/'),
      'create' => CreateBook::route('/create'),
      'view' => ViewBook::route('/{record}'),
      'edit' => EditBook::route('/{record}/edit'),
    ];
  }

  public static function canViewAny(): bool
  {
    return Auth::check() && Auth::user()->hasAnyRole([
        RoleEnum::ADMIN->value,
        RoleEnum::PETUGAS->value
      ]);
  }

  // Private helper methods
  private static function getWriterSelectField(): Select
  {
    return Select::make('writer_id')
      ->label('Penulis')
      ->relationship('writer', 'name')
      ->searchable()
      ->preload()
      ->createOptionForm([
        TextInput::make('name')
          ->label('Nama Penulis')
          ->required()
          ->maxLength(255),
        Textarea::make('biography')
          ->label('Biografi')
          ->maxLength(1000)
          ->rows(3),
        TextInput::make('email')
          ->label('Email')
          ->email()
          ->maxLength(255),
        TextInput::make('phone')
          ->label('Telepon')
          ->tel()
          ->maxLength(20),
        Textarea::make('address')
          ->label('Alamat')
          ->maxLength(500)
          ->rows(2),
      ])
      ->createOptionUsing(fn(array $data) => Writer::create($data)->id)
      ->createOptionModalHeading('Tambah Penulis Baru');
  }

  private static function getPublisherSelectField(): Select
  {
    return Select::make('publisher_id')
      ->label('Penerbit')
      ->relationship('publisher', 'name')
      ->searchable()
      ->preload()
      ->createOptionForm([
        TextInput::make('name')
          ->label('Nama Penerbit')
          ->required()
          ->maxLength(255),
        TextInput::make('address')
          ->label('Alamat')
          ->maxLength(500),
        TextInput::make('phone')
          ->label('Telepon')
          ->tel()
          ->maxLength(20),
      ])
      ->createOptionUsing(fn(array $data) => Publisher::create($data)->id)
      ->createOptionModalHeading('Tambah Penerbit Baru');
  }

  private static function printCardsBulkAction(): \Closure
  {
    return function ($records) {
      if ($records->count() > 30) {
        Notification::make()
          ->title('Terlalu Banyak Buku')
          ->body('Maksimal 30 buku yang dapat dicetak sekaligus.')
          ->danger()
          ->send();
        return;
      }

      $bookIds = $records->pluck('id')->toArray();
      $url = route('book.print-cards-bulk', ['ids' => implode(',', $bookIds)]);

      return redirect($url);
    };
  }
}
