<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace common\models\base;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "customer".
 *
 * @property integer $id
 * @property string $notes
 * @property string $name
 * @property string $address_1
 * @property string $address_2
 * @property string $city
 * @property integer $state_id
 * @property string $zip
 * @property string $main_phone
 * @property string $main_800
 * @property string $main_fax
 * @property string $disp_contact
 * @property string $ap_contact
 * @property string $other_contact
 * @property string $account_no
 * @property string $federal_id
 * @property string $mc_id
 * @property string $scac
 * @property string $marked_as_down
 * @property integer $other_bill_to
 * @property string $invoice_style
 * @property string $invoicing_method
 * @property string $invoicing_email
 * @property boolean $send_invoice_copies_to_invoicing_contacts
 * @property boolean $send_invoices_as_pdf_attachments
 * @property boolean $factor_invoices_for_this_customer
 * @property boolean $hide_load_mileages_on_invoices
 * @property string $line_1
 * @property string $line_2
 * @property string $line_3
 * @property string $line_4
 * @property string $line_5
 * @property boolean $pre_billing_allowed_no_backup_required
 * @property boolean $send_all_load_scans_with_each_invoice
 * @property boolean $original_documents_required
 * @property boolean $bill_of_lading
 * @property boolean $delivery_receipt
 * @property boolean $purchase_order
 * @property boolean $transport_agreement
 * @property boolean $interchanges
 * @property boolean $packing_slip
 * @property boolean $scale_tickets
 * @property boolean $load_receipts
 * @property boolean $work_orders
 * @property string $rate_source
 * @property integer $acc_matrix_id
 * @property string $discount
 * @property string $system
 * @property string $calc_by
 * @property string $rating
 * @property string $agency
 * @property integer $approved_by
 * @property integer $terms
 * @property string $limit
 * @property boolean $credit_hold
 * @property boolean $bond
 * @property string $expires
 * @property string $number
 * @property string $bank
 * @property integer $salesman_1
 * @property string $pay_type_1
 * @property string $rate_1
 * @property integer $salesman_2
 * @property string $pay_type_2
 * @property string $rate_2
 * @property boolean $mail_list
 * @property boolean $send_210
 * @property boolean $send_214
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $created_by
 * @property string $created_at
 *
 * @property \common\models\AccessorialMatrix $accMatrix
 * @property \common\models\Customer $otherBillTo
 * @property \common\models\Customer[] $customers
 * @property \common\models\PaymentTermCode $terms0
 * @property \common\models\State $state
 * @property \common\models\CustomerContact[] $customerContacts
 * @property \common\models\CustomerContactNote[] $customerContactNotes
 * @property \common\models\Document[] $documents
 * @property \common\models\Load[] $loads
 * @property \common\models\Location[] $locations
 * @property \common\models\Truck[] $trucks
 * @property string $aliasModel
 */
abstract class Customer extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
            ],
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['notes', 'bank'], 'string'],
            [['name'], 'required'],
            [['state_id', 'other_bill_to', 'acc_matrix_id', 'approved_by', 'terms', 'salesman_1', 'salesman_2'], 'default', 'value' => null],
            [['state_id', 'other_bill_to', 'acc_matrix_id', 'approved_by', 'terms', 'salesman_1', 'salesman_2'], 'integer'],
            [['marked_as_down', 'expires'], 'safe'],
            [['send_invoice_copies_to_invoicing_contacts', 'send_invoices_as_pdf_attachments', 'factor_invoices_for_this_customer', 'hide_load_mileages_on_invoices', 'pre_billing_allowed_no_backup_required', 'send_all_load_scans_with_each_invoice', 'original_documents_required', 'bill_of_lading', 'delivery_receipt', 'purchase_order', 'transport_agreement', 'interchanges', 'packing_slip', 'scale_tickets', 'load_receipts', 'work_orders', 'credit_hold', 'bond', 'mail_list', 'send_210', 'send_214'], 'boolean'],
            [['discount', 'limit', 'rate_1', 'rate_2'], 'number'],
            [['name', 'address_1', 'address_2', 'city', 'main_phone', 'main_800', 'main_fax', 'disp_contact', 'ap_contact', 'other_contact', 'account_no', 'federal_id', 'mc_id', 'scac', 'invoice_style', 'invoicing_method', 'invoicing_email', 'line_1', 'line_2', 'line_3', 'line_4', 'line_5', 'rate_source', 'system', 'calc_by', 'rating', 'agency', 'number', 'pay_type_1', 'pay_type_2'], 'string', 'max' => 255],
            [['zip'], 'string', 'max' => 10],
            [['acc_matrix_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\AccessorialMatrix::className(), 'targetAttribute' => ['acc_matrix_id' => 'id']],
            [['other_bill_to'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Customer::className(), 'targetAttribute' => ['other_bill_to' => 'id']],
            [['terms'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\PaymentTermCode::className(), 'targetAttribute' => ['terms' => 'id']],
            [['state_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\State::className(), 'targetAttribute' => ['state_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'notes' => Yii::t('app', 'Notes'),
            'name' => Yii::t('app', 'Name'),
            'address_1' => Yii::t('app', 'Address 1'),
            'address_2' => Yii::t('app', 'Address 2'),
            'city' => Yii::t('app', 'City'),
            'state_id' => Yii::t('app', 'State ID'),
            'zip' => Yii::t('app', 'Zip'),
            'main_phone' => Yii::t('app', 'Main Phone'),
            'main_800' => Yii::t('app', 'Main 800'),
            'main_fax' => Yii::t('app', 'Main Fax'),
            'disp_contact' => Yii::t('app', 'Disp Contact'),
            'ap_contact' => Yii::t('app', 'Ap Contact'),
            'other_contact' => Yii::t('app', 'Other Contact'),
            'account_no' => Yii::t('app', 'Account No'),
            'federal_id' => Yii::t('app', 'Federal ID'),
            'mc_id' => Yii::t('app', 'Mc ID'),
            'scac' => Yii::t('app', 'Scac'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'marked_as_down' => Yii::t('app', 'Marked As Down'),
            'other_bill_to' => Yii::t('app', 'Other Bill To'),
            'invoice_style' => Yii::t('app', 'Invoice Style'),
            'invoicing_method' => Yii::t('app', 'Invoicing Method'),
            'invoicing_email' => Yii::t('app', 'Invoicing Email'),
            'send_invoice_copies_to_invoicing_contacts' => Yii::t('app', 'Send Invoice Copies To Invoicing Contacts'),
            'send_invoices_as_pdf_attachments' => Yii::t('app', 'Send Invoices As Pdf Attachments'),
            'factor_invoices_for_this_customer' => Yii::t('app', 'Factor Invoices For This Customer'),
            'hide_load_mileages_on_invoices' => Yii::t('app', 'Hide Load Mileages On Invoices'),
            'line_1' => Yii::t('app', 'Line 1'),
            'line_2' => Yii::t('app', 'Line 2'),
            'line_3' => Yii::t('app', 'Line 3'),
            'line_4' => Yii::t('app', 'Line 4'),
            'line_5' => Yii::t('app', 'Line 5'),
            'pre_billing_allowed_no_backup_required' => Yii::t('app', 'Pre Billing Allowed No Backup Required'),
            'send_all_load_scans_with_each_invoice' => Yii::t('app', 'Send All Load Scans With Each Invoice'),
            'original_documents_required' => Yii::t('app', 'Original Documents Required'),
            'bill_of_lading' => Yii::t('app', 'Bill Of Lading'),
            'delivery_receipt' => Yii::t('app', 'Delivery Receipt'),
            'purchase_order' => Yii::t('app', 'Purchase Order'),
            'transport_agreement' => Yii::t('app', 'Transport Agreement'),
            'interchanges' => Yii::t('app', 'Interchanges'),
            'packing_slip' => Yii::t('app', 'Packing Slip'),
            'scale_tickets' => Yii::t('app', 'Scale Tickets'),
            'load_receipts' => Yii::t('app', 'Load Receipts'),
            'work_orders' => Yii::t('app', 'Work Orders'),
            'rate_source' => Yii::t('app', 'Rate Source'),
            'acc_matrix_id' => Yii::t('app', 'Acc Matrix ID'),
            'discount' => Yii::t('app', 'Discount'),
            'system' => Yii::t('app', 'System'),
            'calc_by' => Yii::t('app', 'Calc By'),
            'rating' => Yii::t('app', 'Rating'),
            'agency' => Yii::t('app', 'Agency'),
            'approved_by' => Yii::t('app', 'Approved By'),
            'terms' => Yii::t('app', 'Terms'),
            'limit' => Yii::t('app', 'Limit'),
            'credit_hold' => Yii::t('app', 'Credit Hold'),
            'bond' => Yii::t('app', 'Bond'),
            'expires' => Yii::t('app', 'Expires'),
            'number' => Yii::t('app', 'Number'),
            'bank' => Yii::t('app', 'Bank'),
            'salesman_1' => Yii::t('app', 'Salesman 1'),
            'pay_type_1' => Yii::t('app', 'Pay Type 1'),
            'rate_1' => Yii::t('app', 'Rate 1'),
            'salesman_2' => Yii::t('app', 'Salesman 2'),
            'pay_type_2' => Yii::t('app', 'Pay Type 2'),
            'rate_2' => Yii::t('app', 'Rate 2'),
            'mail_list' => Yii::t('app', 'Mail List'),
            'send_210' => Yii::t('app', 'Send 210'),
            'send_214' => Yii::t('app', 'Send 214'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccMatrix()
    {
        return $this->hasOne(\common\models\AccessorialMatrix::className(), ['id' => 'acc_matrix_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOtherBillTo()
    {
        return $this->hasOne(\common\models\Customer::className(), ['id' => 'other_bill_to']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(\common\models\Customer::className(), ['other_bill_to' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTerms0()
    {
        return $this->hasOne(\common\models\PaymentTermCode::className(), ['id' => 'terms']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(\common\models\State::className(), ['id' => 'state_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerContacts()
    {
        return $this->hasMany(\common\models\CustomerContact::className(), ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerContactNotes()
    {
        return $this->hasMany(\common\models\CustomerContactNote::className(), ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(\common\models\Document::className(), ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoads()
    {
        return $this->hasMany(\common\models\Load::className(), ['bill_to' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(\common\models\Location::className(), ['bill_to' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrucks()
    {
        return $this->hasMany(\common\models\Truck::className(), ['owned_by_customer_id' => 'id']);
    }




}