<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Automattic\WooCommerce\Client;


class TicketsController extends Controller
{
    protected function _setupWoocommerce()
    {
        return new Client(
            'https://o2n_nginx',
            env('WOOCOMMERCE_CONSUMER_KEY'),
            env('WOOCOMMERCE_CONSUMER_SECRET'),
            [
                'wp_api' => true,
                'version' => 'wc/v2',
                'verify_ssl' => false
            ]
        );
    }
    /**
     * Returns a list of all tickets
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $woocommerce = $this->_setupWoocommerce();

        $tickets = $woocommerce->get('products', ['type' => 'ticket-event', 'status' => 'publish']);
        $formattedTracks = array();

        foreach ($tickets as $ticket) {
            $attributes = array();
            foreach ($ticket['attributes'] as $attribute) {
                $attributes[] = [
                    'label' => $attribute['name'],
                    'value' => $attribute['options'][0]
                ];
            }

            $formattedTracks[] = [
                'id' =>  $ticket['id'],
                'name' => $ticket['name'],
                'price' => $ticket['price'],
                'in_stock' => $ticket['in_stock'],
                'attributes' => $attributes,
                'start_date' => $ticket['meta_data']['1']['value'],
                'start_time' => $ticket['meta_data']['2']['value'],
                'end_date' => $ticket['meta_data']['3']['value'],
                'end_time' => $ticket['meta_data']['4']['value'],
            ];
        }

        return response()->json($formattedTracks);
    }
}
