<?php

namespace App\Services;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use Exception;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function create($supplierId, $locationId, array $items)
    {
        return DB::transaction(function () use ($supplierId, $locationId, $items) {

            $purchase = Purchase::create([
                'supplier_id' => $supplierId,
                'location_id' => $locationId,
                'status' => 'draft',
            ]);

            foreach ($items as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            return $purchase->load('items');
        });
    }

    public function order($purchaseId)
    {
        return DB::transaction(function () use ($purchaseId) {

            $purchase = Purchase::lockForUpdate()->findOrFail($purchaseId);

            if ($purchase->status !== 'draft') {
                throw new Exception('Hanya draft yang bisa diorder');
            }

            $purchase->update([
                'status' => 'ordered',
                'ordered_at' => now(),
            ]);

            return $purchase;
        });
    }

    public function receive($purchaseId)
    {
        return DB::transaction(function () use ($purchaseId) {

            $purchase = Purchase::with('items')
                ->lockForUpdate()
                ->findOrFail($purchaseId);

            if ($purchase->status !== 'ordered') {
                throw new Exception('Purchase belum diorder');
            }

            foreach ($purchase->items as $item) {
                $this->stockService->increase(
                    $item->product_id,
                    $purchase->location_id,
                    $item->qty,
                    'PO-' . $purchase->id,
                    'Purchase Receive'
                );
            }

            $purchase->update([
                'status' => 'received',
                'received_at' => now(),
            ]);

            return $purchase;
        });
    }

    public function complete($purchaseId)
    {
        $purchase = Purchase::findOrFail($purchaseId);

        if ($purchase->status !== 'received') {
            throw new Exception('Belum diterima');
        }

        $purchase->update([
            'status' => 'completed'
        ]);

        return $purchase;
    }
}
