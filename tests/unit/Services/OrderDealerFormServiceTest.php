<?php
namespace Tests\Unit\Services;

use App\Models\FileSign;
use App\Services\Orders\Dealer\OrderDealerFormService;
use PHPUnit\Framework\TestCase;;

class OrderDealerFormServiceTest extends \TestCase
{
    /**
     * @test
     */
    public function testCreation(): void
    {
        $service = new OrderDealerFormService();

        $this->assertInstanceOf(OrderDealerFormService::class, $service);
    }

    /**
     * @test
     */
    public function testGetFileSignatureDataWithFalseResults(): void
    {
        $service = new OrderDealerFormService();

        $fileSign1 = new FileSign();
        $fileSign1->file_id = 1;
        $fileSign1->is_esigned = 1;
        $fileSign1->signer_role = 'rto_company';

        $fileSign2 = new FileSign();
        $fileSign2->file_id = 1;
        $fileSign1->is_esigned = 0;
        $fileSign2->signer_role = 'something_ubnormal';

        $signatures = collect([$fileSign1, $fileSign2]);

        $this->assertEquals(collect([$fileSign1, $fileSign2]), $signatures);

        $expected = [
            'is_esigned' => false,
            'signer_role' => null
        ];

        $result = $service->getFileSignatureDataByRole($signatures, '');

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function testGetFileSignatureDataWithCustomerButNotEsigned(): void
    {
        $service = new OrderDealerFormService();

        $fileSign1 = new FileSign();
        $fileSign1->file_id = 1;
        $fileSign1->is_esigned = 0;
        $fileSign1->signer_role = 'rto_company';

        $fileSign2 = new FileSign();
        $fileSign2->file_id = 1;
        $fileSign2->is_esigned = 1;
        $fileSign2->signer_role = 'something_ubnormal';

        $fileSign3 = new FileSign();
        $fileSign3->file_id = 1;
        $fileSign3->is_esigned = 0;
        $fileSign3->signer_role = 'customer';

        $signatures = collect([$fileSign1, $fileSign2, $fileSign3]);

        $expected = [
            'is_esigned' => false,
            'signer_role' => 'customer'
        ];

        $result = $service->getFileSignatureDataByRole($signatures, 'customer');

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function testGetFileSignatureData(): void
    {
        $service = new OrderDealerFormService();

        $fileSign1 = new FileSign();
        $fileSign1->file_id = 1;
        $fileSign1->is_esigned = 0;
        $fileSign1->signer_role = 'rto_company';

        $fileSign2 = new FileSign();
        $fileSign2->file_id = 1;
        $fileSign2->is_esigned = 1;
        $fileSign2->signer_role = 'something_ubnormal';

        $fileSign3 = new FileSign();
        $fileSign3->file_id = 1;
        $fileSign3->is_esigned = 1;
        $fileSign3->signer_role = 'customer';

        $signatures = collect([$fileSign1, $fileSign2, $fileSign3]);

        $expected = [
            'is_esigned' => true,
            'signer_role' => 'customer'
        ];

        $result = $service->getFileSignatureDataByRole($signatures, 'customer');

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function testGetFileSignatureDataWithEmptyCollection(): void
    {
        $service = new OrderDealerFormService();
        $signatures = collect([]);
        $expected = [
            'is_esigned' => false,
            'signer_role' => 'customer'
        ];

        $result = $service->getFileSignatureDataByRole($signatures, 'customer');

        $this->assertEquals($expected, $result);
    }
}