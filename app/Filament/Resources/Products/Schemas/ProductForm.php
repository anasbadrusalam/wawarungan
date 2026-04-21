<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('sku')
                    ->label('SKU'),
                // TextInput::make('slug')
                //     ->visibleOn('edit')
                //     ->unique(ignoreRecord: true),
                SpatieTagsInput::make('tags')
                    ->type('product_tags'),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('internal_reference')
                    ->required(),
                TextInput::make('barcode'),
                TextInput::make('cost')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('$'),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('$'),
                Toggle::make('manage_stock')
                    ->required(),
                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('category_id')
                    ->numeric(),
                TextInput::make('brand_id')
                    ->numeric(),
            ]);
    }
}
