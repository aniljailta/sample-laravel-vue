<?php

namespace App\Http\Middleware;

use Store;
use Closure;
use Illuminate\Auth\AuthManager;

class ServiceAgreement
{
    /**
     * @var AuthManager
     */
    private $authManager;

    /**
     * ServiceAgreement constructor.
     *
     * @param AuthManager $authManager
     */
    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $company = Store::get('company');

        if (!$this->authManager->user()->hasRole('administrator') && $company->role_id !== 'super_admin') {
            $this->authManager->logout();

            return redirect()->to('login')
                ->with(['message' => 'Company administrator should accept Service Agreement before proceed']);
        }

        if (!$company->fees->count()) {
            $this->authManager->logout();

            return redirect()->to('login')
                ->with(['message' => 'A service agreement does not exist for this account. Please contact customer support for additional information']);
        }

        if (!$company->service_agreement_accepted && $company->role_id !== 'super_admin') {
            return redirect()->route('company.agreement.show');
        }

        return $next($request);
    }
}
