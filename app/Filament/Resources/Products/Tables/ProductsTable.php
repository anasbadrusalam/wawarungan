<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Size;
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
                    ->searchable(['name', 'description', 'sku', 'internal_reference', 'barcode']),
                // TextColumn::make('sku')
                //     ->label('SKU')
                //     ->searchable(),
                SpatieTagsColumn::make('tags')
                    ->size('large')
                    ->type('product_tags')
                    ->wrap()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('tags', function (Builder $q) use ($search) {
                            $q->where('type', 'product_tags') // Mengunci pencarian hanya pada tipe ini
                                ->where('name', 'like', "%{$search}%");
                        });
                    }),
                TextColumn::make('cost')
                    ->badge()
                    ->size('large')
                    ->color('danger')
                    ->money(locale: config('money.locale'), currency: config('money.currency'), decimalPlaces: config('money.decimal_places'),)
                    ->sortable(),
                TextColumn::make('price')
                    ->badge()
                    ->size('large')
                    ->color('success')
                    ->money(locale: config('money.locale'), currency: config('money.currency'), decimalPlaces: config('money.decimal_places'),)
                    ->sortable(),
                IconColumn::make('manage_stock')
                    ->boolean(),
                TextColumn::make('stock')
                    ->badge()
                    ->size('large')
                    ->color('info')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                TextColumn::make('brand.name')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->persistFiltersInSession()
            ->persistSortInSession()
            ->defaultSort('created_at', 'desc');
    }
}
