<?php

namespace common\enums;


class Permission extends BaseEnum
{
    const SYSTEM_ADMINISTRATOR = 'System Administrator';

    const DISPATCH_SETUP = 'Dispatch Setup';
    const ACCOUNTING_SETUP = 'Accounting Setup';
    const MANAGEMENT_SETUP = 'Management Setup';
    const RATE_MATRICES_SETUP = 'Rate Matrices Setup';
    const INTERNATIONAL_SETUP = 'International Setup';

    const LISTS_MANAGER = 'Lists Manager';
    const TRUCK_LISTING = 'Truck Listing';
    const ADD_EDIT_TRUCKS = 'Add/Edit Trucks';
    const TRAILER_LISTING = 'Trailer Listing';
    const ADD_EDIT_TRAILERS = 'Add/Edit Trailers';
    const CARRIER_LISTING = 'Carrier Listing';
    const ADD_EDIT_CARRIERS = 'Add/Edit Carriers';
    const EDIT_CARRIER_APPROVAL = 'Edit Carrier Approval';
    const VENDOR_LISTING = 'Vendor Listing';
    const ADD_EDIT_VENDORS = 'Add/Edit Vendors';
    const LOCATION_LISTING = 'Location Listing';
    const ADD_EDIT_LOCATIONS = 'Add/Edit Locations';
    const CUSTOMER_LISTING = 'Customer Listing';
    const ADD_EDIT_CUSTOMERS = 'Add/Edit Customers';
    const EDIT_CUSTOMER_CREDIT = 'Edit Customer Credit';
    const EDIT_CUSTOMER_SALES_INFO = 'Edit Customer Sales Info';

    const PERSONAL_TASKS = 'Personal Tasks';
    const SERVICE_PROJECTS = 'Service Projects';
    const SALES_PROJECTS_CUSTOMERS = 'Sales Projects - Customers';
    const SALES_PROJECTS_PROSPECTS = 'Sales Projects - Prospects';

    const DRIVER_MESSAGING = 'Driver Messaging';
    const LOAD_STATUS_UPDATES = 'Load Status Updates';

    const SHIPPER_QUOTE_REQUESTS = 'Shipper Quote Requests';
    const SHIPPER_PICKUP_REQUESTS = 'Shipper Pickup Requests';
    const CARRIER_DISPATCH_REQUESTS = 'Carrier Dispatch Requests';
    const DRIVER_APPLICATIONS = 'Driver Applications';
    const CARRIER_APPLICATIONS = 'Carrier Applications';

    const OPERATIONS_ANALYSIS = 'Operations Analysis';
    const FINANCIAL_ANALYSIS = 'Financial Analysis';

    const VIEW_REPORTS = 'View Reports';
    const CREATE_REPORTS = 'Create Reports';

    const COMPANIES = 'Companies';
    const PERSONNEL = 'Personnel';
    const OPERATIONS = 'Operations';
    const ACCOUNTING = 'Accounting';
    const COMPLIANCE = 'Compliance';
    const MAINTENANCE = 'Maintenance';
    const FUEL_AND_MILES = 'Fuel & Miles';

    const DISPATCH_MANAGER = 'Dispatch Manager';
    const TRUCKLOAD_COMPANY_DISPATCH = 'Truckload Company Dispatch';
    const TRUCKLOAD_FREIGHT_BROKERAGE = 'Truckload Freight Brokerage';
    const SEARCH_DISPATCH = 'Search Dispatch';
    const RESEARCH_DISPATCH = 'Research Dispatch';
    const ACTIVITY_REPORTS = 'Activity Reports';
    const REVENUE_REPORTS = 'Revenue Reports';

    const JOB_ORDERS = 'Job Orders';
    const FLEET_TRACKER = 'Fleet Tracker';
    const ADD_EDIT_UNITS = 'Add/Edit Units';
    const DISPATCH_SCHEDULER = 'Dispatch Scheduler';
    const APPOINTMENT_SCHEDULER = 'Appointment Scheduler';
    const CALLIN_LOADS_TRUCKS = 'Call-In Loads/Trucks';
    const RATE_QUOTES = 'Rate Quotes';

    const CHECK_CALLS = 'Check Calls';
    const HOURS_OF_SERVICE = 'Hours Of Service';
    const ISSUE_ADVANCES = 'Issue Advances';
    const FIND_RATE = 'Find Rate';
    const FIND_SHIPPER = 'Find Shipper';
    const FIND_CARRIER = 'Find Carrier';
    const CARRIER_REPORT = 'Carrier Report';
    const LOAD_BOARD = 'Load Board';
    const TRUCK_BOARD = 'Truck Board';
    const LOAD_SHARE = 'Load Share';
    const EDI = 'EDI';
    const MAPPING = 'Mapping';

    const ADD_EDIT_LOADS = 'Add/Edit Loads';
    const EDIT_DISPATCHED_LOADS = 'Edit Dispatched Loads';
    const EDIT_INVOICED_LOADS = 'Edit Invoiced Loads';
    const DUPLICATE_LOADS = 'Duplicate Loads';
    const ADD_EDIT_DISPATCH_FILTERS = 'Add/Edit Dispatch Filters';
    const AUDIT_DISPATCHERS_ACTIVITIES = 'Audit Dispatcher\'s Activities';
    const INPUT_LOAD_MILEAGE_MANUALLY = 'Input Load Mileage Manually';
    const ADD_EDIT_LOAD_COMMISSIONS = 'Add/Edit Load Commissions';
    const EDIT_PAY_AFTER_LOAD_IS_INVOICED = 'Edit Pay After Load Is Invoiced';
    const CUSTOMIZE_LOAD_BOOKING = 'Customize Load Booking';
    const VIEW_LOAD_RATING_INFORMATION = 'View Load Rating Information';

    const ACCOUNTING_MANAGER = 'Accounting Manager';
    const SEARCH_ACCOUNTING = 'Search Accounting';
    const VIEW_JOURNAL_ENTRIES = 'View Journal Entries';
    const ADD_EDIT_JOURNAL_ENTRIES = 'Add/Edit Journal Entries';
    const ASSET_REGISTERS = 'Asset Registers';
    const LIABILITY_REGISTERS = 'Liability Registers';
    const EQUITY_REGISTERS = 'Equity Registers';
    const INCOME_REGISTERS = 'Income Registers';
    const EXPENSE_REGISTERS = 'Expense Registers';
    const PERIOD_END_CLOSING = 'Period End Closing';
    const JOURNAL_ANALYZER = 'Journal Analyzer';
    const ASSET_MANAGER = 'Asset Manager';
    const NOTES_PAYABLE_MANAGER = 'Notes Payable Manager';
    const NOTES_RECEIVABLE_MANAGER = 'Notes Receivable Manager';

    const VIEW_QUICK_HISTORIES = 'View Quick Histories';
    const AUDIT_COLLECTORS_ACTIVITIES = 'Audit Collector\'s Activities';
    const AUDIT_USERS_RECURRING_ENTRIES = 'Audit User\'s Recurring Entries';
    const EDIT_TRANSACTIONS_IN_CLOSED_PERIODS = 'Edit Transactions In Closed Periods';

    const RECEIVABLES = 'Receivables';
    const PAYABLES = 'Payables';
    const BANKING = 'Banking';
    const PAYROLL_AND_PERSONNEL = 'Payroll & Personnel';
    const ACCOUNTING_REPORTS_PAYROLL_TAXES = 'Accounting Reports Payroll Taxes';
    const SALES_STUDIES = 'Sales Studies';
    const FINANCIAL_STATEMENTS = 'Financial Statements';
    const DRILL_DOWN_REPORTING = 'Drill Down Reporting';

    const EXPORT_ACCOUNTING_DATA = 'Export Accounting Data';
    const RECURRING_ENTRIES = 'Recurring Entries';
    const PAY_CARRIERS = 'Pay Carriers';
    const PAY_CARRIERS_CREATE_INVOICES = 'Pay Carriers - Create Invoices';
    const PAY_CARRIERS_CREATE_CHECKS = 'Pay Carriers - Create Checks';
    const LOAD_CLEARING = 'Load Clearing';
    const POST_LOAD_BACKUP = 'Post Load Backup';

    const POST_LOAD_RECEIPTS = 'Post Load Receipts';
    const LOAD_BILLING_TL = 'Load Billing - TL';
    const ADD_EDIT_INVOICES = 'Add/Edit Invoices';
    const VIEW_INVOICED = 'View Invoiced';
    const INVOICE_SPOOLER = 'Invoice Spooler';
    const ADD_EDIT_INVOICE_PAYMENTS = 'Add/Edit Invoice Payments';
    const VIEW_INVOICE_PAYMENTS = 'View Invoice Payments';
    const COLLECTION_MANAGER = 'Collection Manager';
    const A_R_REGISTER = 'A/R Register';

    const ADD_EDIT_BILLS = 'Add/Edit Bills';
    const VIEW_BILLS = 'View Bills';
    const ADD_EDIT_PURCHASE_ORDERS = 'Add/Edit Purchase Orders';
    const ADD_EDIT_BILL_PAYMENTS = 'Add/Edit Bill Payments';
    const VIEW_BILL_PAYMENTS = 'View Bill Payments';
    const A_P_REGISTER = 'A/P Register';
    const CREDIT_ACCOUNTS = 'Credit Accounts';

    const ADD_EDIT_CHECKS = 'Add/Edit Checks';
    const VIEW_CHECKS = 'View Checks';
    const CHECK_SPOOLER = 'Check Spooler';
    const PRINT_BILL_PAYMENT_CHECKS = 'Print Bill Payment Checks';
    const PRINT_DRIVER_PAYCHECKS = 'Print Driver Paychecks';
    const PRINT_EMPLOYEE_PAYCHECKS = 'Print Employee Paychecks';
    const PRINT_EXECUTIVE_PAYCHECKS = 'Print Executive Paychecks';
    const PRINT_AGENT_SETTLEMENT_CHECKS = 'Print Agent Settlement Checks';
    const ADD_EDIT_DEPOSITS = 'Add/Edit Deposits';
    const VIEW_DEPOSITS = 'View Deposits';
    const RECONCILE_BANK_ACCOUNTS = 'Reconcile Bank Accounts';
    const BANK_REGISTERS = 'Bank Registers';

    const ADD_EDIT_DRIVERS = 'Add/Edit Drivers';
    const ADD_EDIT_EMPLOYEES = 'Add/Edit Employees';
    const ADD_EDIT_EXECUTIVES = 'Add/Edit Executives';
    const DRIVER_PAYROLL_ADD_EDIT = 'Driver Payroll - Add/Edit';
    const DRIVER_PAYROLL_READ_ONLY = 'Driver Payroll - Read Only';
    const TRUCK_OWNER_PAYROLL_ADD_EDIT = 'Truck Owner Payroll - Add/Edit';
    const TRUCK_OWNER_PAYROLL_READ_ONLY = 'Truck Owner Payroll - Read Only';
    const EMPLOYEE_PAYROLL_ADD_EDIT = 'Employee Payroll - Add/Edit';
    const EMPLOYEE_PAYROLL_READ_ONLY = 'Employee Payroll - Read Only';
    const EXECUTIVE_PAYROLL_ADD_EDIT = 'Executive Payroll - Add/Edit';
    const EXECUTIVE_PAYROLL_READ_ONLY = 'Executive Payroll - Read Only';
    const AGENT_PAYROLL_ADD_EDIT = 'Agent Payroll - Add/Edit';
    const AGENT_PAYROLL_READ_ONLY = 'Agent Payroll - Read Only';
    const ADVANCES = 'Advances';
    const ESCROW = 'Escrow';
    const PAYROLL_JOURNAL = 'Payroll Journal';
    const PAYROLL_PAYROLL_TAXES = 'Payroll Payroll Taxes';

    const SAFETY_AND_COMPLIANCE = 'Safety and Compliance';
    const FUEL_MANAGEMENT = 'Fuel Management';
    const IMPORT_FUEL_CARD_DATA = 'Import Fuel Card Data';
    const FLEET_MAINTENANCE = 'Fleet Maintenance';
    const PART_PURCHASES = 'Part Purchases';
    const CAMERA_EVENTS = 'Camera Events';
    const DRIVER_SCORE_CARDS = 'Driver Score Cards';
    const OVERRIDE_UNIT_STATUS = 'Override Unit Status';

    const VIEW_DOCUMENT_SCANS = 'View Document Scans';
    const ADD_EDIT_DOCUMENT_SCANS = 'Add/Edit Document Scans';
    const VIEW_OTHER_BACKUP_SCANS = 'View Other Backup Scans';
    const VIEW_COMPANY_IMAGES = 'View Company Images';
    const ADD_EDIT_COMPANY_IMAGES = 'Add/Edit Company Images';
    const VIEW_TRUCK_IMAGES = 'View Truck Images';
    const ADD_EDIT_TRUCK_IMAGES = 'Add/Edit Truck Images';
    const VIEW_TRAILER_IMAGES = 'View Trailer Images';
    const ADD_EDIT_TRAILER_IMAGES = 'Add/Edit Trailer Images';
    const VIEW_CARRIER_IMAGES = 'View Carrier Images';
    const ADD_EDIT_CARRIER_IMAGES = 'Add/Edit Carrier Images';
    const VIEW_VENDOR_IMAGES = 'View Vendor Images';
    const ADD_EDIT_VENDOR_IMAGES = 'Add/Edit Vendor Images';
    const VIEW_LOCATION_IMAGES = 'View Location Images';
    const ADD_EDIT_LOCATION_IMAGES = 'Add/Edit Location Images';
    const VIEW_CUSTOMER_IMAGES = 'View Customer Images';
    const ADD_EDIT_CUSTOMER_IMAGES = 'Add/Edit Customer Images';
    const VIEW_CLAIM_IMAGES = 'View Claim Images';
    const ADD_EDIT_CLAIM_IMAGES = 'Add/Edit Claim Images';
    const VIEW_ACCIDENT_IMAGES = 'View Accident Images';
    const ADD_EDIT_ACCIDENT_IMAGES = 'Add/Edit Accident Images';
    const VIEW_DRIVER_IMAGES = 'View Driver Images';
    const ADD_EDIT_DRIVER_IMAGES = 'Add/Edit Driver Images';
    const VIEW_EXECUTIVE_IMAGES = 'View Executive Images';
    const ADD_EDIT_EXECUTIVE_IMAGES = 'Add/Edit Executive Images';
    const VIEW_EMPLOYEE_IMAGES = 'View Employee Images';
    const ADD_EDIT_EMPLOYEE_IMAGES = 'Add/Edit Employee Images';
    const IMPORT_IMAGES = 'Import Images';
    const ARCHIVE_IMAGES = 'Archive Images';
    const TRANSMIT_IMAGES = 'Transmit Images';

    const REPORTER_PRO = 'Reporter Pro';

    const CUSTOM_MODULE = 'Custom Module';
}