<?php

use yii\db\Migration;

/**
 * Class m210223_165901_customer_add_billing
 */
class m210223_165901_customer_add_billing extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $baseTable = 'customer';
        $this->addColumn("{{%$baseTable}}", $column = 'other_bill_to', $this->integer());
        $this->addForeignKey(
            "{{%{$baseTable}_{$column}_fk}}",
            "{{%$baseTable}}",
            $column,
            "{{%$baseTable}}",
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addColumn("{{%$baseTable}}", 'invoice_style', $this->string());
        $this->addColumn("{{%$baseTable}}", 'invoicing_method', $this->string());
        $this->addColumn("{{%$baseTable}}", 'invoicing_email', $this->string());
        $this->addColumn("{{%$baseTable}}", 'send_invoice_copies_to_invoicing_contacts', $this->boolean()->defaultValue(false));
        $this->addColumn("{{%$baseTable}}", 'send_invoices_as_pdf_attachments', $this->boolean()->defaultValue(false));
        $this->addColumn("{{%$baseTable}}", 'factor_invoices_for_this_customer', $this->boolean()->defaultValue(false));
        $this->addColumn("{{%$baseTable}}", 'hide_load_mileages_on_invoices', $this->boolean()->defaultValue(false));
        for ($i = 1; $i <= 5; $i++) {
            $this->addColumn("{{%$baseTable}}", "line_$i", $this->string());
        }
        $this->addColumn("{{%$baseTable}}", 'pre_billing_allowed_no_backup_required', $this->boolean()->defaultValue(false));
        $this->addColumn("{{%$baseTable}}", 'send_all_load_scans_with_each_invoice', $this->boolean()->defaultValue(false));
        $this->addColumn("{{%$baseTable}}", 'original_documents_required', $this->boolean()->defaultValue(false));
        $this->addColumn("{{%$baseTable}}", 'bill_of_lading', $this->boolean()->defaultValue(false));
        $this->addColumn("{{%$baseTable}}", 'delivery_receipt', $this->boolean()->defaultValue(false));
        $this->addColumn("{{%$baseTable}}", 'purchase_order', $this->boolean()->defaultValue(false));
        $this->addColumn("{{%$baseTable}}", 'transport_agreement', $this->boolean()->defaultValue(false));
        $this->addColumn("{{%$baseTable}}", 'interchanges', $this->boolean()->defaultValue(false));
        $this->addColumn("{{%$baseTable}}", 'packing_slip', $this->boolean()->defaultValue(false));
        $this->addColumn("{{%$baseTable}}", 'scale_tickets', $this->boolean()->defaultValue(false));
        $this->addColumn("{{%$baseTable}}", 'load_receipts', $this->boolean()->defaultValue(false));
        $this->addColumn("{{%$baseTable}}", 'work_orders', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $baseTable = 'customer';
        $column = 'other_bill_to';
        $this->dropForeignKey(
            "{{%{$baseTable}_{$column}_fk}}",
            "{{%$baseTable}}"
        );
        $this->dropColumn("{{%$baseTable}}", $column);
        $this->dropColumn("{{%$baseTable}}", 'invoice_style');
        $this->dropColumn("{{%$baseTable}}", 'invoicing_method');
        $this->dropColumn("{{%$baseTable}}", 'invoicing_email');
        $this->dropColumn("{{%$baseTable}}", 'send_invoice_copies_to_invoicing_contacts');
        $this->dropColumn("{{%$baseTable}}", 'send_invoices_as_pdf_attachments');
        $this->dropColumn("{{%$baseTable}}", 'factor_invoices_for_this_customer');
        $this->dropColumn("{{%$baseTable}}", 'hide_load_mileages_on_invoices');
        for ($i = 1; $i <= 5; $i++) {
            $this->dropColumn("{{%$baseTable}}", "line_$i");
        }
        $this->dropColumn("{{%$baseTable}}", 'pre_billing_allowed_no_backup_required');
        $this->dropColumn("{{%$baseTable}}", 'send_all_load_scans_with_each_invoice');
        $this->dropColumn("{{%$baseTable}}", 'original_documents_required');
        $this->dropColumn("{{%$baseTable}}", 'bill_of_lading');
        $this->dropColumn("{{%$baseTable}}", 'delivery_receipt');
        $this->dropColumn("{{%$baseTable}}", 'purchase_order');
        $this->dropColumn("{{%$baseTable}}", 'transport_agreement');
        $this->dropColumn("{{%$baseTable}}", 'interchanges');
        $this->dropColumn("{{%$baseTable}}", 'packing_slip');
        $this->dropColumn("{{%$baseTable}}", 'scale_tickets');
        $this->dropColumn("{{%$baseTable}}", 'load_receipts');
        $this->dropColumn("{{%$baseTable}}", 'work_orders');
    }
}
