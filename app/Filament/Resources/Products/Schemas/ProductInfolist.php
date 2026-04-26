<?php

namespace App\Filament\Resources\Products\Schemas;

use Dom\Text;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\SpatieTagsEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->dense()
            ->components([
                Section::make('Details')
                    ->columnSpanFull()
                    ->dense()
                    ->columns([
                        'default' => 2,
                        'sm' => 2,
                    ])
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('sku')
                            ->label('SKU')
                            ->placeholder('-'),
                        TextEntry::make('code'),
                        TextEntry::make('barcode')
                            ->placeholder('-'),
                        TextEntry::make('category.name')
                            ->label('Category')
                            ->placeholder('-'),
                        TextEntry::make('brand.name')
                            ->label('Brand')
                            ->placeholder('-'),
                        TextEntry::make('cost')
                            ->badge()
                            ->size('large')
                            ->color('danger')
                            ->money(locale: config('money.locale'), currency: config('money.currency'), decimalPlaces: config('money.decimal_places')),
                        TextEntry::make('price')
                            ->badge()
                            ->size('large')
                            ->color('success')
                            ->money(locale: config('money.locale'), currency: config('money.currency'), decimalPlaces: config('money.decimal_places')),
                        SpatieTagsEntry::make('tags')
                            ->wrap()
                            ->type('product_tags')
                            ->label('Tags')
                            ->placeholder('-'),
                        TextEntry::make('description')
                            ->placeholder('-'),
                        // IconEntry::make('manage_stock')
                        //     ->boolean(),                
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ]),

                Section::make('Logs')
                    ->dense()
                    ->columnSpanFull()
                    ->schema([
                        RepeatableEntry::make('activities')
                            ->hiddenLabel()
                            ->dense()
                            ->contained(false)
                            ->columnSpanFull()
                            ->schema([
                                Flex::make([
                                    TextEntry::make('attribute_changes')
                                        ->grow(true)
                                        ->hiddenLabel()
                                        ->getStateUsing(function ($record) {

                                            $changes = $record->attribute_changes ?? [];

                                            if (is_string($changes)) {
                                                $changes = json_decode($changes, true);
                                            }

                                            $old = $changes['old'] ?? [];
                                            $new = $changes['attributes'] ?? [];

                                            if (empty($new)) {
                                                return '-';
                                            }

                                            $result = [];

                                            foreach ($new as $field => $value) {
                                                $label = ucfirst($field);

                                                $oldValue = $old[$field];

                                                // format angka ke rupiah tanpa desimal
                                                if (is_numeric($value)) {
                                                    $value = number_format((float) $value, 0, ',', '.');
                                                }

                                                if (is_numeric($oldValue)) {
                                                    $oldValue = number_format((float) $oldValue, 0, ',', '.');
                                                }

                                                $result[] = "{$label}: {$oldValue} → {$value}";
                                            }

                                            return implode('<br>', $result);
                                        })
                                        ->html(),
                                    TextEntry::make('created_at')
                                        ->grow(false)
                                        ->hiddenLabel()
                                        ->since()
                                ]),

                            ]),
                    ]),

            ]);
    }
}
