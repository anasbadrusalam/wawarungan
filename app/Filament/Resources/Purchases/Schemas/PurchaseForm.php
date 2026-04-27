<?php

namespace App\Filament\Resources\Purchases\Schemas;

use App\Enums\PurchaseStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Schema;

class PurchaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('supplier_id')
                    ->relationship('supplier', 'name')
                    ->required(),
                Select::make('status')
                    ->disabled()
                    ->options(PurchaseStatus::class)
                    ->default(PurchaseStatus::Draft),
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
                        Flex::make([
                            TextInput::make('quantity')
                                ->numeric()
                                ->required(),
                            TextInput::make('unit_price')
                                ->columnSpanFull()
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
