<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Exception;

class StockService
{

    public function increase($productId, $locationId, $quantity, $reference = null, $note = null)
    {
        return DB::transaction(function () use ($productId, $locationId, $quantity, $reference, $note) {

            $stock = Stock::lockForUpdate()
                ->firstOrCreate(
                    [
                        'product_id' => $productId,
                        'location_id' => $locationId
                    ],
                    ['quantity' => 0]
                );

            $stock->quantity += $quantity;
            $stock->save();

            StockMovement::create([
                'product_id' => $productId,
                'location_id' => $locationId,
                'type' => 'in',
                'quantity' => $quantity,
                'reference' => $reference,
                'note' => $note
            ]);

            return $stock;
        });
    }

    public function decrease($productId, $locationId, $quantity, $reference = null, $note = null)
    {
        return DB::transaction(function () use ($productId, $locationId, $quantity, $reference, $note) {

            $stock = Stock::lockForUpdate()
                ->where('product_id', $productId)
                ->where('location_id', $locationId)
                ->first();

            if (!$stock || $stock->quantity < $quantity) {
                throw new Exception('Stok tidak cukup');
            }

            $stock->quantity -= $quantity;
            $stock->save();

            StockMovement::create([
                'product_id' => $productId,
                'location_id' => $locationId,
                'type' => 'out',
                'quantity' => $quantity,
                'reference' => $reference,
                'note' => $note
            ]);

            return $stock;
        });
    }

    public function transfer($productId, $fromLocationId, $toLocationId, $quantity, $reference = null)
    {
        return DB::transaction(function () use ($productId, $fromLocationId, $toLocationId, $quantity, $reference) {

            // Kurangi dari asal
            $this->decrease($productId, $fromLocationId, $quantity, $reference, 'Transfer OUT');

            // Tambah ke tujuan
            $this->increase($productId, $toLocationId, $quantity, $reference, 'Transfer IN');

            // Catatan tambahan (opsional)
            StockMovement::create([
                'product_id' => $productId,
                'location_id' => $fromLocationId,
                'type' => 'transfer',
                'quantity' => $quantity,
                'reference' => $reference,
                'note' => 'Transfer ke location ' . $toLocationId
            ]);

            return true;
        });
    }
}
