<?php

use Illuminate\Database\Seeder;

class OptionCategoriesSeeder extends Seeder
{
    const ID = "id";
    const NAME = "name";
    const GROUP = "group";
    const IS_REQUIRED = "is_required";
    const QTY_LIMIT = "qty_limit";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
    
    public function run() {
        DB::table('option_categories')->truncate();

        DB::table('option_categories')->insert([
            [
                static::ID => 1,
                static::NAME => 'Miscellaneous',
                static::GROUP => 'misc',
                static::IS_REQUIRED => null,
                static::QTY_LIMIT => null,
                static::CREATED_AT => '2016-08-11 03:12:26',
                static::UPDATED_AT => '2016-08-11 03:12:26'
            ],
            [
                static::ID => 2,
                static::NAME => 'Door',
                static::GROUP => 'doors',
                static::IS_REQUIRED => 1,
                static::QTY_LIMIT => null,
                static::CREATED_AT => '2016-08-11 03:12:26',
                static::UPDATED_AT => '2016-08-11 03:12:26'
            ],
            [
                static::ID => 3,
                static::NAME => 'Window',
                static::GROUP => 'windows',
                static::IS_REQUIRED => null,
                static::QTY_LIMIT => null,
                static::CREATED_AT => '2016-08-11 03:12:26',
                static::UPDATED_AT => '2016-08-11 03:12:26'
            ],
            [
                static::ID => 4,
                static::NAME => 'Interior',
                static::GROUP => 'interior',
                static::IS_REQUIRED => null,
                static::QTY_LIMIT => null,
                static::CREATED_AT => '2016-08-11 03:12:26',
                static::UPDATED_AT => '2016-08-11 03:12:26'
            ],
            [
                static::ID => 5,
                static::NAME => 'Exterior',
                static::GROUP => 'exterior',
                static::IS_REQUIRED => null,
                static::QTY_LIMIT => null,
                static::CREATED_AT => '2016-08-11 03:12:26',
                static::UPDATED_AT => '2016-08-11 03:12:26'
            ],
            [
                static::ID => 6,
                static::NAME => 'Dealer access only',
                static::GROUP => 'dealers',
                static::IS_REQUIRED => null,
                static::QTY_LIMIT => null,
                static::CREATED_AT => '2016-08-11 03:12:26',
                static::UPDATED_AT => '2016-08-11 03:12:26'
            ],
            [
                static::ID => 7,
                static::NAME => 'Roof',
                static::GROUP => 'roof',
                static::IS_REQUIRED => 1,
                static::QTY_LIMIT => 1,
                static::CREATED_AT => '2016-08-11 03:12:26',
                static::UPDATED_AT => '2016-08-11 03:12:26'
            ],
            [
                static::ID => 8,
                static::NAME => 'Trim',
                static::GROUP => 'trim',
                static::IS_REQUIRED => 1,
                static::QTY_LIMIT => 1,
                static::CREATED_AT => '2016-08-11 03:12:26',
                static::UPDATED_AT => '2016-08-11 03:12:26'
            ],
            [
                static::ID => 9,
                static::NAME => 'Siding',
                static::GROUP => 'siding',
                static::IS_REQUIRED => 1,
                static::QTY_LIMIT => 1,
                static::CREATED_AT => '2016-08-11 03:12:26',
                static::UPDATED_AT => '2016-08-11 03:12:26'
            ],
            [
                static::ID => 10,
                static::NAME => 'Order',
                static::GROUP => 'order',
                static::IS_REQUIRED => null,
                static::QTY_LIMIT => null,
                static::CREATED_AT => '2016-08-11 03:12:26',
                static::UPDATED_AT => '2016-08-11 03:12:26'
            ]
        ]);
    }
}