<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\ProductType;
use Filament\Forms\Components\Select;
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
                Toggle::make('manage_stock')
                    ->columnSpanFull()
                    ->default(true)
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Select::make('type')
                    ->options(ProductType::class)
                    ->default(ProductType::Goods)
                    ->required(),
                TextInput::make('sku')
                    ->label('SKU'),
                TextInput::make('barcode'),
                Select::make('category_id')
                    ->relationship('category', 'name'),
                Select::make('brand_id')
                    ->relationship('brand', 'name'),
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
                SpatieTagsInput::make('tags')
                    ->type('product_tags')
                    ->columnSpanFull(),           
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
