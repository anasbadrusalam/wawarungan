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
                    ->required(),
                Select::make('status')
                    ->disabled()
                    ->options(PurchaseStatus::class)
                    ->default(PurchaseStatus::Draft),
                Repeater::make('items')
                    ->label('Products')
                    ->dense()
                    ->relationship()
                    // ->table([
                    //     TableColumn::make('Product')
                    //         ->alignment(Alignment::Start),
                    //     TableColumn::make('Qty')
                    //         ->width('120px')
                    //         ->alignment(Alignment::Start),
                    //     TableColumn::make('Unit Price')
                    //         ->alignment(Alignment::Start),
                    // ])
                    ->schema([
                        Select::make('product_id')
                            ->searchable()
                            ->preload()
                            ->relationship('product', 'name')
                            ->required(),
                        TextInput::make('quantity')
                            ->type('number')
                            ->minValue(1)
                            ->required(),
                        TextInput::make('unit_price')
                            ->type('number')
                            ->minValue(0)
                            ->numeric()
                            ->required(),
                    ])
                    ->defaultItems(1)
                    ->minItems(1)
                    ->columnSpanFull(),
            ]);
    }
}
