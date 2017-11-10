<?php

namespace App\Jobs\Orders;

use Store;
use App\Models\FileSign;
use App\Models\Order;
use App\Models\RtoCompany;
use App\Models\Company;
use App\Jobs\Job;
use App\Notifications\RtoCompanies\OrderRtoSignatureRequested;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderDocumentsToRtoCompany extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $queue;

    /**
     * @var Order
     */
    public $order;

    /**
     * @var Company
     */
    public $company;

    /**
     * @var FileSign
     */
    public $fileSign;

    /**
     * Create the job instance.
     * @param Order    $order
     * @param FileSign $fileSign
     */
    public function __construct(Order $order, FileSign $fileSign)
    {
        $this->company = Store::get('company');
        $this->order = $order;
        $this->fileSign = $fileSign;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->applyTenantScope($this->company);
        $rtoCompany = $this->order->rto_company;
        $rtoCompany->notify(new OrderRtoSignatureRequested($this->order, $this->fileSign));
    }
}
