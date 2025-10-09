<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GoldRate;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class FetchGoldRate extends Command
{
    protected $signature = 'gold:fetch';
    protected $description = 'Fetch gold price from API and store/update DB';

    public function handle()
    {
        $this->info("Fetching Gold Rate...");

        $url = "https://www.goldapi.io/api/XAU/INR";
        $response = Http::withHeaders([
            'x-access-token' => 'goldapi-r72pslvwlb57r-io',
        ])->get($url);

        if ($response->failed()) {
            $this->error("API call failed!");
            return 1;
        }

        $data = $response->json();
        if (!$data) {
            $this->error("No data returned from API");
            return 1;
        }

        // Store/Update in DB
        GoldRate::updateOrCreate(
            [
                'currency' => $data['currency'] ?? 'INR',
                'fetched_at' => Carbon::now()->format('Y-m-d H:i:00')
            ],
            [
                'live_price'       => $data['price'] ?? null,
                'price_gram_24k'   => $data['price_gram_24k'] ?? null,
                'price_gram_22k'   => $data['price_gram_22k'] ?? null,
                'price_gram_21k'   => $data['price_gram_21k'] ?? null,
                'price_gram_20k'   => $data['price_gram_20k'] ?? null,
                'price_gram_18k'   => $data['price_gram_18k'] ?? null,
                'price_gram_16k'   => $data['price_gram_16k'] ?? null,
                'price_gram_14k'   => $data['price_gram_14k'] ?? null,
                'price_gram_10k'   => $data['price_gram_10k'] ?? null,
            ]
        );

        $this->info("Gold rate updated successfully!");
        return 0;
    }
}
