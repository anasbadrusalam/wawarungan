<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->wrap()
                    ->searchable(['name', 'description']),
                SpatieTagsColumn::make('tags')
                    ->wrap()
                    ->type('product_tags')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('tags', function (Builder $q) use ($search) {
                            $q->where('type', 'product_tags')->where('name', 'like', "%{$search}%");
                        });
                    }),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                // TextColumn::make('slug')
                //     ->searchable(),
                TextColumn::make('code')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('barcode')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('cost')
                    ->money(locale: config('money.locale'), currency: config('money.currency'), decimalPlaces: config('money.decimal_places'))
                    ->badge()
                    ->size('large')
                    ->color('danger')
                    ->sortable(),
                TextColumn::make('price')
                    ->money(locale: config('money.locale'), currency: config('money.currency'), decimalPlaces: config('money.decimal_places'))
                    ->badge()
                    ->size('large')
                    ->color('success')
                    ->sortable(),                
                IconColumn::make('manage_stock')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->boolean(),
                TextColumn::make('category.name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('brand.name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkActionGroup::make([
                        // EditAction::make(),
                    ]),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
