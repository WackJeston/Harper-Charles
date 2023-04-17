<?php
// webhook.php
//
// Use this sample code to handle webhook events in your integration.
//
// 1) Paste this code into a new file (webhook.php)
//
// 2) Install dependencies
//   composer require stripe/stripe-php
//
// 3) Run the server on http://localhost:4242
//   php -S localhost:4242

require 'vendor/autoload.php';

// The library needs to be configured with your account's secret key.
// Ensure the key is kept out of any version control system you might be using.
$stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

// This is your Stripe CLI webhook secret for testing your endpoint locally.
$endpoint_secret = 'whsec_d87c38f79b232056e7c3ea7fa906c4d5fd378592368917e29b0b8118892a3c17';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
  $event = \Stripe\Webhook::constructEvent(
    $payload, $sig_header, $endpoint_secret
  );
} catch(\UnexpectedValueException $e) {
  // Invalid payload
  http_response_code(400);
  exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
  // Invalid signature
  http_response_code(400);
  exit();
}

// Handle the event
switch ($event->type) {
  case 'account.updated':
    $account = $event->data->object;
  case 'account.external_account.created':
    $externalAccount = $event->data->object;
  case 'account.external_account.deleted':
    $externalAccount = $event->data->object;
  case 'account.external_account.updated':
    $externalAccount = $event->data->object;
  case 'balance.available':
    $balance = $event->data->object;
  case 'billing_portal.configuration.created':
    $configuration = $event->data->object;
  case 'billing_portal.configuration.updated':
    $configuration = $event->data->object;
  case 'billing_portal.session.created':
    $session = $event->data->object;
  case 'capability.updated':
    $capability = $event->data->object;
  case 'cash_balance.funds_available':
    $cashBalance = $event->data->object;
  case 'charge.captured':
    $charge = $event->data->object;
  case 'charge.expired':
    $charge = $event->data->object;
  case 'charge.failed':
    $charge = $event->data->object;
  case 'charge.pending':
    $charge = $event->data->object;
  case 'charge.refunded':
    $charge = $event->data->object;
  case 'charge.succeeded':
    $charge = $event->data->object;
  case 'charge.updated':
    $charge = $event->data->object;
  case 'charge.dispute.closed':
    $dispute = $event->data->object;
  case 'charge.dispute.created':
    $dispute = $event->data->object;
  case 'charge.dispute.funds_reinstated':
    $dispute = $event->data->object;
  case 'charge.dispute.funds_withdrawn':
    $dispute = $event->data->object;
  case 'charge.dispute.updated':
    $dispute = $event->data->object;
  case 'charge.refund.updated':
    $refund = $event->data->object;
  case 'checkout.session.async_payment_failed':
    $session = $event->data->object;
  case 'checkout.session.async_payment_succeeded':
    $session = $event->data->object;
  case 'checkout.session.completed':
    $session = $event->data->object;
  case 'checkout.session.expired':
    $session = $event->data->object;
  case 'coupon.created':
    $coupon = $event->data->object;
  case 'coupon.deleted':
    $coupon = $event->data->object;
  case 'coupon.updated':
    $coupon = $event->data->object;
  case 'credit_note.created':
    $creditNote = $event->data->object;
  case 'credit_note.updated':
    $creditNote = $event->data->object;
  case 'credit_note.voided':
    $creditNote = $event->data->object;
  case 'customer.created':
    $customer = $event->data->object;
  case 'customer.deleted':
    $customer = $event->data->object;
  case 'customer.updated':
    $customer = $event->data->object;
  case 'customer.discount.created':
    $discount = $event->data->object;
  case 'customer.discount.deleted':
    $discount = $event->data->object;
  case 'customer.discount.updated':
    $discount = $event->data->object;
  case 'customer.source.created':
    $source = $event->data->object;
  case 'customer.source.deleted':
    $source = $event->data->object;
  case 'customer.source.expiring':
    $source = $event->data->object;
  case 'customer.source.updated':
    $source = $event->data->object;
  case 'customer.subscription.created':
    $subscription = $event->data->object;
  case 'customer.subscription.deleted':
    $subscription = $event->data->object;
  case 'customer.subscription.paused':
    $subscription = $event->data->object;
  case 'customer.subscription.pending_update_applied':
    $subscription = $event->data->object;
  case 'customer.subscription.pending_update_expired':
    $subscription = $event->data->object;
  case 'customer.subscription.resumed':
    $subscription = $event->data->object;
  case 'customer.subscription.trial_will_end':
    $subscription = $event->data->object;
  case 'customer.subscription.updated':
    $subscription = $event->data->object;
  case 'customer.tax_id.created':
    $taxId = $event->data->object;
  case 'customer.tax_id.deleted':
    $taxId = $event->data->object;
  case 'customer.tax_id.updated':
    $taxId = $event->data->object;
  case 'customer_cash_balance_transaction.created':
    $customerCashBalanceTransaction = $event->data->object;
  case 'file.created':
    $file = $event->data->object;
  case 'financial_connections.account.created':
    $account = $event->data->object;
  case 'financial_connections.account.deactivated':
    $account = $event->data->object;
  case 'financial_connections.account.disconnected':
    $account = $event->data->object;
  case 'financial_connections.account.reactivated':
    $account = $event->data->object;
  case 'financial_connections.account.refreshed_balance':
    $account = $event->data->object;
  case 'identity.verification_session.canceled':
    $verificationSession = $event->data->object;
  case 'identity.verification_session.created':
    $verificationSession = $event->data->object;
  case 'identity.verification_session.processing':
    $verificationSession = $event->data->object;
  case 'identity.verification_session.requires_input':
    $verificationSession = $event->data->object;
  case 'identity.verification_session.verified':
    $verificationSession = $event->data->object;
  case 'invoice.created':
    $invoice = $event->data->object;
  case 'invoice.deleted':
    $invoice = $event->data->object;
  case 'invoice.finalization_failed':
    $invoice = $event->data->object;
  case 'invoice.finalized':
    $invoice = $event->data->object;
  case 'invoice.marked_uncollectible':
    $invoice = $event->data->object;
  case 'invoice.paid':
    $invoice = $event->data->object;
  case 'invoice.payment_action_required':
    $invoice = $event->data->object;
  case 'invoice.payment_failed':
    $invoice = $event->data->object;
  case 'invoice.payment_succeeded':
    $invoice = $event->data->object;
  case 'invoice.sent':
    $invoice = $event->data->object;
  case 'invoice.upcoming':
    $invoice = $event->data->object;
  case 'invoice.updated':
    $invoice = $event->data->object;
  case 'invoice.voided':
    $invoice = $event->data->object;
  case 'invoiceitem.created':
    $invoiceitem = $event->data->object;
  case 'invoiceitem.deleted':
    $invoiceitem = $event->data->object;
  case 'invoiceitem.updated':
    $invoiceitem = $event->data->object;
  case 'issuing_authorization.created':
    $issuingAuthorization = $event->data->object;
  case 'issuing_authorization.updated':
    $issuingAuthorization = $event->data->object;
  case 'issuing_card.created':
    $issuingCard = $event->data->object;
  case 'issuing_card.updated':
    $issuingCard = $event->data->object;
  case 'issuing_cardholder.created':
    $issuingCardholder = $event->data->object;
  case 'issuing_cardholder.updated':
    $issuingCardholder = $event->data->object;
  case 'issuing_dispute.closed':
    $issuingDispute = $event->data->object;
  case 'issuing_dispute.created':
    $issuingDispute = $event->data->object;
  case 'issuing_dispute.funds_reinstated':
    $issuingDispute = $event->data->object;
  case 'issuing_dispute.submitted':
    $issuingDispute = $event->data->object;
  case 'issuing_dispute.updated':
    $issuingDispute = $event->data->object;
  case 'issuing_transaction.created':
    $issuingTransaction = $event->data->object;
  case 'issuing_transaction.updated':
    $issuingTransaction = $event->data->object;
  case 'mandate.updated':
    $mandate = $event->data->object;
  case 'order.created':
    $order = $event->data->object;
  case 'payment_intent.amount_capturable_updated':
    $paymentIntent = $event->data->object;
  case 'payment_intent.canceled':
    $paymentIntent = $event->data->object;
  case 'payment_intent.created':
    $paymentIntent = $event->data->object;
  case 'payment_intent.partially_funded':
    $paymentIntent = $event->data->object;
  case 'payment_intent.payment_failed':
    $paymentIntent = $event->data->object;
  case 'payment_intent.processing':
    $paymentIntent = $event->data->object;
  case 'payment_intent.requires_action':
    $paymentIntent = $event->data->object;
  case 'payment_intent.succeeded':
    $paymentIntent = $event->data->object;
  case 'payment_link.created':
    $paymentLink = $event->data->object;
  case 'payment_link.updated':
    $paymentLink = $event->data->object;
  case 'payment_method.attached':
    $paymentMethod = $event->data->object;
  case 'payment_method.automatically_updated':
    $paymentMethod = $event->data->object;
  case 'payment_method.detached':
    $paymentMethod = $event->data->object;
  case 'payment_method.updated':
    $paymentMethod = $event->data->object;
  case 'payout.canceled':
    $payout = $event->data->object;
  case 'payout.created':
    $payout = $event->data->object;
  case 'payout.failed':
    $payout = $event->data->object;
  case 'payout.paid':
    $payout = $event->data->object;
  case 'payout.reconciliation_completed':
    $payout = $event->data->object;
  case 'payout.updated':
    $payout = $event->data->object;
  case 'person.created':
    $person = $event->data->object;
  case 'person.deleted':
    $person = $event->data->object;
  case 'person.updated':
    $person = $event->data->object;
  case 'plan.created':
    $plan = $event->data->object;
  case 'plan.deleted':
    $plan = $event->data->object;
  case 'plan.updated':
    $plan = $event->data->object;
  case 'price.created':
    $price = $event->data->object;
  case 'price.deleted':
    $price = $event->data->object;
  case 'price.updated':
    $price = $event->data->object;
  case 'product.created':
    $product = $event->data->object;
  case 'product.deleted':
    $product = $event->data->object;
  case 'product.updated':
    $product = $event->data->object;
  case 'promotion_code.created':
    $promotionCode = $event->data->object;
  case 'promotion_code.updated':
    $promotionCode = $event->data->object;
  case 'quote.accepted':
    $quote = $event->data->object;
  case 'quote.canceled':
    $quote = $event->data->object;
  case 'quote.created':
    $quote = $event->data->object;
  case 'quote.finalized':
    $quote = $event->data->object;
  case 'radar.early_fraud_warning.created':
    $earlyFraudWarning = $event->data->object;
  case 'radar.early_fraud_warning.updated':
    $earlyFraudWarning = $event->data->object;
  case 'recipient.created':
    $recipient = $event->data->object;
  case 'recipient.deleted':
    $recipient = $event->data->object;
  case 'recipient.updated':
    $recipient = $event->data->object;
  case 'refund.created':
    $refund = $event->data->object;
  case 'refund.updated':
    $refund = $event->data->object;
  case 'reporting.report_run.failed':
    $reportRun = $event->data->object;
  case 'reporting.report_run.succeeded':
    $reportRun = $event->data->object;
  case 'review.closed':
    $review = $event->data->object;
  case 'review.opened':
    $review = $event->data->object;
  case 'setup_intent.canceled':
    $setupIntent = $event->data->object;
  case 'setup_intent.created':
    $setupIntent = $event->data->object;
  case 'setup_intent.requires_action':
    $setupIntent = $event->data->object;
  case 'setup_intent.setup_failed':
    $setupIntent = $event->data->object;
  case 'setup_intent.succeeded':
    $setupIntent = $event->data->object;
  case 'sigma.scheduled_query_run.created':
    $scheduledQueryRun = $event->data->object;
  case 'sku.created':
    $sku = $event->data->object;
  case 'sku.deleted':
    $sku = $event->data->object;
  case 'sku.updated':
    $sku = $event->data->object;
  case 'source.canceled':
    $source = $event->data->object;
  case 'source.chargeable':
    $source = $event->data->object;
  case 'source.failed':
    $source = $event->data->object;
  case 'source.mandate_notification':
    $source = $event->data->object;
  case 'source.refund_attributes_required':
    $source = $event->data->object;
  case 'source.transaction.created':
    $transaction = $event->data->object;
  case 'source.transaction.updated':
    $transaction = $event->data->object;
  case 'subscription_schedule.aborted':
    $subscriptionSchedule = $event->data->object;
  case 'subscription_schedule.canceled':
    $subscriptionSchedule = $event->data->object;
  case 'subscription_schedule.completed':
    $subscriptionSchedule = $event->data->object;
  case 'subscription_schedule.created':
    $subscriptionSchedule = $event->data->object;
  case 'subscription_schedule.expiring':
    $subscriptionSchedule = $event->data->object;
  case 'subscription_schedule.released':
    $subscriptionSchedule = $event->data->object;
  case 'subscription_schedule.updated':
    $subscriptionSchedule = $event->data->object;
  case 'tax_rate.created':
    $taxRate = $event->data->object;
  case 'tax_rate.updated':
    $taxRate = $event->data->object;
  case 'terminal.reader.action_failed':
    $reader = $event->data->object;
  case 'terminal.reader.action_succeeded':
    $reader = $event->data->object;
  case 'test_helpers.test_clock.advancing':
    $testClock = $event->data->object;
  case 'test_helpers.test_clock.created':
    $testClock = $event->data->object;
  case 'test_helpers.test_clock.deleted':
    $testClock = $event->data->object;
  case 'test_helpers.test_clock.internal_failure':
    $testClock = $event->data->object;
  case 'test_helpers.test_clock.ready':
    $testClock = $event->data->object;
  case 'topup.canceled':
    $topup = $event->data->object;
  case 'topup.created':
    $topup = $event->data->object;
  case 'topup.failed':
    $topup = $event->data->object;
  case 'topup.reversed':
    $topup = $event->data->object;
  case 'topup.succeeded':
    $topup = $event->data->object;
  case 'transfer.created':
    $transfer = $event->data->object;
  case 'transfer.reversed':
    $transfer = $event->data->object;
  case 'transfer.updated':
    $transfer = $event->data->object;
  // ... handle other event types
  default:
    echo 'Received unknown event type ' . $event->type;
}

http_response_code(200);