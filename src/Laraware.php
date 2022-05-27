<?php

namespace Kirko\Larawave;

use Kirko\Larawave\Helpers\Banks;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Kirko\Larawave\Helpers\Payments;
use Kirko\Larawave\Helpers\Transfers;
use Kirko\Larawave\Helpers\Subaccount;
use Kirko\Larawave\Helpers\Beneficiary;
use Kirko\Larawave\Helpers\verification;

class Laraware
{
    protected $publicKey;
    protected $secretKey;
    protected $baseUrl;

    function __construct()
    {
        $this->publicKey = config('flutterwave.publicKey');
        $this->secretKey = config('flutterwave.secretKey');
        $this->secretHash = config('flutterwave.secretHash');
        $this->baseUrl = "https://api.flutterwave.com/v3";

    }

    /**
     * Generates a unique reference
     * @param $transactionPrefix
     * @return string
     */
    public function generateReference(String $transactionPrefix = Null)
    {
        if($transactionPrefix)
        {
            return $transactionPrefix . '-' . uniqid(time());
        }
        return 'flw_' . uniqid(time());
    }

    /**
     * Reaches out to Flutterwave to initialize a payment
     * @param $data
     * @return object
     */
    public function initializePayment(array $data)
    {
        $payment = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/payments',
            $data
        )->json();

        return $payment;
    }

    /**
     * Gets a transaction ID depending on the redirect structure
     * @return string
     */
    public function getTransactionId()
    {
        $transactionId = request()->getTransactionId;

        if(!$transactionId) {
            $transactionId = json_decode(request()->resp)->data->id;
        }

        return $transactionId;
    }

    /**
     * Reaches out to Flutterwave to validate a charge
     * @param $data
     * @return object
     */
    public function validateCharge(array $data)
    {
        $payment = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/validations/charge',
            $data
        )->json();

        return $payment;
    }

    /**
     * Reaches out to Flutterwave to verify a transaction
     * @param $id
     * @return object
     */
    public function verifyTransaction($id)
    {
        $payment = Http::withToken($this->secretKey)->get(
            $this->baseUrl . '/transactions/' . $id . '/verify'
        )->json();

        return $payment;
    }

    /**
     * Confirms webhook `verifi-hash` is the same as the environment variable
     * @param $data
     * @return boolean
     */
    public function verifyWebHook()
    {
        if(request()->header('verify-hash')) {
            $signature = request()->header('verify-hash');

            if($signature == $this->secretHash) {
                return true;
            }
        }
        return false;
    }

    /**
     * Payments
     * @return object
     */
    public function payments()
    {
        $payments = new Payments($this->publicKey, $this->secretKey, $this->baseUrl, $this->encryptionKey);
        return $payments;
    }

    /**
     * Verification
     * @return object
     */
    public function verification()
    {
        $verification = new Verification($this->publicKey, $this->secretKey, $this->baseUrl);
        return $verification;
    }

    /**
     * Transfers
     * @return object
     */
    public function transfers()
    {
        $transfers = new Transfers($this->publicKey, $this->secretKey, $this->baseUrl);
        return $transfers;
    }

    /**
     * Bank
     * @return object
     */
    public function bank()
    {
        $bank = new Banks($this->publicKey, $this->secretKey, $this->baseUrl);
        return $bank;
    }

    /**
     * Beneficiary
     * @return object
     */
    public function beneficiary()
    {
        $beneficiary = new Beneficiary($this->publicKey, $this->secretKey, $this->baseUrl);
        return $beneficiary;
    }

    /**
     * Subaccount
     * @return object
     */
    public function subaccount()
    {
        $subaccount = new Subaccount($this->publicKey, $this->secretKey, $this->baseUrl);
        return $subaccount;
    }
}