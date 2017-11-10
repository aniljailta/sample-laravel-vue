<?php

use Uuid as Uuid;
use Exception as Exception;

use App\Models\Dealer;
use App\Models\Order;
use App\Models\OrderReference;
use App\Models\Sale;
use App\Models\Building;
use Illuminate\Database\Seeder;

class BuildingOrderSeeder0303 extends Seeder
{
    public $dealers;

    private $fieldMap = [
        'order' => []
    ];

    private $fields = [
        'orders' => [
            'serial_number' => "Serial Number",
            // 'total_sales_price' => "Total Price",
            'date_sold' => "Date sold",
            'total_sales_price' => "Retail Price Sold",
            'payment_type' => "Payment Method", // RTO | Outright
            'sales_tax' => "Sales Tax Collected",
            'sale_invoice_id' => "Invoice",
            'deposit_received' => "Deposit",
            'payment_method' => "Deposit Method", // CC | Cash | Check |  Check to 4 Seasons  | Square (CC)

            'shed_location'=> "Shed Location",
            'customer_name'=> "Customer",
            'dealer_name'=> "Dealer",
            'note_dealer'=> "Deposit note",
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Log::info(__CLASS__ . ' Start');
        
        Building::unguard(false);
        try {
            $this->getDealers();

            $file = Storage::get('usc_orders.csv');
            $this->parseFile($file);
        } catch (Exception $e) {
            dd($e);
        }

        Log::info(__CLASS__ . ' End');
    }

    /**
     * Parse data per each line, save some common values to store for next checkings/getters
     * @param $raw_file
     * @return bool
     */
    private function parseFile($raw_file)
    {
        if ( empty($raw_file) ) return false;

        $lines = explode(PHP_EOL, $raw_file);
        // first line (headers)
        $this->buildFieldMap(str_getcsv(array_shift($lines)));
        foreach($lines as $lineNum => $line)
        {
            $line = str_getcsv($line);
            if ( !isset($line[0]) || $line[0] == '' ) continue;

            DB::transaction(function() use($line, $lineNum) {
                $res = $this->generateEntities($line, $lineNum);
            });
        }
    }

    // should be validation here..
    private function buildFieldMap($headerRow)
    {
        // search building data
        foreach ($this->fields['orders'] as $bField => $bFieldName) {
            $index = array_search($bFieldName, $headerRow);
            if ($index !== false) {
                $this->fieldMap['orders'][$bField] = $index;
            }
        }
    }

    /**
     * Collect building data (some of fields should be requested/validated against DB)
     * @param $dataLine
     * @return array
     */
    private function generateEntities($dataLine, $lineNum)
    {
        $fieldMap = $this->fieldMap['orders'];

        $buildingSN = $dataLine[$fieldMap['serial_number']] ?: null;
        $dealerName = $dataLine[$fieldMap['dealer_name']] ?: null;

        if (!$buildingSN) return;
        if (empty($dataLine[$fieldMap['date_sold']]) ||
            empty($dataLine[$fieldMap['payment_type']]) ||
            empty($dataLine[$fieldMap['total_sales_price']])
        ) {
            Log::info(__CLASS__ . " (Line: {$lineNum}) Building SN: {$buildingSN} - NO REQUIRED COLUMNS  date_sold / payment_type / total_sales_price");
            return;
        }

        $building = Building::where('serial_number', $buildingSN)->first();
        if (!$building) {
            Log::info(__CLASS__ . " (Line: {$lineNum}) Building SN: {$buildingSN} - NOT FOUND");
            return;
        }

        if ($building->order_id) {
            Log::info(__CLASS__ . " (Line: {$lineNum}) Building SN: {$buildingSN} - EXIST ORDER {$building->order_id}");
            return;
        }

        $order = new Order();
        $sale = new Sale();
        $order->uuid = Uuid::generate(4)->string;
        $order->status_id = 'sale_generated';
        $order->building_id = $building->id;

        if($dealerName && !empty($this->dealers[$dealerName])) {
            $order->dealer_id = $this->dealers[$dealerName]->id;
        } else {
            $order->dealer_id = 1;
        }

        $order->total_sales_price = (float) $this->toInt($dataLine[$fieldMap['total_sales_price']]);
        $order->sales_tax = (float) $this->toInt($dataLine[$fieldMap['sales_tax']]);
        $order->deposit_received = (float) $this->toInt($dataLine[$fieldMap['deposit_received']]);
        $order->note_dealer = $dataLine[$fieldMap['note_dealer']];

        if ($building->order_type === 'inventory')                      $order->sale_type = 'dealer-inventory';
        elseif ($building->order_type === 'customer')                   $order->sale_type = 'custom-order';
        elseif ($dataLine[$fieldMap['shed_location']] === 'Customer')   $order->sale_type = 'custom-order';
        else $order->sale_type = 'dealer-inventory';

        if ($dataLine[$fieldMap['payment_type']] === 'RTO')      $order->payment_type = 'rto';
        if ($dataLine[$fieldMap['payment_type']] === 'Outright') $order->payment_type = 'cash';

        if ($dataLine[$fieldMap['payment_method']] === 'CC')                    $order->payment_method = 'credit_card';
        if ($dataLine[$fieldMap['payment_method']] === 'Cash')                  $order->payment_method = 'cash';
        if ($dataLine[$fieldMap['payment_method']] === 'Check to 4 Seasons')    $order->payment_method = 'check';
        if ($dataLine[$fieldMap['payment_method']] === 'Square')                $order->payment_method = 'credit_card';

        $customer = explode(' ', $dataLine[$fieldMap['customer_name']]);
        $lastName = '';
        foreach($customer as $i => $part) {
            if ($i === 0) $firstName = $part;
            else $lastName .= ' '.$part;
        }

        if (!preg_match("/(\d{1,2})\/(\d{1,2})\/(\d{4})$/", $dataLine[$fieldMap['date_sold']]) ) {
            Log::info(__CLASS__ . " (Line: {$lineNum}) Building # {$building->id}  SN: {$building->serial_number} - empty date sold");
        } else {
            $sale->created_at = date_create_from_format('m/d/Y', $dataLine[$fieldMap['date_sold']])->format('Y-m-d H:i:s');
            $order->created_at = date_create_from_format('m/d/Y', $dataLine[$fieldMap['date_sold']])->format('Y-m-d H:i:s');
            $order->order_date = date_create_from_format('m/d/Y', $dataLine[$fieldMap['date_sold']])->format('Y-m-d H:i:s');
        }


        $orderReference = new OrderReference([
            'first_name' => $firstName,
            'last_name' => $lastName,
        ]);
        $orderReference->save();
        $order->reference_id = $orderReference->id;
        $order->note_admin = 'migration';
        $order->save();

        $sale->status_id = 'invoiced';
        $sale->order_id = $order->id;
        $sale->building_id = $building->id;
        $sale->invoice_id = $dataLine[$fieldMap['sale_invoice_id']];
        $sale->save();

        $building->order_id = $order->id;
        $building->save();

        return [
            'order' => $order,
            'sale' => $sale,
            'building' => $building,
        ];
    }

    function toInt($str)
    {
        return preg_replace("/([^0-9\\.])/i", "", $str);
    }

    private function getDealers() {

        $dealers = Dealer::withTrashed()->get();
        $mappedDealers = $dealers->mapWithKeys(function ($item) {
            switch ($item['business_name']) {
                case '4 Seasons Motor Sports':
                    return ['4 Seasons' => $item];

                case 'A Country Trailer':
                    return ['A Country Trailer' => $item];

                case 'Urban Shed Concepts - Ash Fork':
                    return ['Ash Fork' => $item];

                case 'Camel Stop RV':
                    return ['Camel Stop' => $item];

                case 'Urban Shed Concepts - Camp Verde':
                    return ['Camp Verde' => $item];

                case 'Urban Shed Concepts Coolidge':
                    return ['Coolidge' => $item];

                case "Olsen's Grain":
                    return ["Olsen's" => $item];

                case 'Urban Shed Concepts - Quartzsite (Nuport Gift)':
                    return ['Quartzsite' => $item];

                case 'Urban Shed Concepts (43rd Ave)':
                    return ['USC 43rd' => $item];

                case 'Urban Shed Concepts (99th ave)':
                    return ['USC 99th' => $item];

                case 'D&H':
                    return ['D&H' => $item];

                case 'D&H Enterprises':
                    return ['D&H Enterprises' => $item];

                case 'Devry':
                    return ['Devry' => $item];

                case 'Furniture King':
                    return ['Furniture King' => $item];

                case 'Loumer':
                    return ['Loumer' => $item];
            }

            return [$item['business_name'] => $item];
        });

        $this->dealers = $mappedDealers;
        return $this->dealers;
    }
}
