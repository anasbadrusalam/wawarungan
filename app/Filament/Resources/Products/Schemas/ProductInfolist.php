<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\SpatieTagsEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('sku')
                    ->label('SKU')
                    ->placeholder('-'),
                TextEntry::make('slug'),
                SpatieTagsEntry::make('tags')
                    ->type('product_tags'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('internal_reference'),
                TextEntry::make('barcode')
                    ->placeholder('-'),
                TextEntry::make('cost')
                    ->money(),
                TextEntry::make('price')
                    ->money(),
                IconEntry::make('manage_stock')
                    ->boolean(),
                TextEntry::make('stock')
                    ->numeric(),
                TextEntry::make('category_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('brand_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
