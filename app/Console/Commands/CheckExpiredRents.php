<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rent;
use App\Events\RentStatusUpdated;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

class CheckExpiredRents extends Command
{
    protected $signature = 'rent:check-expired';
    protected $description = 'Check expired rents and update item status';

    public function handle()
    {
        $now = now();

        // جلب السجلات المنتهية
        $expiredRents = Rent::where('rental_status', 'approved')
            ->where('end_date', '<', $now)
            ->get();

        if ($expiredRents->isEmpty()) {
            $this->info('لا توجد إيجارات منتهية.');
        } else {
            foreach ($expiredRents as $rent) {
                $this->info('تم العثور على إيجار منتهي: ' . $rent->id);
            }
        }

        // التحقق من السجلات المنتهية
        try {
            foreach ($expiredRents as $rent) {
                Log::info("Processing rent ID: {$rent->id}");

                // تحقق من وجود العنصر
                if ($rent->item) {
                    // تحقق إذا كانت الإيجار منتهية بالفعل قبل تحديث العنصر
                    $rent->item->update(['item_status' => 'available']);
                    Log::info("Updated item status to 'available' for item ID: {$rent->item->id}");
                } else {
                    Log::error("No item found for rent ID: {$rent->id}");
                }

                // بث الحدث
                Broadcast(new RentStatusUpdated($rent));
            }

            $this->info('Expired rentals processed and item statuses updated.');
        } catch (\Exception $e) {
            Log::error("Error processing expired rents: " . $e->getMessage());
            $this->error("حدث خطأ أثناء معالجة الإيجارات المنتهية.");
        }
    }
}
