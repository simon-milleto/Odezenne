<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Automattic\WooCommerce\Client;

use App\Exceptions\Handler;

use App\Settings;

use PayPal\Api\Payer;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;


class TicketsController extends Controller
{
    /**
     * Returns a list of all tickets
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $woocommerce = $this->_setupWoocommerce();

        $tickets = $woocommerce->get('products', ['status' => 'publish']);
        $formattedTickets = array();

        foreach ($tickets as $ticket) {
            if ($ticket['meta_data'][0]['value'] === 'Event') {
                $formattedTickets[] = [
                    'id' => $ticket['id'],
                    'name' => $ticket['name'],
                    'price' => $ticket['price'],
                    'in_stock' => $ticket['in_stock'],
                    'city' => $ticket['attributes'][0]['options'][0],
                    'location' => $ticket['meta_data'][6]['value'],
                    'date' => $ticket['meta_data'][1]['value'],
                    'start_time' => $ticket['meta_data'][3]['value'] . ':' . $ticket['meta_data'][4]['value'],
                    'end_time' => $ticket['meta_data'][11]['value'] . ':' . $ticket['meta_data'][12]['value']
                ];
            }
        }

        return response()->json($formattedTickets);
    }

    /**
     * Create an order
     *
     * @param $request Request
     * @return \Illuminate\Http\Response
     */
    public function createOrder(Request $request)
    {
        $woocommerce = $this->_setupWoocommerce();
        $items = $request->all()['items'];
        $user = $request->all()['user'];
        $formattedItems = array();
        $metaData = array();

        foreach ($items as $item) {
            $formattedItems[] = [
                'product_id' => $item['id'],
                'quantity' => $item['quantity']
            ];

            foreach ($item['information'] as $x => $information) {
                $firstNameKey = $item['id'] . '_attendee_' . ($x + 1);
                $lastNameKey = $item['id'] . '_attendeelastname_' . ($x + 1);
                $metaData = array_merge(
                    $metaData,
                    [
                        [
                            'key' => $firstNameKey,
                            'value' => $information['firstName']
                        ],
                        [
                            'key' => $lastNameKey,
                            'value' => $information['lastName']
                        ]
                    ]
                );
            }
        }

        $order = [
            'payment_method' => 'paypal',
            'payment_method_title' => 'PayPal',
            'billing' => [
                'first_name' => $user['firstName'],
                'last_name' => $user['lastName'],
                'address_1' => $user['address'],
                'postcode' => $user['postcode'],
                'city' => $user['city'],
                'email' => $user['email'],
                'phone' => $user['phoneNumber'],
            ],
            'line_items' => $formattedItems,
            'meta_data' => $metaData
        ];

        $orderResponse = $woocommerce->post('orders', $order);

        return response()->json($orderResponse);
    }

    /**
     * Edit an order
     *
     * @param $request Request
     * @return \Illuminate\Http\Response
     */
    public function editOrder(Request $request)
    {
        $woocommerce = $this->_setupWoocommerce();

        $oderId = $request->all()['orderId'];

        $formattedOrder = [
            'set_paid' => true,
            'status' => 'completed',
            'transaction_id' => $request->all()['transactionId']
        ];

        $orderResponse = $woocommerce->put('orders/' . $oderId, $formattedOrder);

        return response()->json($orderResponse);
    }

    /**
     * Create a payment
     *
     * @param $request Request
     * @return \Illuminate\Http\Response
     */
    public function createPayment(Request $request)
    {
        $woocommerce = $this->_setupWoocommerce();
        $paypalContext = $this->_setupPaypal();

        $oderId = $request->all()['orderId'];

        $orderResponse = $woocommerce->get('orders/' . $oderId);
        $items = $orderResponse['line_items'];

        $formattedItems = new ItemList();

        foreach ($items as $item) {
            $formattedItem = new Item();
            $formattedItem->setName($item['name'])
                ->setCurrency($orderResponse['currency'])
                ->setQuantity($item['quantity'])
                ->setPrice($item['price']);
            $formattedItems->addItem($formattedItem);
        }

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $amount = new Amount();
        $amount->setCurrency($orderResponse['currency'])
            ->setTotal($orderResponse['total']);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($formattedItems)
            ->setDescription("Achat billet")
            ->setInvoiceNumber($orderResponse['order_key']);

        $host = env('APP_HOST');
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($host . "/api/v1/tickets/checkout/payment/execute?success=true")
                     ->setCancelUrl($host . "/api/v1/tickets/checkout/payment/execute?success=false");

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->addTransaction($transaction);

        try {
            $payment->create($paypalContext);
        } catch (Exception $e) {
            $handler = new Handler;
            $handler->report($e);
        }

        return response()->json(['paymentID' => $payment->getId()]);
    }

    /**
     * Execute a payment
     *
     * @param $request Request
     * @return \Illuminate\Http\Response
     */
    public function executePayment(Request $request)
    {
        $paypalContext = $this->_setupPaypal();

        $paymentID = $request->all()['paymentID'];
        $payerID = $request->all()['payerID'];

        $payment = Payment::get($paymentID, $paypalContext);

        $execution = new PaymentExecution();
        $execution->setPayerId($payerID);

        try {
            $result = $payment->execute($execution, $paypalContext);
            $paymentResponse = [
                'valid' => true,
                'id' => $result->getId(),
                'state' => $result->getState()
            ];
        } catch (Exception $e) {
            $paymentResponse = [
                'valid' => false,
                'errorMessage' => $e->getMessage(),
                'paymentErrorMessage' => $payment->getFailureReason()
            ];
        }

        return response()->json($paymentResponse);
    }

    protected function _setupWoocommerce()
    {
        $host = env('APP_ADMIN');

        $woocommerce_consumer_key = Settings::where('label', 'woocommerce_consumer_key')->limit(1)->pluck('value')[0];
        $woocommerce_consumer_secret = Settings::where('label', 'woocommerce_consumer_secret')->limit(1)->pluck('value')[0];

        return new Client(
            $host,
            $woocommerce_consumer_key,
            $woocommerce_consumer_secret,
            [
                'wp_api' => true,
                'version' => 'wc/v2',
                'verify_ssl' => env('APP_ENV') != 'development'
            ]
        );
    }

    protected function _setupPaypal()
    {

        $paypal_client_id = Settings::where('label', 'paypal_client_id')->limit(1)->pluck('value')[0];
        $paypal_client_secret = Settings::where('label', 'paypal_client_secret')->limit(1)->pluck('value')[0];

        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                $paypal_client_id,
                $paypal_client_secret
            )
        );

        $apiContext->setConfig(
            array(
                'mode' => 'sandbox',
                'log.LogEnabled' => true,
                'log.FileName' => '../storage/logs/PayPal.log',
                'log.LogLevel' => 'DEBUG', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
                'cache.enabled' => true,
            )
        );

        return $apiContext;
    }
}
