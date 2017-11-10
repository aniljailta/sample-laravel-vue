<?php

namespace App\Validators\Order;

use App\Models\Order;
use App\Validators\Validator;
use App\Validators\EnchantValidatorTrait;

use Store;
use DB;
use Log;
use Entrust;

class OrderValidator extends Validator {

    use EnchantValidatorTrait;
}