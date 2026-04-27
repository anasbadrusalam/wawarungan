<?php

namespace App\Filament\Resources\Purchases\Schemas;

use App\Enums\PurchaseStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;

class PurchaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('supplier_id')
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->unique(ignoreRecord: true)
                            ->required(),
                    ]),
                Grid::make([
                    'default' => 2,
                    'sm' => 2,
                ])
                    ->dense()
                    ->schema([
                        Select::make('status')
                            ->disabled()
                            ->options(PurchaseStatus::class)
                            ->default(PurchaseStatus::Draft),
                        Select::make('store_id')
                            ->relationship('store', 'name')
                            ->default(1)
                    ]),

                Repeater::make('items')
                    ->label('Products')
                    ->dense()
                    ->relationship()
                    ->schema([
                        Select::make('product_id')
                            ->searchable()
                            ->preload()
                            ->relationship('product', 'name')
                            ->required(),
                        Grid::make([
                            'default' => 4,
                            'sm' => 4,
                        ])
                            ->dense()
                            ->schema([
                                TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required(),
                                TextInput::make('price')
                                    ->columnSpan([
                                        'default' => 3,
                                        'sm' => 3,
                                    ])
                                    ->type('number')
                                    ->minValue(0)
                                    ->numeric()
                                    ->required(),

                            ]),

                    ])
                    ->defaultItems(1)
                    ->minItems(1)
                    ->columnSpanFull(),
            ]);
    }
}
