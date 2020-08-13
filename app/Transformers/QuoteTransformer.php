<?php
/**
 * Invoice Ninja (https://invoiceninja.com)
 *
 * @link https://github.com/invoiceninja/invoiceninja source repository
 *
 * @copyright Copyright (c) 2020. Invoice Ninja LLC (https://invoiceninja.com)
 *
 * @license https://opensource.org/licenses/AAL
 */

namespace App\Transformers;

use App\Models\Backup;
use App\Models\Document;
use App\Models\Quote;
use App\Models\QuoteInvitation;
use App\Transformers\DocumentTransformer;
use App\Transformers\InvoiceHistoryTransformer;
use App\Transformers\QuoteInvitationTransformer;
use App\Utils\Traits\MakesHash;

class QuoteTransformer extends EntityTransformer
{
    use MakesHash;

    protected $defaultIncludes = [
            'invitations',
            'documents',
            'history'
    ];

    protected $availableIncludes = [
        'invitations',
        'documents',
        'history'
       //    'payments',
    //    'client',
    ];

    public function includeHistory(Quote $quote)
    {
        $transformer = new InvoiceHistoryTransformer($this->serializer);

        return $this->includeCollection($quote->history, $transformer, Backup::class);
    }
    
    public function includeInvitations(Quote $quote)
    {
        $transformer = new QuoteInvitationTransformer($this->serializer);

        return $this->includeCollection($quote->invitations, $transformer, QuoteInvitation::class);
    }
    /*
        public function includePayments(quote $quote)
        {
            $transformer = new PaymentTransformer($this->account, $this->serializer, $quote);

            return $this->includeCollection($quote->payments, $transformer, ENTITY_PAYMENT);
        }

        public function includeClient(quote $quote)
        {
            $transformer = new ClientTransformer($this->account, $this->serializer);

            return $this->includeItem($quote->client, $transformer, ENTITY_CLIENT);
        }

        public function includeExpenses(quote $quote)
        {
            $transformer = new ExpenseTransformer($this->account, $this->serializer);

            return $this->includeCollection($quote->expenses, $transformer, ENTITY_EXPENSE);
        }
    */

    public function includeDocuments(Quote $quote)
    {
        $transformer = new DocumentTransformer($this->serializer);
        return $this->includeCollection($quote->documents, $transformer, Document::class);
    }
    
    public function transform(Quote $quote)
    {
        return [
            'id' => $this->encodePrimaryKey($quote->id),
            'user_id' => $this->encodePrimaryKey($quote->user_id),
            'assigned_user_id' => $this->encodePrimaryKey($quote->assigned_user_id),
            'amount' => (float) $quote->amount,
            'balance' => (float) $quote->balance,
            'client_id' => (string) $this->encodePrimaryKey($quote->client_id),
            'status_id' => (string)$quote->status_id,
            'design_id' => (string) $this->encodePrimaryKey($quote->design_id),
            'invoice_id' => (string)$this->encodePrimaryKey($quote->invoice_id),
            'updated_at' => (int)$quote->updated_at,
            'archived_at' => (int)$quote->deleted_at,
            'created_at' => (int)$quote->created_at,
            'number' => $quote->number ?: '',
            'discount' => (float) $quote->discount,
            'po_number' => $quote->po_number ?: '',
            'date' => $quote->date ?: '',
            'last_sent_date' => $quote->last_sent_date ?: '',
            'next_send_date' => $quote->date ?: '',
            'due_date' => $quote->due_date ?: '',
            'terms' => $quote->terms ?: '',
            'public_notes' => $quote->public_notes ?: '',
            'private_notes' => $quote->private_notes ?: '',
            'is_deleted' => (bool) $quote->is_deleted,
            'uses_inclusive_taxes' => (bool) $quote->uses_inclusive_taxes,
            'tax_name1' => $quote->tax_name1 ? $quote->tax_name1 : '',
            'tax_rate1' => (float) $quote->tax_rate1,
            'tax_name2' => $quote->tax_name2 ? $quote->tax_name2 : '',
            'tax_rate2' => (float) $quote->tax_rate2,
            'tax_name3' => $quote->tax_name3 ? $quote->tax_name3 : '',
            'tax_rate3' => (float) $quote->tax_rate3,
            'total_taxes' => (float) $quote->total_taxes,
            'is_amount_discount' => (bool) ($quote->is_amount_discount ?: false),
            'footer' => $quote->footer ?: '',
            'partial' => (float) ($quote->partial ?: 0.0),
            'partial_due_date' => $quote->partial_due_date ?: '',
            'custom_value1' => (string) $quote->custom_value1 ?: '',
            'custom_value2' => (string) $quote->custom_value2 ?: '',
            'custom_value3' => (string) $quote->custom_value3 ?: '',
            'custom_value4' => (string) $quote->custom_value4 ?: '',
            'has_tasks' => (bool) $quote->has_tasks,
            'has_expenses' => (bool) $quote->has_expenses,
            'custom_surcharge1' => (float)$quote->custom_surcharge1,
            'custom_surcharge2' => (float)$quote->custom_surcharge2,
            'custom_surcharge3' => (float)$quote->custom_surcharge3,
            'custom_surcharge4' => (float)$quote->custom_surcharge4,
            'custom_surcharge_taxes' => (bool) $quote->custom_surcharge_taxes,
            'line_items' => $quote->line_items ?: (array)[],
            'entity_type' => 'quote',

        ];
    }
}
