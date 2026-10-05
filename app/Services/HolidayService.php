<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HolidayService
{
    /**
     * Get holiday data for a specific year.
     * Caches the result to avoid repeated API calls.
     */
    public function getHolidaysForYear(int $year): array
    {
        return Cache::remember("holidays_{$year}", now()->addDays(30), function () use ($year) {
            try {
                Log::info("Fetching holiday data from API for year: {$year}");
                $response = Http::timeout(5)->get("https://api-hari-libur.vercel.app/api?year={$year}");
                
                if ($response->successful()) {
                    $data = $response->json();
                    $holidays = [];
                    if (isset($data['data']) && is_array($data['data'])) {
                        foreach ($data['data'] as $item) {
                            if (isset($item['date']) && isset($item['description'])) {
                                $holidays[$item['date']] = $item['description'];
                            }
                        }
                    }
                    Log::info("Successfully fetched holidays for {$year}: " . count($holidays) . " days found.", ['data' => $holidays]);
                    return $holidays;
                }
                
                Log::warning("API request failed with status: {$response->status()}");
            } catch (\Exception $e) {
                Log::error("Error fetching holiday data: " . $e->getMessage());
            }
            
            return [];
        });
    }

    /**
     * Check if a specific date is a holiday.
     * 
     * @param Carbon|string $date
     * @return string|null Returns the holiday name if it's a holiday, null otherwise.
     */
    public function getHolidayName($date): ?string
    {
        $carbonDate = $date instanceof Carbon ? $date : Carbon::parse($date);
        $year = $carbonDate->year;
        $dateString = $carbonDate->format('Y-m-d');
        
        $holidays = $this->getHolidaysForYear($year);
        
        return $holidays[$dateString] ?? null;
    }
    
    /**
     * Check if a specific date is a holiday or weekend.
     */
    public function isHolidayOrWeekend($date): bool
    {
        $carbonDate = $date instanceof Carbon ? $date : Carbon::parse($date);
        
        if ($carbonDate->isWeekend()) {
            return true;
        }
        
        return $this->getHolidayName($carbonDate) !== null;
    }
}
