<?php

namespace App\Filament\Resources\Purchases\Schemas;

use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class PurchaseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->dense()
            ->columns([
                'default' => 2,
                'sm' => 2,
            ])
            ->components([
                TextEntry::make('supplier.name')
                    ->label('Supplier'),
                TextEntry::make('status'),
                RepeatableEntry::make('items')
                    ->dense()
                    ->columnSpanFull()
                    ->label('Products')
                    ->columns([
                        'default' => 2,
                        'sm' => 2,
                    ])
                    ->schema([
                        TextEntry::make('product.name')
                            ->wrap()
                            ->label('Product'),
                        Grid::make([
                            'default' => 6,
                            'sm' => 6,
                        ])
                            ->dense()
                            ->schema([
                                TextEntry::make('quantity')
                                    ->badge()
                                    ->color('info')
                                    ->size('large')
                                    ->columnSpan([
                                        'default' => 2,
                                        'sm' => 2,
                                    ]),
                                TextEntry::make('price')
                                    ->columnSpan([
                                        'default' => 4,
                                        'sm' => 4,
                                    ])
                                    ->badge()
                                    ->color('danger')
                                    ->size('large')
                                    ->money(locale: 'id', currency: 'IDR'),
                            ]),
                    ]),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
