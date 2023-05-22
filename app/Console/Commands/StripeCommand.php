<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Http\Controllers\Api\V1\StripeController;

class StripeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:pay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $str = '{
            "id": "evt_1NAXS5LKIXN48vzh1c9zQZn1",
            "object": "event",
            "api_version": "2022-11-15",
            "created": 1684756979,
            "data": {
              "object": {
                "id": "in_1NAXS1LKIXN48vzhuRvbkKST",
                "object": "invoice",
                "account_country": "GB",
                "account_name": "WILLBE GROUP LTD",
                "account_tax_ids": null,
                "amount_due": 100,
                "amount_paid": 100,
                "amount_remaining": 0,
                "amount_shipping": 0,
                "application": null,
                "application_fee_amount": null,
                "attempt_count": 1,
                "attempted": true,
                "auto_advance": false,
                "automatic_tax": {
                  "enabled": true,
                  "status": "complete"
                },
                "billing_reason": "subscription_create",
                "charge": "ch_3NAXS1LKIXN48vzh05PqqX92",
                "collection_method": "charge_automatically",
                "created": 1684756977,
                "currency": "gbp",
                "custom_fields": null,
                "customer": "cus_NwQIxNwhsb03V6",
                "customer_address": {
                  "city": null,
                  "country": "PL",
                  "line1": null,
                  "line2": null,
                  "postal_code": null,
                  "state": null
                },
                "customer_email": "1.shod@will.com",
                "customer_name": "Asd Zxc",
                "customer_phone": null,
                "customer_shipping": null,
                "customer_tax_exempt": "none",
                "customer_tax_ids": [
                ],
                "default_payment_method": null,
                "default_source": null,
                "default_tax_rates": [
                ],
                "description": null,
                "discount": null,
                "discounts": [
                ],
                "due_date": null,
                "ending_balance": 0,
                "footer": null,
                "from_invoice": null,
                "hosted_invoice_url": "https://invoice.stripe.com/i/acct_1McSdZLKIXN48vzh/test_YWNjdF8xTWNTZFpMS0lYTjQ4dnpoLF9Od1FJVElNMXNhREh0c0dyMEZUTzRrakdIVXJqbHg0LDc1Mjk3Nzgx0200jqdIOBK9?s=ap",
                "invoice_pdf": "https://pay.stripe.com/invoice/acct_1McSdZLKIXN48vzh/test_YWNjdF8xTWNTZFpMS0lYTjQ4dnpoLF9Od1FJVElNMXNhREh0c0dyMEZUTzRrakdIVXJqbHg0LDc1Mjk3Nzgx0200jqdIOBK9/pdf?s=ap",
                "last_finalization_error": null,
                "latest_revision": null,
                "lines": {
                  "object": "list",
                  "data": [
                    {
                      "id": "il_1NAXS1LKIXN48vzhcbxSkzMn",
                      "object": "line_item",
                      "amount": 100,
                      "amount_excluding_tax": 100,
                      "currency": "gbp",
                      "description": "1 × TestPlan (at £1.00 / month)",
                      "discount_amounts": [
                      ],
                      "discountable": true,
                      "discounts": [
                      ],
                      "livemode": false,
                      "metadata": {
                      },
                      "period": {
                        "end": 1687435376,
                        "start": 1684756976
                      },
                      "plan": {
                        "id": "price_1N9DLLLKIXN48vzhGwKhhrtT",
                        "object": "plan",
                        "active": true,
                        "aggregate_usage": null,
                        "amount": 100,
                        "amount_decimal": "100",
                        "billing_scheme": "per_unit",
                        "created": 1684441355,
                        "currency": "gbp",
                        "interval": "month",
                        "interval_count": 1,
                        "livemode": false,
                        "metadata": {
                        },
                        "nickname": null,
                        "product": "prod_Nv3S5CVKDFOIZP",
                        "tiers_mode": null,
                        "transform_usage": null,
                        "trial_period_days": null,
                        "usage_type": "licensed"
                      },
                      "price": {
                        "id": "price_1N9DLLLKIXN48vzhGwKhhrtT",
                        "object": "price",
                        "active": true,
                        "billing_scheme": "per_unit",
                        "created": 1684441355,
                        "currency": "gbp",
                        "custom_unit_amount": null,
                        "livemode": false,
                        "lookup_key": null,
                        "metadata": {
                        },
                        "nickname": null,
                        "product": "prod_Nv3S5CVKDFOIZP",
                        "recurring": {
                          "aggregate_usage": null,
                          "interval": "month",
                          "interval_count": 1,
                          "trial_period_days": null,
                          "usage_type": "licensed"
                        },
                        "tax_behavior": "inclusive",
                        "tiers_mode": null,
                        "transform_quantity": null,
                        "type": "recurring",
                        "unit_amount": 100,
                        "unit_amount_decimal": "100"
                      },
                      "proration": false,
                      "proration_details": {
                        "credited_items": null
                      },
                      "quantity": 1,
                      "subscription": "sub_1NAXS0LKIXN48vzhx9Jy76Gb",
                      "subscription_item": "si_NwQIm0n5xtlDLo",
                      "tax_amounts": [
                        {
                          "amount": 0,
                          "inclusive": true,
                          "tax_rate": "txr_1N9R40LKIXN48vzhKjhoezRm",
                          "taxability_reason": "not_collecting",
                          "taxable_amount": 0
                        }
                      ],
                      "tax_rates": [
                      ],
                      "type": "subscription",
                      "unit_amount_excluding_tax": "100"
                    }
                  ],
                  "has_more": false,
                  "total_count": 1,
                  "url": "/v1/invoices/in_1NAXS1LKIXN48vzhuRvbkKST/lines"
                },
                "livemode": false,
                "metadata": {
                },
                "next_payment_attempt": null,
                "number": "5147ACF9-0012",
                "on_behalf_of": null,
                "paid": true,
                "paid_out_of_band": false,
                "payment_intent": "pi_3NAXS1LKIXN48vzh0aGPCqtK",
                "payment_settings": {
                  "default_mandate": null,
                  "payment_method_options": null,
                  "payment_method_types": null
                },
                "period_end": 1684756976,
                "period_start": 1684756976,
                "post_payment_credit_notes_amount": 0,
                "pre_payment_credit_notes_amount": 0,
                "quote": null,
                "receipt_number": null,
                "rendering_options": null,
                "shipping_cost": null,
                "shipping_details": null,
                "starting_balance": 0,
                "statement_descriptor": null,
                "status": "paid",
                "status_transitions": {
                  "finalized_at": 1684756977,
                  "marked_uncollectible_at": null,
                  "paid_at": 1684756979,
                  "voided_at": null
                },
                "subscription": "sub_1NAXS0LKIXN48vzhx9Jy76Gb",
                "subtotal": 100,
                "subtotal_excluding_tax": 100,
                "tax": 0,
                "test_clock": null,
                "total": 100,
                "total_discount_amounts": [
                ],
                "total_excluding_tax": 100,
                "total_tax_amounts": [
                  {
                    "amount": 0,
                    "inclusive": true,
                    "tax_rate": "txr_1N9R40LKIXN48vzhKjhoezRm",
                    "taxability_reason": "not_collecting",
                    "taxable_amount": 0
                  }
                ],
                "transfer_data": null,
                "webhooks_delivered_at": 1684756977
              }
            },
            "livemode": false,
            "pending_webhooks": 2,
            "request": {
              "id": "req_dBCEdVHTAasncE",
              "idempotency_key": "eda84cb5-19a2-471f-889e-2cd1c0c72a90"
            },
            "type": "invoice.payment_succeeded"
          }';


        // Преобразование строки в массив
        $arr = json_decode($str, true);

        $stripe = new StripeController();
        $res = $stripe->handleInvoicePaymentSucceeded($arr);
        dd($res);
        //$stripeCustomer = $user->createOrGetStripeCustomer();

        //dd($stripeCustomer);
        return Command::SUCCESS;
    }
}
