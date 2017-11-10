<?php
namespace App\Http\Controllers;

use App\Http\Requests\Agreements\StoreAgreement;
use App\Models\Company;
use App\Models\Fee;
use Illuminate\Contracts\View\View;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Carbon\Carbon;
use Store;

class ServiceAgreementController extends Controller
{
    /**
     * @var Company
     */
    private $company;

    /**
     * @var AuthManager
     */
    private $authManager;

    /**
     * @var ViewFactory
     */
    private $viewFactory;

    /**
     * @var Carbon
     */
    private $carbon;

    /**
     * @var Fee
     */
    private $fee;

    /**
     * ServiceAgreementController constructor.
     *
     * @param Company $company
     * @param Fee $fee
     * @param AuthManager $authManager
     * @param ViewFactory $viewFactory
     * @param Carbon $carbon
     */
    public function __construct(
        Company $company,
        Fee $fee,
        AuthManager $authManager,
        ViewFactory $viewFactory,
        Carbon $carbon
    ) {
        $this->company = $company;
        $this->fee = $fee;
        $this->authManager = $authManager;
        $this->viewFactory = $viewFactory;
        $this->carbon = $carbon;
    }

    /**
     * @return View
     */
    public function show()
    {
        $company = Store::get('company');
        $user = $this->authManager->user();
        $today = $this->carbon->now($company->time_zone);
        $fees = [];
        foreach ($company->fees as $fee) {
            $fees[$fee->slug] = $fee;
        }

        if (!$company->fees->count()) {
            $this->authManager->logout();

            return redirect()->to('login')
                ->with(['message' => 'A service agreement does not exist for this account. Please contact customer support for additional information']);
        }

        return $this->viewFactory->make('agreement.show', compact('company', 'today', 'user', 'fees'));
    }

    /**
     * @param StoreAgreement $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreAgreement $request)
    {
        $company = Store::get('company');
        $company->service_agreement_accepted = $request->get('service_agreement_accepted') ?? 0;
        $company->save();

        return redirect()->to('/');
    }
}